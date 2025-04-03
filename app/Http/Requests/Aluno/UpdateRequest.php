<?php

namespace App\Http\Requests\Aluno;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:alunos,email,id'],
            'sexo' => ['sometimes', 'string', 'in:masculino,feminino,outro'],
            'nascimento' => ['sometimes', 'date', 'before:today', 'date_format:Y-m-d'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.string' => 'O campo nome deve ser um texto.',
            'nome.max' => 'O campo nome não pode ter mais de 255 caracteres.',
            
            'email.string' => 'O campo e-mail deve ser um texto.',
            'email.email' => 'O campo e-mail deve ser um endereço de e-mail válido.',
            'email.max' => 'O campo e-mail não pode ter mais de 255 caracteres.',
            'email.unique' => 'O e-mail informado já está em uso.',
            
            'sexo.string' => 'O campo sexo deve ser um texto.',
            'sexo.in' => 'O campo sexo deve ser masculino, feminino ou outro.',

            'nascimento.date' => 'O campo data de nascimento deve ser uma data válida.',
            'nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'nascimento.date_format' => 'A data de nascimento deve estar no formato AAAA-MM-DD',
        ];
    }
}
