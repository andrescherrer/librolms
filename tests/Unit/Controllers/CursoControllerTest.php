<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\CursoController;
use App\Http\Requests\Curso\{IndexRequest, StoreRequest, UpdateRequest};
use App\Http\Resources\Curso\{IndexCollection, ShowResource};
use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CursoControllerTest extends TestCase
{
    private $serviceMock;
    private $modelMock;
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceMock = Mockery::mock(CursoService::class);
        $this->modelMock = Mockery::mock(Curso::class);
        $this->controller = new CursoController($this->serviceMock, $this->modelMock);
    }

    #[Test]
    public function deve_retornar_uma_colecao_de_cursos_ao_chamar_o_metodo_index()
    {
        $requestMock = Mockery::mock(IndexRequest::class);

        $items = collect([]);
        $paginator = new LengthAwarePaginator($items, $items->count(), 10);

        $this->serviceMock->shouldReceive('filter')->once()->with($requestMock)->andReturn($paginator);

        $response = $this->controller->index($requestMock);

        $this->assertInstanceOf(IndexCollection::class, $response);
    }

    #[Test]
    public function deve_criar_um_curso_com_sucesso()
    {
        $requestMock = Mockery::mock(StoreRequest::class);
        $this->serviceMock->shouldReceive('create')->once()->with($requestMock)->andReturn(true);

        $response = $this->controller->store($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['message' => 'Curso criado com sucesso'], $response->getData(true));
    }

    #[Test]
    public function deve_retornar_erro_quando_a_criacao_do_curso_falhar()
    {
        $requestMock = Mockery::mock(StoreRequest::class);
        $this->serviceMock->shouldReceive('create')->once()->with($requestMock)->andReturn(false);

        $response = $this->controller->store($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(['message' => 'Erro ao salvar o curso'], $response->getData(true));
    }

    #[Test]
    public function deve_retornar_curso_ao_chamar_o_metodo_show()
    {
        $cursoMock = Mockery::mock(Curso::class);

        $response = $this->controller->show($cursoMock);

        $this->assertInstanceOf(ShowResource::class, $response);
    }

    #[Test]
    public function deve_atualizar_um_curso_com_sucesso()
    {
        $requestMock = Mockery::mock(UpdateRequest::class);
        $cursoMock = Mockery::mock(Curso::class);
        $this->serviceMock->shouldReceive('update')->once()->with($cursoMock, $requestMock)->andReturn(true);

        $response = $this->controller->update($requestMock, $cursoMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['message' => 'Curso atualizado com sucesso'], $response->getData(true));
    }

    #[Test]
    public function deve_retornar_erro_quando_atualizacao_do_curso_falhar()
    {
        $requestMock = Mockery::mock(UpdateRequest::class);
        $cursoMock = Mockery::mock(Curso::class);
        $this->serviceMock->shouldReceive('update')->once()->with($cursoMock, $requestMock)->andReturn(false);

        $response = $this->controller->update($requestMock, $cursoMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertEquals(['message' => 'Erro ao atualizar o curso'], $response->getData(true));
    }

    #[Test]
    public function deve_deletar_um_curso_com_sucesso()
    {
        $cursoMock = Mockery::mock(Curso::class);
        $cursoMock->shouldReceive('delete')->once()->andReturn(true);

        $response = $this->controller->destroy($cursoMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals(['message' => 'Curso excluído com sucesso'], $response->getData(true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
