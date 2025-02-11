<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matricula\{IndexRequest,StoreRequest, UpdateRequest};
use App\Http\Resources\Matricula\IndexCollection;
use App\Http\Resources\Matricula\ShowResource;
use App\Models\Matricula;
use App\Services\MatriculaService;
use Illuminate\Http\JsonResponse;

class MatriculaController extends Controller
{
    public function __construct(
        private MatriculaService $service,
        private Matricula $model,
    ) {}

    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $matriculaCriada = $this->service->create($request);

        if (!$matriculaCriada) {
            return response()->json(['message' => 'Erro ao salvar a matrícula'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Matrícula criada com sucesso'], JsonResponse::HTTP_CREATED);            
        }
    }

    public function show(Matricula $matricula): ShowResource
    {
        return new ShowResource($matricula);
    }

    public function update(UpdateRequest $request, Matricula $matricula): JsonResponse
    {
        $matriculaAtualizada = $this->service->update($matricula, $request);

        if (!$matriculaAtualizada) {
            return response()->json(['message' => 'Erro ao atualizar a matrícula'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Matrícula atualizada com sucesso'], JsonResponse::HTTP_OK);            
        }
    }

    public function destroy(Matricula $matricula): JsonResponse
    {
        $matricula->delete();

        return response()->json(['message' => 'Matrícula excluída com sucesso'], JsonResponse::HTTP_NO_CONTENT);
    }
}
