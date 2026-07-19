<?php

namespace App\Tests\Unit\Auth;

use PHPUnit\Framework\TestCase;
use App\Auth\Auth;
use App\Auth\User;
use App\Auth\Repository\UserRepository;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TestAuth extends TestCase
{
    /**
     * @var LegacyMockInterface|UserRepository
     */
    protected $userRepository;

    /**
     * @var LegacyMockInterface|Session
     */
    protected $session;

    protected function setUp(): void
    {
        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->session = Mockery::mock(Session::class);
    }

    public function testLoginSuccess()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('testuser');
        $user->setPassword('testpassword');

        $this->userRepository->shouldReceive('findUserByUsername')->with('testuser')->andReturn($user);
        $this->userRepository->shouldReceive('validatePassword')->with('testpassword', $user->getPassword())->andReturn(true);

        $auth = new Auth($this->userRepository, $this->session);
        $auth->login('testuser', 'testpassword');

        $this->assertTrue($this->session->has('logged_in'));
        $this->assertEquals($user->getId(), $this->session->get('logged_in'));
    }

    public function testLoginFailure()
    {
        $this->userRepository->shouldReceive('findUserByUsername')->with('testuser')->andReturn(null);

        $auth = new Auth($this->userRepository, $this->session);
        $auth->login('testuser', 'testpassword');

        $this->assertFalse($this->session->has('logged_in'));
    }

    public function testRegisterSuccess()
    {
        $user = new User();
        $user->setId(1);
        $user->setUsername('testuser');
        $user->setPassword('testpassword');

        $this->userRepository->shouldReceive('createUser')->with($user)->andReturn($user);

        $auth = new Auth($this->userRepository, $this->session);
        $auth->register($user);

        $this->assertTrue($this->session->has('registered'));
        $this->assertEquals($user->getId(), $this->session->get('registered'));
    }

    public function testRegisterFailure()
    {
        $this->userRepository->shouldReceive('createUser')->with(Mockery::any())->andReturn(null);

        $auth = new Auth($this->userRepository, $this->session);
        $auth->register(new User());

        $this->assertFalse($this->session->has('registered'));
    }
}


This test file includes four test methods:

- `testLoginSuccess`: Tests that a user can successfully log in with the correct credentials.
- `testLoginFailure`: Tests that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Tests that a user can successfully register with the correct credentials.
- `testRegisterFailure`: Tests that a user cannot register with incorrect credentials.

Each test method uses Mockery to mock the `UserRepository` and `Session` objects, allowing us to isolate the `Auth` class and test its behavior in isolation. The `shouldReceive` method is used to specify the expected behavior of the mocked objects, and the `andReturn` method is used to specify the return values for the mocked methods. The `assertTrue` and `assertEquals` assertions are used to verify that the `Auth` class behaves as expected.