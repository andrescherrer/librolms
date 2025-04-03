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
        try {
            $alunoCriado = $this->service->create($request);

            if (!$alunoCriado) {
                return response()->json(['message' => 'Erro ao salvar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Aluno criado com sucesso'], JsonResponse::HTTP_CREATED);            
            }
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao salvar o aluno', 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
        
    }

    public function show(Aluno $aluno): ShowResource | JsonResponse
    {
        try {
            return new ShowResource($aluno);
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao buscar o aluno', 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    public function update(UpdateRequest $request, Aluno $aluno): JsonResponse
    {
        try {
            $alunoAtualizado = $this->service->update($aluno, $request);

            if (!$alunoAtualizado) {
                return response()->json(['message' => 'Erro ao atualizar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Aluno atualizado com sucesso'], JsonResponse::HTTP_OK);            
            }
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao atualizar o aluno', 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    public function destroy(Aluno $aluno): JsonResponse
    {
        try {
            $aluno->delete();

            return response()->json(['message' => 'Aluno excluído com sucesso'], JsonResponse::HTTP_NO_CONTENT);
        } catch(\Throwable $th) {
            return response()->json(['message' => 'Erro ao excluir o aluno', 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }
}
