<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:2|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => trans('validation.required', ['attribute' => trans('app.content')]),
            'content.min' => trans('validation.min.string', ['attribute' => trans('app.content'), 'min' => 2]),
            'content.max' => trans('validation.max.string', ['attribute' => trans('app.content'), 'max' => 1000]),
            'parent_id.exists' => trans('validation.exists', ['attribute' => trans('app.parent_comment')]),
        ];
    }
}
