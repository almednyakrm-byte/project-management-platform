<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testأفراد extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->stmt);
    }

    public function testGetأفراد(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'أفراد 1'],
                ['id' => 2, 'name' => 'أفراد 2'],
            ]);

        $أفرادController = new أفرادController($this->pdo);
        $result = $أفرادController->getأفراد($request, $response);

        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostأفراد(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')
            ->willReturn(['name' => 'أفراد 3']);

        $response = $this->createMock(ResponseInterface::class);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'أفراد 3']);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $أفرادController = new أفرادController($this->pdo);
        $result = $أفرادController->postأفراد($request, $response);

        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testPutأفراد(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getParsedBody')
            ->willReturn(['name' => 'أفراد 1 updated']);

        $response = $this->createMock(ResponseInterface::class);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(['name' => 'أفراد 1 updated', 'id' => 1]);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $أفرادController = new أفرادController($this->pdo);
        $result = $أفرادController->putأفراد($request, $response, ['id' => 1]);

        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteأفراد(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $أفرادController = new أفرادController($this->pdo);
        $result = $أفرادController->deleteأفراد($request, $response, ['id' => 1]);

        $this->assertEquals(204, $result->getStatusCode());
    }
}