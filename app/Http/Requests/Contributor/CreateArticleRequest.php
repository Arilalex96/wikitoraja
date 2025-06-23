<?php

namespace App\Http\Requests\Contributor;

use Illuminate\Foundation\Http\FormRequest;

class CreateArticleRequest extends FormRequest
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
            'title' => ['required','string'],
            'category' => ['required', 'integer', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'tags'=> ['regex:/^[^,]+(?:,[^,]+)*$/'],
            'image' => ['required','mimes:png,jpg,jpeg', 'max:2048'],
            'references' => ['required','regex:/^[^,]+(,[^,]+)*$/']
        ];
    }
}
