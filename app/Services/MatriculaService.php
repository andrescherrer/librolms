<?php

namespace App\Services;

use App\Models\Matricula;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class MatriculaService extends Service
{
    public function __construct(
        private Matricula $model,
    ) {}
    public function filter(Request $request): LengthAwarePaginator
    {
        return Matricula
            ::with(
                ['aluno', 'curso']
            )
            ->whereHas('aluno', function(Builder $query) use ($request) {
                $this->aluno($request, $query);
            })            
            ->orderBy('id')
            ->paginate();
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

    public function update(Matricula $matricula, Request $request): bool
    {
        return $matricula->update($request->validated());
    }

    private function aluno($request, $query)
    {
        if ($request->filled('nome')) {
            $query->where('nome', 'LIKE', "%{$request->input('nome')}%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', "%{$request->input('email')}%");
        }        
    }    
}