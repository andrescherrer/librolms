<?php

namespace App\Http\Controllers;

use App\Http\Requests\Aluno\{IndexRequest, StoreRequest, UpdateRequest};
use App\Http\Resources\Aluno\{IndexCollection, ShowResource};
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\JsonResponse;

class AlunoController extends Controller
{
    public function __construct(
        private AlunoService $service,
        private Aluno $model,
    ) {}


    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $alunoCriado = $this->service->create($request);

        if (!$alunoCriado) {
            return response()->json(['message' => 'Erro ao salvar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Aluno criado com sucesso'], JsonResponse::HTTP_CREATED);            
        }
    }

    public function show(Aluno $aluno): ShowResource
    {
        return new ShowResource($aluno);
    }

    public function update(UpdateRequest $request, Aluno $aluno): JsonResponse
    {
        $alunoAtualizado = $this->service->update($aluno, $request);

        if (!$alunoAtualizado) {
            return response()->json(['message' => 'Erro ao atualizar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Aluno atualizado com sucesso'], JsonResponse::HTTP_OK);            
        }
    }

    public function destroy(Aluno $aluno): JsonResponse
    {
        $aluno->delete();

        return response()->json(['message' => 'Aluno excluído com sucesso'], JsonResponse::HTTP_NO_CONTENT);
    }
}
