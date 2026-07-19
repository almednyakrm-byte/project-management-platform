<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testمهمات extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetمهمات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مهمات')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مهمة 1'],
                ['id' => 2, 'name' => 'مهمة 2'],
            ]);

        $مهماتController = new مهماتController($this->pdo);
        $result = $مهماتController->getمهمات($request, $response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testPostمهمات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مهمة جديدة']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مهمات (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'مهمة جديدة');

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $مهماتController = new مهماتController($this->pdo);
        $result = $مهماتController->postمهمات($request, $response);

        $this->assertTrue($result);
    }

    public function testPutمهمات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مهمة محدثة']);

        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مهمات SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'مهمة محدثة');

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $مهماتController = new مهماتController($this->pdo);
        $result = $مهماتController->putمهمات($request, $response, ['id' => 1]);

        $this->assertTrue($result);
    }

    public function testDeleteمهمات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مهمات WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $مهماتController = new مهماتController($this->pdo);
        $result = $مهماتController->deleteمهمات($request, $response, ['id' => 1]);

        $this->assertTrue($result);
    }
}