<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/26/19
 * Time: 7:47 PM.
 */

namespace App\Tests\Action;

use App\Action\ForgotPasswordAction;
use App\Domain\Service\UserService;
use App\Handler\FormHandler\ForgotPasswordFormHandler;
use App\Responder\ForgotPasswordResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ForgotPasswordActionTest extends KernelTestCase
{
    private $formFactory;
    private $userService;
    private $formHandler;
    private $twig;
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');
        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->formHandler = $this->createMock(ForgotPasswordFormHandler::class);
        $this->twig = $this->createMock(Environment::class);
        $this->userService = $this->createMock(UserService::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(ForgotPasswordFormHandler::class, $this->formHandler);
        static::assertInstanceOf(UserService::class, $this->userService);

        $forgotPasswordAction = new ForgotPasswordAction(
            $this->formFactory,
            $this->userService,
            $this->formHandler
        );

        static::assertInstanceOf(
            ForgotPasswordAction::class,
            $forgotPasswordAction
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCorrectHandling()
    {
        $request = Request::create('/trick/edit/test', 'POST');
        $responder = new ForgotPasswordResponder($this->twig, $this->urlGenerator);

        $forgotPasswordAction = new ForgotPasswordAction(
            $this->formFactory,
            $this->userService,
            $this->formHandler
        );

        $this->formHandler->method('handle')->willReturn(true);

        $response = $forgotPasswordAction($request, $responder);

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
        $request = Request::create('/trick/edit/test', 'POST');
        $responder = new ForgotPasswordResponder($this->twig, $this->urlGenerator);

        $forgotPasswordAction = new ForgotPasswordAction(
            $this->formFactory,
            $this->userService,
            $this->formHandler
        );

        $this->formHandler->method('handle')->willReturn(false);

        $response = $forgotPasswordAction($request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
