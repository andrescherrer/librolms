<?php

namespace App\Http\Controllers;

use App\Http\Requests\Curso\IndexRequest;
use App\Http\Requests\Curso\StoreRequest;
use App\Http\Resources\Curso\IndexCollection;
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function __construct(
        private CursoService $service,
        private Curso $model,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): IndexCollection
    {
        return new IndexCollection($this->service->filter($request));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $cursoCriado = $this->service->create($request);

        if (!$cursoCriado) {
            return response()->json(['message' => 'Erro ao salvar o curso'], JsonResponse::HTTP_BAD_REQUEST);
        } else {
            return response()->json(['message' => 'Curso criado com sucesso'], JsonResponse::HTTP_CREATED);            
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        //
    }
}
