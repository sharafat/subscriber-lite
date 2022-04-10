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
            ],
            'state' => 'nullable|in:' . implode(',', Subscriber::STATES),
            'fields.*.title' => 'nullable|max:250|exists:fields,title',
            'fields.*.value' => [
                'required_with:fields.*.title',
                function ($attribute, $value, $fail) {
                    $this->validateIfFieldValueConformsToFieldType($attribute, $value, $fail);
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

    private function validateIfFieldValueConformsToFieldType(string $attribute, mixed $value, callable $fail): void
    {
        $attributeIndex = explode('.', $attribute)[1];
        $fieldTitle = $this->input("fields.$attributeIndex.title");
        $field = Field::where('title', $fieldTitle)->first();

        switch ($field?->type) {
            case Field::TYPE_DATE:
                if (!(bool)strtotime($value)) {
                    $fail(__('Value must be a date.'));
                }
                break;
            case Field::TYPE_NUMBER:
                if (!is_numeric($value)) {
                    $fail(__('Value must be a number.'));
                }
                break;
            case Field::TYPE_BOOLEAN:
                if (!in_array($value, [true, false, 0, 1], false)) {
                    $fail(__('Value must be a boolean.'));
                }
                break;
            case Field::TYPE_STRING:
                $maxLength = 250;
                if (strlen($value) > $maxLength) {
                    $fail(__('Length cannot be greater than :maxlength.', ['maxlength' => $maxLength]));
                }
                break;
        }
    }
}
