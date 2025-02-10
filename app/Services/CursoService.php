<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CursoService extends Service
{
    public function __construct(
        private Curso $model,
    ) {}
    public function filter(Request $request): LengthAwarePaginator
    {
        return Curso::when(
            $request->anyFilled([
                'titulo',
                'descricao',
            ]), function ($query) use ($request) {
                $this->curso($request, $query);
            })
            ->orderBy('titulo')
            ->paginate('20');
    }

    public function create(Request $request): bool
    {
        try {
            $this->model->create($request->all());
            return true;
        }
        catch (\Exception $e) {
            Log::critical("Erro ao salvar o registro: ". $e->getMessage());
            return false;
        }        
    }

    public function update(Curso $curso, Request $request): bool
    {
        return $curso->update($request->validated());
    }

    private function curso($request, $query)
    {
        if ($request->filled('titulo')) {
            $query->where('titulo', 'LIKE', "%{$request->input('titulo')}%");
        }
        if ($request->filled('descricao')) {
            $query->where('descricao', 'LIKE', "%{$request->input('descricao')}%");
        }
    }
}