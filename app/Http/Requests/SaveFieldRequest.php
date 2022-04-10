<?php

namespace App\Http\Requests;

use App\Models\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use function __;

class SaveFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'max:250',
                Rule::unique('fields', 'title')
                    ->ignore($this->route('subscriber')?->id)
            ],
            'type' => 'required|in:' . implode(',', Field::TYPES),
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => __('Title'),
            'type' => __('Type'),
        ];
    }
}
