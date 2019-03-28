<?php
/**
 * Created by PhpStorm.
 * User: saysa
 * Date: 2019-03-07
 * Time: 15:45.
 */

namespace App\Tests\Action;

use App\Action\CreateTrickAction;
use App\Domain\Service\TrickService;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\CreateTrickResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class CreateTrickActionTest extends KernelTestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var CreateTrickFormHandler
     */
    private $createTrickFormHandler;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var TrickService
     */
    private $trickService;

    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');
        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->createTrickFormHandler = $this->createMock(CreateTrickFormHandler::class);
        $this->security = $this->createMock(Security::class);
        $this->twig = $this->createMock(Environment::class);
        $this->trickService = $this->createMock(TrickService::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(Security::class, $this->security);
        static::assertInstanceOf(CreateTrickFormHandler::class, $this->createTrickFormHandler);
        static::assertInstanceOf(TrickService::class, $this->trickService);

        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->security,
            $this->createTrickFormHandler,
            $this->trickService
        );

        static::assertInstanceOf(
            CreateTrickAction::class,
            $createTrickAction
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCorrectHandling()
    {
        $request = Request::create('/trick/add', 'POST');
        $responder = new CreateTrickResponder($this->twig, $this->urlGenerator);

        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->security,
            $this->createTrickFormHandler,
            $this->trickService
        );

        $this->createTrickFormHandler->method('handle')->willReturn(true);
        $this->trickService->method('getSlug')->willReturn('test');

        $response = $createTrickAction($request, $responder);

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
        $request = Request::create('/trick/add', 'GET');
        $responder = new CreateTrickResponder($this->twig, $this->urlGenerator);

        $createTrickAction = new CreateTrickAction(
            $this->formFactory,
            $this->security,
            $this->createTrickFormHandler,
            $this->trickService
        );

        $this->createTrickFormHandler->method('handle')->willReturn(false);

        $response = $createTrickAction($request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
