<?php

namespace App\Http\Requests\Contributor;

use Illuminate\Foundation\Http\FormRequest;

class EditArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'string',
            'category_id' => ['integer', 'exists:categories,id'],
            'content' => 'string',
            'tags'=> ['nullable', 'regex:/^[^,]+(?:,[^,]+)*$/'],
            'image' => ['mimes:png,jpg,jpeg', 'max:2048'],
            'references' => 'regex:/^[^,]+(,[^,]+)*$/',
        ];
    }
}
