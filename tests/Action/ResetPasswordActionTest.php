<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 8:51 PM.
 */

namespace App\Tests\Action;

use App\Action\ResetPasswordAction;
use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use App\Handler\FormHandler\ResetPasswordFormHandler;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ResetPasswordActionTest extends KernelTestCase
{
    private $formFactory;
    private $flashBag;
    private $security;
    private $userService;
    private $formHandler;
    private $twig;
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');
        $this->formFactory = static::$kernel->getContainer()->get('form.factory');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->userService = $this->createMock(UserService::class);
        $this->formHandler = $this->createMock(ResetPasswordFormHandler::class);
        $this->security = $this->createMock(Security::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(UserService::class, $this->userService);
        static::assertInstanceOf(FlashBagInterface::class, $this->flashBag);
        static::assertInstanceOf(ResetPasswordFormHandler::class, $this->formHandler);
        static::assertInstanceOf(Security::class, $this->security);

        $resetPasswordAction = new ResetPasswordAction(
            $this->formFactory,
            $this->flashBag,
            $this->security,
            $this->formHandler,
            $this->userService
        );

        static::assertInstanceOf(
            ResetPasswordAction::class,
            $resetPasswordAction
        );
    }

    public function testCorrectHandling()
    {
        $request = Request::create('/reset-password/{vkey}', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $user = new User();

        $resetPasswordAction = new ResetPasswordAction(
            $this->formFactory,
            $this->flashBag,
            $this->security,
            $this->formHandler,
            $this->userService
        );

        $this->formHandler->method('handle')->willReturn(true);

        $response = $resetPasswordAction($user, $request, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    public function testWrongHandling()
    {
        $request = Request::create('/reset-password/{vkey}', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $user = new User();

        $resetPasswordAction = new ResetPasswordAction(
            $this->formFactory,
            $this->flashBag,
            $this->security,
            $this->formHandler,
            $this->userService
        );

        $this->formHandler->method('handle')->willReturn(false);

        $response = $resetPasswordAction($user, $request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
