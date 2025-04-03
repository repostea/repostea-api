<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCommentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => 'integer|min:5|max:50',
        ];
    }
}
