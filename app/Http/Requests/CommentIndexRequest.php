<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort' => 'string|in:karma,created_at',
            'direction' => 'string|in:asc,desc',
        ];
    }
}
