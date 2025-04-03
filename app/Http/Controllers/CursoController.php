<?php

namespace App\Http\Controllers;

use App\Http\Requests\Curso\{IndexRequest,StoreRequest, UpdateRequest};
use App\Http\Resources\Curso\{IndexCollection, ShowResource};
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CursoController extends Controller
{
    public function __construct(
        private CursoService $service,
        private Curso $model,
    ) {}

    /**
     * Listar Cursos
     */
    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    /**
     * Adicionar Curso
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $message = 'Erro ao salvar o curso ';
        try {
            $cursoCriado = $this->service->create($request);

            if (!$cursoCriado) {
                return response()->json(['message' => 'Erro ao salvar o curso'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Curso criado com sucesso'], JsonResponse::HTTP_CREATED);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Exibir Curso
     */
    public function show(Curso $curso): ShowResource | JsonResponse
    {
        $message = 'Erro ao buscar o curso: ';
        try {
            return new ShowResource($curso);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Atualizar Curso
     */
    public function update(UpdateRequest $request, Curso $curso): JsonResponse
    {
        $message = 'Erro ao atualizar o curso ';
        try {
            $cursoAtualizado = $this->service->update($curso, $request);

            if (!$cursoAtualizado) {
                return response()->json(['message' => 'Erro ao atualizar o curso'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Curso atualizado com sucesso'], JsonResponse::HTTP_OK);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }


    /**
     * Excluir Curso
     */
    public function destroy(Curso $curso): JsonResponse
    {
        $message = 'Erro ao excluir o curso ';
        try {
            $curso->delete();

            return response()->json(['message' => 'Curso excluído com sucesso'], JsonResponse::HTTP_NO_CONTENT);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => 'Erro ao excluir o aluno', 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }
}
