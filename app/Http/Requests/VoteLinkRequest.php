<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'value' => 'required|integer|in:1,-1',
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => trans('validation.required', ['attribute' => 'vote value']),
            'value.in' => trans('validation.in', ['attribute' => 'vote value']),
        ];
    }
}
