<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'writer_name' => 'required|string|max:255',
            'url' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1',
            'active' => 'nullable|integer|in:0,1',
            'card' => 'nullable|image|max:5120',
            'writer_image' => 'nullable|image|max:5120',
            'blog_category_id' => 'nullable|integer|exists:blog_categories,id',
            'tag_ids' => 'nullable|array|max:50',
            'tag_ids.*' => 'integer|exists:tags,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('tag_ids')) {
            $this->merge(['tag_ids' => []]);
        }
        if ($this->input('blog_category_id') === '' || $this->input('blog_category_id') === null) {
            $this->merge(['blog_category_id' => null]);
        }
    }
}
