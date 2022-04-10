<?php

namespace App\Http\Requests;

use App\Models\Subscriber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function __;

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
            'fields.*.value' => 'required_with:fields.*.title|max:250',
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
}
