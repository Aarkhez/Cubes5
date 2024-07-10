<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\User;
use App\Utility\Session;
use \Core\View;

class LoginActionTest extends TestCase
{
    private $userController;

    protected function setUp(): void
    {
        $route_params = ['controller' => 'user', 'action' => 'login'];
        $this->userController = $this->getMockBuilder(User::class)
            ->setConstructorArgs([$route_params])
            ->onlyMethods(['login'])
            ->getMock();
    }

    public function testLoginActionSuccess()
    {
        $_POST['submit'] = true;
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'password123';

        // Mocking login method to return true
        $this->userController->expects($this->once())
            ->method('login')
            ->willReturn(true);

        // Expect header redirection
        $this->expectOutputString('');
        $this->expectException('PHPUnit\Framework\Error\Warning');
        $this->expectExceptionMessage('Cannot modify header information');

        $this->userController->loginAction();
    }

    public function testLoginActionFailure()
    {
        $_POST['submit'] = true;
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'wrongpassword';

        // Mocking login method to return false
        $this->userController->expects($this->once())
            ->method('login')
            ->willReturn(false);

        // Mocking Session::getFlashes to return a sample flash message
        Session::method('getFlashes')->willReturn(['danger' => 'Invalid credentials']);

        // Expect the view to be rendered with flash messages
        $this->expectOutputRegex('/Invalid credentials/');

        $this->userController->loginAction();
    }
}
