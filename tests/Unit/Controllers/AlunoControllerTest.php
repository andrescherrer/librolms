<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AlunoController;
use App\Http\Requests\Aluno\{StoreRequest, UpdateRequest, IndexRequest};
use App\Http\Resources\Aluno\{IndexCollection, ShowResource};
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class AlunoControllerTest extends TestCase
{
    private AlunoService $service;
    private AlunoController $controller;
    private Aluno $aluno;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = Mockery::mock(AlunoService::class);
        $this->alunoMock = Mockery::mock(Aluno::class);
        $this->controller = new AlunoController($this->serviceMock, $this->alunoMock);
    }

    public function test_deve_retornar_colecao_de_alunos_no_index()
    {
        $request = Mockery::mock(IndexRequest::class);
        $paginator = new LengthAwarePaginator([], 0, 20);
        $this->serviceMock
                ->shouldReceive('filter')
                ->once()
                ->with($request)
                ->andReturn($paginator);

        $response = $this->controller->index($request);
        $this->assertInstanceOf(IndexCollection::class, $response);
    }

    public function test_deve_armazenar_um_novo_aluno_com_sucesso()
    {
        $request = Mockery::mock(StoreRequest::class);
        $this->serviceMock
            ->shouldReceive('create')
            ->once()
            ->with($request)
            ->andReturn(true);

        $response = $this->controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->status());
    }

    public function test_deve_falhar_ao_salvar_um_novo_aluno()
    {
        $request = Mockery::mock(StoreRequest::class);
        $this->serviceMock
            ->shouldReceive('create')
            ->once()
            ->with($request)
            ->andReturn(false);

        $response = $this->controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->status());
    }

    public function test_deve_mostrar_um_aluno()
    {
        $this->alunoMock->shouldReceive('getAttribute')->andReturn(1);

        $response = $this->controller->show($this->alunoMock);
        $this->assertInstanceOf(ShowResource::class, $response);
    }

    public function test_deve_atualizar_um_aluno_com_sucesso()
    {
        $request = Mockery::mock(UpdateRequest::class);
        $this->serviceMock
            ->shouldReceive('update')
            ->once()
            ->with($this->alunoMock, $request)
            ->andReturn(true);

        $response = $this->controller->update($request, $this->alunoMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }

    public function test_deve_falhar_ao_atualizar_um_aluno()
    {
        $request = Mockery::mock(UpdateRequest::class);
        $this->serviceMock
            ->shouldReceive('update')
            ->once()
            ->with($this->alunoMock, $request)
            ->andReturn(false);

        $response = $this->controller->update($request, $this->alunoMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->status());
    }

    public function test_deve_deletar_um_aluno_com_sucesso()
    {
        $this->alunoMock->shouldReceive('delete')->once();

        $response = $this->controller->destroy($this->alunoMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->status());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
