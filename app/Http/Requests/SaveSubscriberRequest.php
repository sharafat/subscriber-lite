<?php

namespace App\Http\Requests;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveSubscriberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:250',
            'email' => [
                'required',
                'max:250',
                'email:rfc,dns',
                Rule::unique('subscribers', 'email')
                    ->ignore($this->route('subscriber')?->id)
                    ->withoutTrashed()
            ],
            'state' => 'nullable|in:' . implode(',', Subscriber::STATES),
            'fields.*.title' => 'nullable|max:250|exists:fields,title',
            'fields.*.value' => [
                function ($attribute, $value, $fail) {
                    $this->validateCustomField($attribute, $value, $fail);
                }
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Name'),
            'email' => __('Email'),
            'state' => __('State'),
            'fields.*.title' => __('Field Title'),
            'fields.*.type' => __('Field Type'),
            'fields.*.value' => __('Field Value'),
        ];
    }

    private function validateCustomField(string $attribute, mixed $value, callable $fail): void
    {
        $attributeIndex = explode('.', $attribute)[1];
        $fieldTitle = $this->input("fields.$attributeIndex.title");
        $field = Field::where('title', $fieldTitle)->first();

        if (!$field) {
            return;
        }

        $validationError = $field->validate($value);

        if ($validationError) {
            $fail($validationError);
        }
    }
}
