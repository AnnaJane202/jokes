<?php

namespace App\Http\Requests\Client;

use App\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'reportable_type' => 'required|string|in:post,user,comment',
            'reportable_id'   => 'required|integer',
            'type'            => 'required|string|in:' . implode(',', array_keys(Report::getViolationTypes())),
            'reason'          => 'required|string|min:10|max:1000',
            'evidence_ids'    => 'nullable|array',
            'evidence_ids.*'  => 'exists:report_evidence,id',
        ];
    }

    public function messages(): array
    {
        return [
            'reportable_type.required' => 'Не указан тип контента.',
            'reportable_id.required'   => 'Не указан ID контента.',
            'type.required'            => 'Выберите тип нарушения.',
            'type.in'                  => 'Некорректный тип нарушения.',
            'reason.required'          => 'Опишите причину жалобы.',
            'reason.min'               => 'Причина должна содержать минимум 10 символов.',
            'reason.max'               => 'Причина не может быть длиннее 1000 символов.',
            'evidence_ids.*.exists'    => 'Один из файлов не существует.',
        ];
    }
}
