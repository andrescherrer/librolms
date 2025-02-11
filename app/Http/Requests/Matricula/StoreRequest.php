<?php

namespace App\Http\Requests\Matricula;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'aluno_id' => ['required'],
            'curso_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'aluno_id.required' => 'O campo aluno_id é obrigatório.',
            
            'curso_id.required' => 'O campo curso_id é obrigatório.',
        ];
    }
}
