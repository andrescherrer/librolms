<?php

namespace App\Http\Requests\Curso;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

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
        return $this->buildRules();
    }

    public function messages(): array
    {
        return $this->buildMessages();
    }

    private function buildRules(): array
    {
        $rules = [];

        $sometimesString = [
            'titulo',
        ];

        $nullableString = [
            'descricao',
        ];        

        foreach($sometimesString as $sometimes)
            $rules = Arr::add($rules, $sometimes, 'sometimes|string');

        foreach($nullableString as $nullable)
            $rules = Arr::add($rules, $nullable, 'nullable|string');

        return $rules;
    }

    private function buildMessages(): array
    {
        $messages = [];

        $camposTexto = [
            'titulo',
            'descricao',
        ];

        foreach ($camposTexto as $campoTexto)
            $messages = Arr::add($messages, $campoTexto . '.string', 'O campo ' . $campoTexto . ' deve ser texto.');

        return $messages;
    }
}
