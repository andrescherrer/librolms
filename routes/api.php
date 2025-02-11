<?php

use App\Http\Controllers\AlunoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\MatriculaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function() {
    Route::apiResource('cursos', CursoController::class);
    Route::apiResource('alunos', AlunoController::class);
    Route::apiResource('matriculas', MatriculaController::class);
    Route::get('/listar', [MatriculaController::class, 'alunos_por_faixa_etaria_por_curso_e_sexo']);
});