<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Testموارد extends TestCase
{
    private $mockPdo;
    private $request;
    private $response;
    private $stream;

    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
    }

    public function testGetAllموارد()
    {
        $this->mockPdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM موارد')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'All موارد retrieved successfully']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $controller = new \App\Controller\مواردController($this->mockPdo);
        $response = $controller->getAll($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetمواردById()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM موارد WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'موارد retrieved successfully']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $controller = new \App\Controller\مواردController($this->mockPdo);
        $response = $controller->getById($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateموارد()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO موارد (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Test مورد', 'description' => 'Test description']);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'موارد created successfully']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(201);

        $controller = new \App\Controller\مواردController($this->mockPdo);
        $response = $controller->create($this->request, $this->response);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateموارد()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE موارد SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Test مورد', 'description' => 'Updated Test description']);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'موارد updated successfully']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $controller = new \App\Controller\مواردController($this->mockPdo);
        $response = $controller->update($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteموارد()
    {
        $this->mockPdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM موارد WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->response->expects($this->once())
            ->method('getBody')
            ->willReturn($this->stream);

        $this->stream->expects($this->once())
            ->method('write')
            ->with(json_encode(['message' => 'موارد deleted successfully']));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $controller = new \App\Controller\مواردController($this->mockPdo);
        $response = $controller->delete($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
    }
}