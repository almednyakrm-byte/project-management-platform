<?php

namespace App\Tests\Controller;

use App\Controller\TaawoniyaController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TestTaawoniya extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new TaawoniyaController($this->router, $this->tokenStorage, $this->pdo);
    }

    public function testGetTaawoniya()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM taawoniya')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getTaawoniya($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostTaawoniya()
    {
        $data = ['name' => 'Taawoniya 1', 'description' => 'This is a taawoniya'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO taawoniya (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data]);
        $response = $this->controller->postTaawoniya($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutTaawoniya()
    {
        $data = ['name' => 'Taawoniya 1', 'description' => 'This is a taawoniya'];
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE taawoniya SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data, 'id' => $id]);
        $response = $this->controller->putTaawoniya($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteTaawoniya()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM taawoniya WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => $id]);
        $response = $this->controller->deleteTaawoniya($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}