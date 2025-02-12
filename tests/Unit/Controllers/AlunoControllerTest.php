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
use PHPUnit\Framework\Attributes\Test;

class AlunoControllerTest extends TestCase
{
    private $serviceMock;
    private $modelMock;
    private $controller;
    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = Mockery::mock(AlunoService::class);
        $this->modelMock = Mockery::mock(Aluno::class);
        $this->controller = new AlunoController($this->serviceMock, $this->modelMock);
    }

    #[Test]
    public function deve_retornar_colecao_de_alunos_no_index()
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

    #[Test]
    public function deve_armazenar_um_novo_aluno_com_sucesso()
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

    #[Test]
    public function deve_falhar_ao_salvar_um_novo_aluno()
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

    #[Test]
    public function deve_mostrar_um_aluno()
    {
        $this->modelMock->shouldReceive('getAttribute')->andReturn(1);

        $response = $this->controller->show($this->modelMock);
        $this->assertInstanceOf(ShowResource::class, $response);
    }

    #[Test]
    public function deve_atualizar_um_aluno_com_sucesso()
    {
        $request = Mockery::mock(UpdateRequest::class);
        $this->serviceMock
            ->shouldReceive('update')
            ->once()
            ->with($this->modelMock, $request)
            ->andReturn(true);

        $response = $this->controller->update($request, $this->modelMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->status());
    }

    #[Test]
    public function deve_falhar_ao_atualizar_um_aluno()
    {
        $request = Mockery::mock(UpdateRequest::class);
        $this->serviceMock
            ->shouldReceive('update')
            ->once()
            ->with($this->modelMock, $request)
            ->andReturn(false);

        $response = $this->controller->update($request, $this->modelMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->status());
    }

    #[Test]
    public function deve_deletar_um_aluno_com_sucesso()
    {
        $this->modelMock->shouldReceive('delete')->once();

        $response = $this->controller->destroy($this->modelMock);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->status());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
