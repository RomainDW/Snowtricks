<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 8:13 PM.
 */

namespace App\Tests\Action;

use App\Action\RegistrationAction;
use App\Domain\Service\UserService;
use App\Handler\FormHandler\RegistrationFormHandler;
use App\Responder\RegistrationResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class RegistrationActionTest extends KernelTestCase
{
    private $formFactory;
    private $security;
    private $flashBag;
    private $userService;
    private $formHandler;
    private $urlGenerator;
    private $twig;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');
        $this->formFactory = static::$kernel->getContainer()->get('form.factory');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->userService = $this->createMock(UserService::class);
        $this->formHandler = $this->createMock(RegistrationFormHandler::class);
        $this->security = $this->createMock(Security::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(RegistrationFormHandler::class, $this->formHandler);
        static::assertInstanceOf(UserService::class, $this->userService);

        $registrationAction = new RegistrationAction(
            $this->formFactory,
            $this->security,
            $this->flashBag,
            $this->userService,
            $this->formHandler
        );

        static::assertInstanceOf(
            RegistrationAction::class,
            $registrationAction
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCorrectHandling()
    {
        $request = Request::create('/register', 'POST');
        $responder = new RegistrationResponder($this->twig, $this->urlGenerator);

        $registrationAction = new RegistrationAction(
            $this->formFactory,
            $this->security,
            $this->flashBag,
            $this->userService,
            $this->formHandler
        );

        $this->formHandler->method('handle')->willReturn(true);
        $this->userService->method('getUserId')->willreturn(0);

        $response = $registrationAction($request, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testWrongHandling()
    {
        $request = Request::create('/register', 'POST');
        $responder = new RegistrationResponder($this->twig, $this->urlGenerator);

        $registrationAction = new RegistrationAction(
            $this->formFactory,
            $this->security,
            $this->flashBag,
            $this->userService,
            $this->formHandler
        );

        $this->formHandler->method('handle')->willReturn(false);

        $response = $registrationAction($request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
