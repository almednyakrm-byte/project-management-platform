<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\AfradController;
use App\Repository\AfradRepository;
use App\Entity\Afrad;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestAfrad extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AfradRepository::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->controller = new AfradController($this->repository, $this->pdo);
    }

    public function testGetAfrad(): void
    {
        $id = 1;
        $afrad = new Afrad();
        $afrad->setId($id);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($afrad);

        $request = new Request();
        $response = $this->controller->getAfrad($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($afrad, $response->getContent());
    }

    public function testGetAfradNotFound(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getAfrad($request, $id);
    }

    public function testCreateAfrad(): void
    {
        $afrad = new Afrad();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($afrad);

        $request = new Request([], [], ['afrad' => ['name' => 'John Doe']]);
        $response = $this->controller->createAfrad($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($afrad, $response->getContent());
    }

    public function testUpdateAfrad(): void
    {
        $id = 1;
        $afrad = new Afrad();
        $afrad->setId($id);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($afrad);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($afrad);

        $request = new Request([], [], ['afrad' => ['name' => 'John Doe']]);
        $response = $this->controller->updateAfrad($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($afrad, $response->getContent());
    }

    public function testUpdateAfradNotFound(): void
    {
        $id = 1;
        $afrad = new Afrad();
        $afrad->setId($id);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $request = new Request([], [], ['afrad' => ['name' => 'John Doe']]);
        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateAfrad($request, $id);
    }

    public function testDeleteAfrad(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Afrad());

        $request = new Request();
        $response = $this->controller->deleteAfrad($request, $id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteAfradNotFound(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteAfrad($request, $id);
    }
}


This test file covers the following scenarios:

- `testGetAfrad`: Verifies that the `getAfrad` method returns the correct Afrad object when the ID exists.
- `testGetAfradNotFound`: Verifies that the `getAfrad` method throws a `NotFoundHttpException` when the ID does not exist.
- `testCreateAfrad`: Verifies that the `createAfrad` method creates a new Afrad object and saves it to the repository.
- `testUpdateAfrad`: Verifies that the `updateAfrad` method updates an existing Afrad object and saves it to the repository.
- `testUpdateAfradNotFound`: Verifies that the `updateAfrad` method throws a `NotFoundHttpException` when the ID does not exist.
- `testDeleteAfrad`: Verifies that the `deleteAfrad` method deletes an existing Afrad object.
- `testDeleteAfradNotFound`: Verifies that the `deleteAfrad` method throws a `NotFoundHttpException` when the ID does not exist.