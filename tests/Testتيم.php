<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeamController;
use App\Repository\TeamRepository;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testتيم extends TestCase
{
    private $teamController;
    private $teamRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->teamController = new TeamController($this->teamRepository, $this->entityManager);
    }

    public function testGetTeams(): void
    {
        $teams = [new Team(), new Team()];
        $this->teamRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($teams);

        $response = $this->teamController->getTeams();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($teams), $response->getContent());
    }

    public function testGetTeam(): void
    {
        $team = new Team();
        $team->setId(1);
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $response = $this->teamController->getTeam(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testGetTeamNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->teamController->getTeam(1);
    }

    public function testCreateTeam(): void
    {
        $team = new Team();
        $team->setName('Team Name');
        $this->teamRepository->expects($this->once())
            ->method('save')
            ->with($team);

        $request = new Request();
        $request->request->set('name', 'Team Name');
        $response = $this->teamController->createTeam($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeam(): void
    {
        $team = new Team();
        $team->setId(1);
        $team->setName('Team Name');
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);
        $this->teamRepository->expects($this->once())
            ->method('save')
            ->with($team);

        $request = new Request();
        $request->request->set('name', 'Team Name');
        $response = $this->teamController->updateTeam(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeamNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Team Name');
        $this->teamController->updateTeam(1, $request);
    }

    public function testDeleteTeam(): void
    {
        $team = new Team();
        $team->setId(1);
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);
        $this->teamRepository->expects($this->once())
            ->method('remove')
            ->with($team);

        $response = $this->teamController->deleteTeam(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteTeamNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->teamController->deleteTeam(1);
    }
}