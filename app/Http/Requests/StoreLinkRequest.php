<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:140',
            'url' => 'nullable|url|max:250',
            'content' => 'nullable|required_without:url',
            'description' => 'required|string|max:600',
            'tags' => 'required|array|min:1|max:5',
            'tags.*' => 'exists:tags,id',
            'nsfw' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('validation.required', ['attribute' => trans('app.title')]),
            'title.max' => trans('validation.max.string', ['attribute' => trans('app.title'), 'max' => 140]),
            'url.url' => trans('validation.url', ['attribute' => trans('app.url')]),
            'content.required_without' => trans('validation.required_without', ['attribute' => trans('app.content'), 'values' => trans('app.url')]),
            'description.required' => trans('validation.required', ['attribute' => trans('app.description')]),
            'tags.required' => trans('validation.required', ['attribute' => trans('app.tags')]),
            'tags.min' => trans('validation.min.array', ['attribute' => trans('app.tags'), 'min' => 1]),
            'tags.max' => trans('validation.max.array', ['attribute' => trans('app.tags'), 'max' => 5]),
        ];
    }
}
