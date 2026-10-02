<?php

namespace App\Http\Requests\Client\Comment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->id() === $this->comment->user_id;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Текст комментария обязателен',
            'content.max' => 'Комментарий не может быть длиннее 2000 символов',
        ];
    }
}
