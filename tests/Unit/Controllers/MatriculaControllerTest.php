<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MatriculaController;
use App\Http\Requests\Matricula\{IndexRequest, StoreRequest, UpdateRequest};
use App\Http\Resources\Matricula\{IndexCollection, ShowResource};
use App\Models\Matricula;
use App\Services\MatriculaService;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MatriculaControllerTest extends TestCase
{
    protected $serviceMock;
    protected $modelMock;
    protected $controller;

    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->serviceMock = Mockery::mock(MatriculaService::class);
        $this->modelMock = Mockery::mock(Matricula::class);
        $this->controller = new MatriculaController($this->serviceMock, $this->modelMock);
    }

    #[Test]
    public function deve_retornar_colecao_de_matriculas_no_index()
{
    $mockRequest = Mockery::mock(IndexRequest::class);

    $items = collect([['id' => 1, 'aluno_id' => 1, 'curso_id' => 1]]);
    $paginator = new \Illuminate\Pagination\LengthAwarePaginator($items, 1, 10);

    $this->serviceMock->shouldReceive('filter')->once()->with($mockRequest)->andReturn($paginator);

    $response = $this->controller->index($mockRequest);

    $this->assertInstanceOf(IndexCollection::class, $response);
}
    #[Test]
    public function deve_armazenar_uma_matricula_com_sucesso()
    {
        $mockRequest = Mockery::mock(StoreRequest::class);

        $this->serviceMock->shouldReceive('create')->once()->with($mockRequest)->andReturn(true);

        $response = $this->controller->store($mockRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->status());
    }

    #[Test]
    public function deve_falhar_ao_armazenar_uma_matricula()
    {
        $mockRequest = Mockery::mock(StoreRequest::class);

        $this->serviceMock->shouldReceive('create')->once()->with($mockRequest)->andReturn(false);

        $response = $this->controller->store($mockRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->status());
    }

    #[Test]
    public function deve_retornar_uma_matricula_no_show()
    {
        $matricula = Mockery::mock(Matricula::class);
        $mockResource = Mockery::mock(ShowResource::class);

        $response = $this->controller->show($matricula);

        $this->assertInstanceOf(ShowResource::class, $response);
    }

    #[Test]
    public function deve_atualizar_uma_matricula_com_sucesso()
    {
        $mockRequest = Mockery::mock(UpdateRequest::class);
        $matricula = Mockery::mock(Matricula::class);

        $this->serviceMock->shouldReceive('update')->once()->with($matricula, $mockRequest)->andReturn(true);

        $response = $this->controller->update($mockRequest, $matricula);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }

    #[Test]
    public function deve_falhar_ao_atualizar_uma_matricula()
    {
        $mockRequest = Mockery::mock(UpdateRequest::class);
        $matricula = Mockery::mock(Matricula::class);

        $this->serviceMock->shouldReceive('update')->once()->with($matricula, $mockRequest)->andReturn(false);

        $response = $this->controller->update($mockRequest, $matricula);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->status());
    }

    #[Test]
    public function deve_excluir_uma_matricula_com_sucesso()
    {
        $matricula = Mockery::mock(Matricula::class);
        $matricula->shouldReceive('delete')->once()->andReturn(true);

        $response = $this->controller->destroy($matricula);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->status());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
