<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'aluno_id' => ['sometimes'],
            'curso_id' => ['sometimes'],
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.sometimes' => 'O campo aluno_id é obrigatório se for preenchido.',
            
            'curso_id.sometimes' => 'O campo curso_id é obrigatório se for preenchido.',
        ];
    }
}
