<?php

namespace App\Http\Requests\Admin\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     * @param $postId
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $postId = $this->route('post');
        return [
            'title' => 'required|string',
            'slug' => [
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($postId),
            ],
            'likes' => 'numeric',
            'description' => 'string',
            'content' => 'required|string',
            'published' => 'required|boolean',
            'user_id' => 'required|numeric|exists:users,id',
            'category_id' => 'required|numeric|exists:categories,id',
        ];
    }
}
