<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class ResolveReportRequest extends FormRequest
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
            'action' => 'required|in:create_violation,reject',
            'violation_type' => 'required_if:action,create_violation|string|in:' . implode(',', array_keys(Report::getViolationTypes())),
            'violation_reason' => 'required_if:action,create_violation|string|min:10|max:1000',
            'penalty_type' => 'required_if:action,create_violation|string|in:warning,temp_ban,permanent_ban,content_removal',
            'duration_days' => 'required_if:penalty_type,temp_ban|nullable|integer|min:1|max:365',
            'moderator_comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Выберите действие',
            'violation_type.required_if' => 'Выберите тип нарушения',
            'violation_reason.required_if' => 'Укажите причину нарушения',
            'penalty_type.required_if' => 'Выберите тип наказания',
        ];
    }
}
