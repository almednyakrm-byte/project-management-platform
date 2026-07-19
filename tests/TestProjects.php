<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Projects;

class TestProjects extends TestCase
{
    private $projects;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->projects = new Projects();
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->projects->setPdo($this->pdo);
    }

    public function testGetProjects()
    {
        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM projects')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->projects->getProjects($this->request, $this->response);
    }

    public function testGetProjectById()
    {
        $projectId = 1;
        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($projectId);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM projects WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->projects->getProjectById($this->request, $this->response);
    }

    public function testCreateProject()
    {
        $projectData = [
            'name' => 'Test Project',
            'description' => 'This is a test project',
        ];

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($projectData);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO projects (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->projects->createProject($this->request, $this->response);
    }

    public function testUpdateProject()
    {
        $projectId = 1;
        $projectData = [
            'name' => 'Updated Test Project',
            'description' => 'This is an updated test project',
        ];

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($projectId);

        $this->request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($projectData);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE projects SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->projects->updateProject($this->request, $this->response);
    }

    public function testDeleteProject()
    {
        $projectId = 1;

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($projectId);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM projects WHERE id = :id')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->projects->deleteProject($this->request, $this->response);
    }
}