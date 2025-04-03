<?php

namespace App\Http\Controllers;

use App\Http\Requests\Aluno\{IndexRequest, StoreRequest, UpdateRequest};
use App\Http\Resources\Aluno\{IndexCollection, ShowResource};
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
        $message = 'Erro ao salvar o aluno ';
        try {
            $alunoCriado = $this->service->create($request);

            if (!$alunoCriado) {
                return response()->json(['message' => 'Erro ao salvar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Aluno criado com sucesso'], JsonResponse::HTTP_CREATED);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
        
    }

    public function show(Aluno $aluno): ShowResource | JsonResponse
    {
        $message = 'Erro ao buscar o aluno ';
        try {
            return new ShowResource($aluno);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    public function update(UpdateRequest $request, Aluno $aluno): JsonResponse
    {
        $message = 'Erro ao atualizar o aluno ';
        try {
            $alunoAtualizado = $this->service->update($aluno, $request);

            if (!$alunoAtualizado) {
                return response()->json(['message' => 'Erro ao atualizar o aluno'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Aluno atualizado com sucesso'], JsonResponse::HTTP_OK);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    public function destroy(Aluno $aluno): JsonResponse
    {
        $message = 'Erro ao excluir o aluno ';
        try {
            $aluno->delete();

            return response()->json(['message' => 'Aluno excluído com sucesso'], JsonResponse::HTTP_NO_CONTENT);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }
}
