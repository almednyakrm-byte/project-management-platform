<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testالمواردالبشرية extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('GET');

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الموارد_البشرية')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $controller = new المواردالبشريةController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('POST');
        $request->method('getParsedBody')->willReturn(['name' => 'New Employee']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الموارد_البشرية (name) VALUES (:name)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with([':name' => 'New Employee']);

        $controller = new المواردالبشريةController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testPutRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('PUT');
        $request->method('getParsedBody')->willReturn(['id' => 1, 'name' => 'Updated Employee']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الموارد_البشرية SET name = :name WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with([':id' => 1, ':name' => 'Updated Employee']);

        $controller = new المواردالبشريةController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteRequest(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getMethod')->willReturn('DELETE');
        $request->method('getParsedBody')->willReturn(['id' => 1]);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الموارد_البشرية WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute')
            ->with([':id' => 1]);

        $controller = new المواردالبشريةController($this->pdo);
        $result = $controller->handleRequest($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }
}