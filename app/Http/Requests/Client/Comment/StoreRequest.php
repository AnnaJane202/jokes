<?php

namespace App\Http\Requests\Client\Comment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
        $post = $this->route('post');
        return [
            'content' => 'required|string|max:2000',
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id')->where('post_id', $post->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Текст комментария обязателен',
            'content.max' => 'Комментарий не может быть длиннее 2000 символов',
            'parent_id.exists' => 'Родительский комментарий не существует',
        ];
    }
}
