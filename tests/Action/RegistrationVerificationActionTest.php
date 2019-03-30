<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 8:41 PM.
 */

namespace App\Tests\Action;

use App\Action\RegistrationVerificationAction;
use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

class RegistrationVerificationActionTest extends KernelTestCase
{
    private $flashBag;
    private $tokenStorage;
    private $session;
    private $userService;
    private $urlGenerator;
    private $twig;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->userService = $this->createMock(UserService::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(UserService::class, $this->userService);
        static::assertInstanceOf(TokenStorageInterface::class, $this->tokenStorage);
        static::assertInstanceOf(FlashBagInterface::class, $this->flashBag);
        static::assertInstanceOf(SessionInterface::class, $this->session);

        $registrationVerificationAction = new RegistrationVerificationAction(
            $this->flashBag,
            $this->tokenStorage,
            $this->session,
            $this->userService
        );

        static::assertInstanceOf(
            RegistrationVerificationAction::class,
            $registrationVerificationAction
        );
    }

    public function testCorrectVerification()
    {
        $user = new User();
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $registrationVerificationAction = new RegistrationVerificationAction(
            $this->flashBag,
            $this->tokenStorage,
            $this->session,
            $this->userService
        );

        $this->userService->method('verification')->willreturn(true);

        $response = $registrationVerificationAction($user, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }

    public function testWrongVerification()
    {
        $user = new User();
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $registrationVerificationAction = new RegistrationVerificationAction(
            $this->flashBag,
            $this->tokenStorage,
            $this->session,
            $this->userService
        );

        $this->userService->method('verification')->willreturn(false);

        $response = $registrationVerificationAction($user, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }
}
