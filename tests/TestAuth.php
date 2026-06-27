<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authRepository->expects($this->once())
            ->method('verifyPassword')
            ->with($username, $password)
            ->willReturn(true);

        $result = $this->authService->login($username, $password);

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $result = $this->authService->login($username, $password);

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('createUser')
            ->with($username, $password)
            ->willReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $result = $this->authService->register($username, $password);

        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method returns `true` when the username and password are correct.
- `testLoginFailure`: Tests that the `login` method returns `false` when the username or password is incorrect.
- `testRegisterSuccess`: Tests that the `register` method returns `true` when the user is created successfully.
- `testRegisterFailure`: Tests that the `register` method returns `false` when the user already exists.

Note that this test file assumes that the `AuthService` and `AuthRepository` classes are implemented correctly and that the `User` class is defined.