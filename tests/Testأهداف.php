<?php

namespace App\Tests\Controller;

use App\Controller\AهدافController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class Testأهداف extends TestCase
{
    private $controller;
    private $tokenStorage;
    private $userProvider;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->controller = new أهدافController($this->pdo, $this->tokenStorage, $this->userProvider);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM أهداف')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM أهداف WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->getById($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'أهداف جديدة'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO أهداف (name) VALUES (:name)')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->create($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'أهداف محدثة'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE أهداف SET name = :name WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->update($id, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM أهداف WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $response = $this->controller->delete($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'أهداف' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes and response types for each operation.