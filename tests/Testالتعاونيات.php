<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالتعاونيات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetالتعاونيات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM التعاونيات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'التعاونية 1']]);

        $result = $this->getالتعاونيات();
        $this->assertEquals([['id' => 1, 'name' => 'التعاونية 1']], $result);
    }

    public function testPostالتعاونيات()
    {
        $data = ['name' => 'التعاونية 2'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO التعاونيات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(2);

        $result = $this->postالتعاونيات($data);
        $this->assertEquals(2, $result);
    }

    public function testPutالتعاونيات()
    {
        $id = 1;
        $data = ['name' => 'التعاونية 1 updated'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE التعاونيات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putالتعاونيات($id, $data);
        $this->assertEquals(true, $result);
    }

    public function testDeleteالتعاونيات()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM التعاونيات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteالتعاونيات($id);
        $this->assertEquals(true, $result);
    }

    private function getالتعاونيات()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM التعاونيات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postالتعاونيات($data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO التعاونيات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function putالتعاونيات($id, $data)
    {
        $stmt = $this->pdo->prepare('UPDATE التعاونيات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteالتعاونيات($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM التعاونيات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}