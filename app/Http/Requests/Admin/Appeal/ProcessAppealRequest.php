<?php

namespace App\Http\Requests\Admin\Appeal;

use Illuminate\Foundation\Http\FormRequest;

class ProcessAppealRequest extends FormRequest
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
            'decision' => 'required|in:approve,reject,modify',
            'new_penalty_type' => 'required_if:decision,modify|string|in:warning,temp_ban,permanent_ban',
            'new_duration_days' => [
                'required_if:new_penalty_type,temp_ban',
                'nullable',
                'integer',
                'min:1',
                'max:365',
            ],
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'decision.required' => 'Необходимо выбрать решение',
            'decision.in' => 'Некорректное решение',
            'new_penalty_type.required_if' => 'Необходимо выбрать тип наказания',
            'new_duration_days.required_if' => 'Необходимо указать длительность',
            'new_duration_days.min' => 'Минимальная длительность — 1 день',
            'new_duration_days.max' => 'Максимальная длительность — 365 дней',
            'comment.max' => 'Комментарий не может быть длиннее 1000 символов',
        ];
    }
}
