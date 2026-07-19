<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Tasks;

class TestTasks extends TestCase
{
    private $tasks;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(\PDO::class);
        $this->tasks = new Tasks($this->pdo);
    }

    public function testGetAllTasks()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([]));

        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'title' => 'Task 1'],
                ['id' => 2, 'title' => 'Task 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM tasks'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->tasks->getAllTasks($request, $response);
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testGetTaskById()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'title' => 'Task 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM tasks WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->tasks->getTaskById($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(1, $result['id']);
    }

    public function testCreateTask()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['Task 1']));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO tasks (title) VALUES (?)'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['title' => 'Task 1']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->tasks->createTask($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(201, $result['status']);
    }

    public function testUpdateTask()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['Task 1', 1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE tasks SET title = ? WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['title' => 'Task 1']);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->tasks->updateTask($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(200, $result['status']);
    }

    public function testDeleteTask()
    {
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([1]));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM tasks WHERE id = ?'))
            ->willReturn($stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->tasks->deleteTask($request, $response);
        $this->assertIsArray($result);
        $this->assertEquals(204, $result['status']);
    }
}