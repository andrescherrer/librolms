<?php

namespace App\Http\Controllers;

use App\Http\Requests\Curso\{IndexRequest,StoreRequest, UpdateRequest};
use App\Http\Resources\Curso\{IndexCollection, ShowResource};
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\JsonResponse;

class CursoController extends Controller
{
    public function __construct(
        private CursoService $service,
        private Curso $model,
    ) {}

    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $cursoCriado = $this->service->create($request);

        if (!$cursoCriado) {
            return response()->json(['message' => 'Erro ao salvar o curso'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Curso criado com sucesso'], JsonResponse::HTTP_CREATED);            
        }
    }

    public function show(Curso $curso): ShowResource
    {
        return new ShowResource($curso);
    }

    public function update(UpdateRequest $request, Curso $curso): JsonResponse
    {
        $cursoAtualizado = $this->service->update($curso, $request);

        if (!$cursoAtualizado) {
            return response()->json(['message' => 'Erro ao atualizar o curso'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Curso atualizado com sucesso'], JsonResponse::HTTP_OK);            
        }
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return response()->json(['message' => 'Curso excluído com sucesso'], JsonResponse::HTTP_NO_CONTENT);
    }
}
