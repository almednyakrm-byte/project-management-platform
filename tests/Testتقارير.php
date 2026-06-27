<?php

namespace App\Tests\Controller;

use App\Controller\ReportController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testتقارير extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->controller = new ReportController($this->pdoMock);
    }

    public function testGetReports(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM reports')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getReports($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateReport(): void
    {
        $data = ['title' => 'Test Report', 'description' => 'This is a test report'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO reports (title, description) VALUES (:title, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data]);
        $response = $this->controller->createReport($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateReport(): void
    {
        $data = ['title' => 'Updated Report', 'description' => 'This is an updated test report'];
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE reports SET title = :title, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data, 'id' => $id]);
        $response = $this->controller->updateReport($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteReport(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM reports WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => $id]);
        $response = $this->controller->deleteReport($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'تقارير' module. It uses mocked PDO statements to isolate the dependencies and make the tests more efficient. The tests cover the following scenarios:

- `testGetReports`: Verifies that the `getReports` method returns a successful response when retrieving reports from the database.
- `testCreateReport`: Verifies that the `createReport` method creates a new report in the database and returns a successful response.
- `testUpdateReport`: Verifies that the `updateReport` method updates an existing report in the database and returns a successful response.
- `testDeleteReport`: Verifies that the `deleteReport` method deletes a report from the database and returns a successful response.

Note that this is a basic implementation and you may need to modify it to fit your specific use case. Additionally, you should replace the mocked PDO statements with actual database interactions in a real-world scenario.