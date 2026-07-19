<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestReports extends TestCase
{
    private $pdo;
    private $reports;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->reports = new Reports($this->pdo);
    }

    public function testGetReports()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'Report 1']]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM reports WHERE id = :id'))
            ->willReturn($stmt);

        $result = $this->reports->getReports(1);
        $this->assertEquals([['id' => 1, 'name' => 'Report 1']], $result);
    }

    public function testCreateReport()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['name' => 'New Report']));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO reports (name) VALUES (:name)'))
            ->willReturn($stmt);

        $result = $this->reports->createReport('New Report');
        $this->assertEquals(1, $result);
    }

    public function testUpdateReport()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1, 'name' => 'Updated Report']));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE reports SET name = :name WHERE id = :id'))
            ->willReturn($stmt);

        $result = $this->reports->updateReport(1, 'Updated Report');
        $this->assertEquals(1, $result);
    }

    public function testDeleteReport()
    {
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM reports WHERE id = :id'))
            ->willReturn($stmt);

        $result = $this->reports->deleteReport(1);
        $this->assertEquals(1, $result);
    }
}

class Reports
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getReports($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM reports WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    }

    public function createReport($name)
    {
        $stmt = $this->pdo->prepare('INSERT INTO reports (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return $stmt->rowCount();
    }

    public function updateReport($id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE reports SET name = :name WHERE id = :id');
        $stmt->execute(['id' => $id, 'name' => $name]);
        return $stmt->rowCount();
    }

    public function deleteReport($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM reports WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}