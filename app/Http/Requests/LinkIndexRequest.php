<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort' => 'string|in:promoted_at,votes,karma,clicks,created_at',
            'direction' => 'string|in:asc,desc',
            'per_page' => 'integer|min:5|max:50',
            'interval' => 'integer|min:1|max:43200',
        ];
    }
}
