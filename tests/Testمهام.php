<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TasksController;
use App\Repository\TasksRepository;
use App\Service\TasksService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestTasksController extends TestCase
{
    private $tasksController;
    private $tasksRepository;
    private $tasksService;
    private $pdo;

    protected function setUp(): void
    {
        $this->tasksRepository = $this->createMock(TasksRepository::class);
        $this->tasksService = $this->createMock(TasksService::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->tasksController = new TasksController($this->tasksRepository, $this->tasksService, $this->pdo);
    }

    public function testGetTasks()
    {
        $tasks = ['task1', 'task2', 'task3'];
        $this->tasksRepository->expects($this->once())
            ->method('getAllTasks')
            ->willReturn($tasks);

        $response = $this->tasksController->getTasks();
        $this->assertEquals($tasks, $response);
    }

    public function testCreateTask()
    {
        $task = 'new task';
        $this->tasksService->expects($this->once())
            ->method('createTask')
            ->with($task)
            ->willReturn(true);

        $response = $this->tasksController->createTask($task);
        $this->assertTrue($response);
    }

    public function testUpdateTask()
    {
        $taskId = 1;
        $task = 'updated task';
        $this->tasksService->expects($this->once())
            ->method('updateTask')
            ->with($taskId, $task)
            ->willReturn(true);

        $response = $this->tasksController->updateTask($taskId, $task);
        $this->assertTrue($response);
    }

    public function testDeleteTask()
    {
        $taskId = 1;
        $this->tasksService->expects($this->once())
            ->method('deleteTask')
            ->with($taskId)
            ->willReturn(true);

        $response = $this->tasksController->deleteTask($taskId);
        $this->assertTrue($response);
    }
}



// TasksController.php

namespace App\Controller;

use App\Repository\TasksRepository;
use App\Service\TasksService;
use PDO;

class TasksController
{
    private $tasksRepository;
    private $tasksService;
    private $pdo;

    public function __construct(TasksRepository $tasksRepository, TasksService $tasksService, PDO $pdo)
    {
        $this->tasksRepository = $tasksRepository;
        $this->tasksService = $tasksService;
        $this->pdo = $pdo;
    }

    public function getTasks()
    {
        return $this->tasksRepository->getAllTasks();
    }

    public function createTask($task)
    {
        return $this->tasksService->createTask($task);
    }

    public function updateTask($taskId, $task)
    {
        return $this->tasksService->updateTask($taskId, $task);
    }

    public function deleteTask($taskId)
    {
        return $this->tasksService->deleteTask($taskId);
    }
}