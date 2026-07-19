<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالموظفين extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetAllالموظفين()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM الموظفين')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'John Doe'],
                ['id' => 2, 'name' => 'Jane Doe'],
            ]);

        $result = $this->pdo->query('SELECT * FROM الموظفين')->fetchAll();
        $this->assertCount(2, $result);
    }

    public function testGetالموظفينById()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM الموظفين WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'John Doe']);

        $stmt = $this->pdo->prepare('SELECT * FROM الموظفين WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateالموظف()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الموظفين (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'John Doe');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('INSERT INTO الموظفين (name) VALUES (:name)');
        $stmt->bindParam(':name', 'John Doe');
        $result = $stmt->execute();
        $this->assertTrue($result);
    }

    public function testUpdateالموظف()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الموظفين SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Jane Doe');

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('UPDATE الموظفين SET name = :name WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $stmt->bindParam(':name', 'Jane Doe');
        $result = $stmt->execute();
        $this->assertTrue($result);
    }

    public function testDeleteالموظف()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الموظفين WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->pdo->prepare('DELETE FROM الموظفين WHERE id = :id');
        $stmt->bindParam(':id', 1);
        $result = $stmt->execute();
        $this->assertTrue($result);
    }
}