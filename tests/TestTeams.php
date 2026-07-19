<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeamsController;
use App\Repository\TeamsRepository;
use App\Service\TeamsService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PDO;

class TestTeams extends TestCase
{
    private $teamsController;
    private $teamsRepository;
    private $teamsService;
    private $pdo;

    protected function setUp(): void
    {
        $this->teamsRepository = $this->createMock(TeamsRepository::class);
        $this->teamsService = $this->createMock(TeamsService::class);
        $this->pdo = $this->createMock(PDO::class);

        $this->teamsController = new TeamsController($this->teamsRepository, $this->teamsService, $this->pdo);
    }

    public function testGetTeams()
    {
        $expectedResponse = ['teams' => ['team1', 'team2']];
        $this->teamsRepository->expects($this->once())
            ->method('getAllTeams')
            ->willReturn($expectedResponse);

        $response = $this->teamsController->getTeams();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreateTeam()
    {
        $teamData = ['name' => 'Team1', 'description' => 'This is team1'];
        $expectedResponse = ['message' => 'Team created successfully'];
        $this->teamsService->expects($this->once())
            ->method('createTeam')
            ->with($teamData)
            ->willReturn($expectedResponse);

        $response = $this->teamsController->createTeam($teamData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateTeam()
    {
        $teamId = 1;
        $teamData = ['name' => 'Team1', 'description' => 'This is team1'];
        $expectedResponse = ['message' => 'Team updated successfully'];
        $this->teamsService->expects($this->once())
            ->method('updateTeam')
            ->with($teamId, $teamData)
            ->willReturn($expectedResponse);

        $response = $this->teamsController->updateTeam($teamId, $teamData);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDeleteTeam()
    {
        $teamId = 1;
        $expectedResponse = ['message' => 'Team deleted successfully'];
        $this->teamsService->expects($this->once())
            ->method('deleteTeam')
            ->with($teamId)
            ->willReturn($expectedResponse);

        $response = $this->teamsController->deleteTeam($teamId);
        $this->assertEquals($expectedResponse, $response);
    }
}