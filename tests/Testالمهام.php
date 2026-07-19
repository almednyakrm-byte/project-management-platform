<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class Testالمهام extends TestCase
{
    private $pdo;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetالمهام()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM المهام')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Task 1'],
                ['id' => 2, 'name' => 'Task 2'],
            ]);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        $controller = new المهامController($this->pdo);
        $response = $controller->handle($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([
            ['id' => 1, 'name' => 'Task 1'],
            ['id' => 2, 'name' => 'Task 2'],
        ], json_decode($response->getBody()->getContents(), true));
    }

    public function testPostالمهام()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO المهام (name) VALUES (:name)')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'New Task');

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Task']);

        $controller = new المهامController($this->pdo);
        $response = $controller->handle($this->request, $this->response);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Task created successfully'], json_decode($response->getBody()->getContents(), true));
    }

    public function testPutالمهام()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE المهام SET name = :name WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Updated Task');

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('PUT');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'Updated Task']);

        $controller = new المهامController($this->pdo);
        $response = $controller->handle($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Task updated successfully'], json_decode($response->getBody()->getContents(), true));
    }

    public function testDeleteالمهام()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM المهام WHERE id = :id')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('getMethod')
            ->willReturn('DELETE');

        $this->request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1]);

        $controller = new المهامController($this->pdo);
        $response = $controller->handle($this->request, $this->response);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Task deleted successfully'], json_decode($response->getBody()->getContents(), true));
    }
}