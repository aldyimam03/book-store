<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
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
            'author_id' => ['required', 'exists:authors,id'],
            'book_id' => [
                'required',
                Rule::exists('books', 'id')->where(fn($q) => $q->where('author_id', $this->input('author_id')))
            ],
            'score' => ['required', 'integer', 'between:0,10'], 
        ];
    }

    public function messages(): array
    {
        return [
            'author_id.required' => 'Author harus dipilih.',
            'author_id.exists' => 'Author tidak valid.',
            'book_id.required' => 'Buku harus dipilih.',
            'book_id.exists' => 'Buku tidak valid atau tidak sesuai dengan author.',
            'score.required' => 'Rating harus dipilih.',
            'score.integer' => 'Rating harus berupa angka.',
            'score.between' => 'Rating harus antara 0 sampai 10.',
        ];
    }
}
