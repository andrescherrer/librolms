<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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