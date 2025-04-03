<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matricula\{IndexRequest,StoreRequest, UpdateRequest};
use App\Http\Resources\Matricula\IndexCollection;
use App\Http\Resources\Matricula\ShowResource;
use App\Models\Matricula;
use App\Services\MatriculaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatriculaController extends Controller
{
    public function __construct(
        private MatriculaService $service,
        private Matricula $model,
    ) {}

    /**
     * Listar Matrícula
     */
    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    /**
     * Adicionar Matrícula
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $message = 'Erro ao salvar a matrícula ';
        try {
            $matriculaCriada = $this->service->create($request);

            if (!$matriculaCriada) {
                return response()->json(['message' => 'Erro ao salvar a matrícula'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Matrícula criada com sucesso'], JsonResponse::HTTP_CREATED);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Exibit Matrícula
     */
    public function show(Matricula $matricula): ShowResource | JsonResponse
    {
        $message = 'Erro ao buscar a matrícula ';
        try {
            return new ShowResource($matricula);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Atualizar Matrícula
     */
    public function update(UpdateRequest $request, Matricula $matricula): JsonResponse
    {
        $message = 'Erro ao atualizar a matrícula ';
        try {
            $matriculaAtualizada = $this->service->update($matricula, $request);

            if (!$matriculaAtualizada) {
                return response()->json(['message' => 'Erro ao atualizar a matrícula'], JsonResponse::HTTP_BAD_REQUEST);
            } else {
                return response()->json(['message' => 'Matrícula atualizada com sucesso'], JsonResponse::HTTP_OK);            
            }
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Excluir Matrícula
     */
    public function destroy(Matricula $matricula): JsonResponse
    {
        $message = 'Erro ao excluir a matrícula ';
        try {
            $matricula->delete();
            
            return response()->json(['message' => 'Matrícula excluída com sucesso'], JsonResponse::HTTP_NO_CONTENT);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }

    /**
     * Listar Alunos por faixa etária, curso e sexo
     */
    public function alunos_por_faixa_etaria_por_curso_e_sexo()
    {
        $message = 'Erro ao buscar dados ';
        try {
            $dados = DB::table('matriculas as m')
            ->join('alunos as a', 'a.id', '=', 'm.aluno_id')
            ->join('cursos as c', 'c.id', '=', 'm.curso_id')
            ->select(
                'c.titulo',
                'a.sexo',
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, a.nascimento, CURDATE()) < 15 THEN 1 END) AS menor_que_15'),
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, a.nascimento, CURDATE()) BETWEEN 15 AND 18 THEN 1 END) AS entre_15_e_18'),
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, a.nascimento, CURDATE()) BETWEEN 19 AND 24 THEN 1 END) AS entre_19_e_24'),
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, a.nascimento, CURDATE()) BETWEEN 25 AND 30 THEN 1 END) AS entre_25_e_30'),
                DB::raw('COUNT(CASE WHEN TIMESTAMPDIFF(YEAR, a.nascimento, CURDATE()) > 30 THEN 1 END) AS maior_que_30')
            )
            ->groupBy('c.titulo', 'a.sexo')
            ->get();

            return response()->json($dados);
        } catch(\Throwable $th) {
            Log::critical($message . $th->getMessage());
            return response()->json(['message' => $message, 'error' => $th->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }        
    }
}
