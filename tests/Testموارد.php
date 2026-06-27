<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\مواردController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testموارد extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->controller = new موادController($this->pdoMock);
    }

    public function testGetAll(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials')
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $materialId = 1;
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials WHERE id = ?', [$materialId])
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $response = $this->controller->getOne($request, $materialId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOneNotFound(): void
    {
        $materialId = 1;
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials WHERE id = ?', [$materialId])
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne($request, $materialId);
    }

    public function testCreate(): void
    {
        $materialData = ['name' => 'Material 1', 'description' => 'This is a material'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO materials (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $request = new Request([], [], ['material' => $materialData]);
        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $materialId = 1;
        $materialData = ['name' => 'Material 1', 'description' => 'This is a material'];
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials WHERE id = ?', [$materialId])
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE materials SET name = :name, description = :description WHERE id = ?', [$materialId])
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request([], [], ['material' => $materialData]);
        $response = $this->controller->update($request, $materialId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $materialId = 1;
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM materials WHERE id = ?', [$materialId])
            ->willReturn($this->createMock('PDOStatement'));
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM materials WHERE id = ?', [$materialId])
            ->willReturn($this->createMock('PDOStatement'));

        $request = new Request();
        $response = $this->controller->delete($request, $materialId);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'موارد' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

*   `testGetAll`: Verifies that the `getAll` method returns a successful response when retrieving all materials.
*   `testGetOne`: Verifies that the `getOne` method returns a successful response when retrieving a single material by ID.
*   `testGetOneNotFound`: Verifies that the `getOne` method throws a `NotFoundHttpException` when trying to retrieve a non-existent material.
*   `testCreate`: Verifies that the `create` method returns a successful response when creating a new material.
*   `testUpdate`: Verifies that the `update` method returns a successful response when updating an existing material.
*   `testDelete`: Verifies that the `delete` method returns a successful response when deleting a material.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should ensure that the mocked PDO statements are properly configured to simulate the expected database behavior.