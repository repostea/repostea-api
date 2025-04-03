<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkPendingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort' => 'string|in:created_at,votes,karma',
            'direction' => 'string|in:asc,desc',
            'per_page' => 'integer|min:5|max:50',
        ];
    }
}
