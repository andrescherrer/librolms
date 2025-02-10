<?php

namespace App\Http\Requests\Curso;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

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
        return $this->buildRules();
    }

    public function messages(): array
    {
        return $this->buildMessages();
    }

    private function buildRules(): array
    {
        $rules = [];

        $requiredString = [
            'titulo',            
        ];

        $nullableString = [
            'descricao',
        ];        

        foreach($requiredString as $required)
            $rules = Arr::add($rules, $required, 'required|string');

        foreach($nullableString as $nullable)
            $rules = Arr::add($rules, $nullable, 'nullable|string');

        return $rules;
    }

    private function buildMessages(): array
    {
        $messages = [];

        $camposObrigatorios = [
            'titulo',
        ];

        $camposTexto = [
            'titulo',
            'descricao',
        ];

        foreach ($camposObrigatorios as $campoObrigatorio)
            $messages = Arr::add( $messages, $campoObrigatorio . '.required', 'O campo ' . $campoObrigatorio . ' é obrigatório.');

        foreach ($camposTexto as $campoTexto)
            $messages = Arr::add( $messages, $campoTexto . '.string', 'O campo ' . $campoTexto . ' deve ser texto');

        return $messages;
    }
}
