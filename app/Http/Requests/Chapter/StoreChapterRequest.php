<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'content' => ['nullable', 'string', 'max:1000'],
            'book_id' => ['required', 'integer', 'exists:books,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Chapter name is required.',
            'name.min' => 'Chapter name must be at least 2 characters.',
            'book_id.required' => 'Book is required.',
            'book_id.exists' => 'The selected book does not exist.',
        ];
    }
}
