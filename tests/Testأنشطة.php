<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AnshataController;
use App\Repository\AnshataRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAnshata extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(AnshataRepository::class);
        $this->controller = new AnshataController($this->repository);
    }

    public function testGetAnshatas()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('getAll')
            ->willReturn($expectedResponse);
        $response = $this->controller->getAnshatas();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPostAnshata()
    {
        $data = ['name' => 'Test Anshata'];
        $expectedResponse = ['message' => 'Anshata created successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->willReturn(1);
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse);
        $response = $this->controller->postAnshata($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPutAnshata()
    {
        $id = 1;
        $data = ['name' => 'Updated Anshata'];
        $expectedResponse = ['message' => 'Anshata updated successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->willReturn(1);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse);
        $response = $this->controller->putAnshata($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteAnshata()
    {
        $id = 1;
        $expectedResponse = ['message' => 'Anshata deleted successfully'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('exec')
            ->willReturn(1);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($expectedResponse);
        $response = $this->controller->deleteAnshata($id);
        $this->assertEquals($expectedResponse, $response);
    }
}