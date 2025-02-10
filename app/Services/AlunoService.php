<?php

namespace App\Services;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class AlunoService extends Service
{
    public function __construct(
        private Aluno $model,
    ) {}


    public function filter(Request $request): LengthAwarePaginator
    {
        return Aluno::when(
            $request->anyFilled([
                'nome',
                'email',
                'sexo',
                'nascimento',
            ]), function ($query) use ($request) {
                $this->aluno($request, $query);
            })
            ->orderBy('nome')
            ->paginate('20');
    }

    public function create(Request $request): bool
    {
        try {
            $this->model->create($request->all());
            return true;
        } catch (\Exception $e) {
            Log::critical("Erro ao salvar o aluno: ". $e->getMessage());
            return false;
        }
    }

    public function update(Aluno $aluno, Request $request): bool
    {
        return $aluno->update($request->validated());
    }

    private function aluno($request, $query)
    {
        if ($request->filled('nome')) {
            $query->where('nome', 'LIKE', "%{$request->input('nome')}%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', "%{$request->input('email')}%");
        }
        if ($request->filled('sexo')) {
            $query->where('sexo', $request->input('sexo'));
        }
        if ($request->filled('nascimento')) {
            $query->where('nascimento', $request->input('nascimento'));
        }
    }
}
