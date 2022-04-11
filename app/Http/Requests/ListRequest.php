<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'nullable|int|min:1',
            'size' => 'nullable|int|min:1',
            'sort' => 'nullable',
        ];
    }
}
