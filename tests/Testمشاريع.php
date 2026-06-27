<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

class Testمشاريع extends TestCase
{
    private $mockPDO;
    private $request;
    private $response;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->request = new ServerRequest('GET', '/');
        $this->response = new Response();
    }

    public function testGetمشاريع()
    {
        $this->mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مشاريع')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new مشاريعController($this->mockPDO);
        $response = $controller->getمشاريع($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostمشاريع()
    {
        $data = ['name' => 'Test Project', 'description' => 'Test project description'];
        $this->request = $this->request->withParsedBody($data);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مشاريع (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new مشاريعController($this->mockPDO);
        $response = $controller->postمشاريع($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutمشاريع()
    {
        $data = ['name' => 'Updated Test Project', 'description' => 'Updated test project description'];
        $this->request = $this->request->withParsedBody($data);
        $this->request = $this->request->withAttribute('id', 1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مشاريع SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new مشاريعController($this->mockPDO);
        $response = $controller->putمشاريع($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteمشاريع()
    {
        $this->request = $this->request->withAttribute('id', 1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مشاريع WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $controller = new مشاريعController($this->mockPDO);
        $response = $controller->deleteمشاريع($this->request, $this->response);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}