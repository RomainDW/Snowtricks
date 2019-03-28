<?php
/**
 * Created by PhpStorm.
 * User: saysa
 * Date: 2019-03-07
 * Time: 15:45.
 */

namespace App\Tests\Action;

use App\Action\CreateTrickAction;
use App\Domain\Manager\TrickManager;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\CreateTrickResponder;
use App\Service\TrickService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

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
     * @var CreateTrickResponder
     */
    private $responder;

    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * @var TrickService
     */
    private $trickService;

    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');

        $this->createTrickFormHandler = $this->createMock(CreateTrickFormHandler::class);
        $this->security = $this->createMock(Security::class);
        $this->responder = $this->createMock(CreateTrickResponder::class);
        $this->trickManager = $this->createMock(TrickManager::class);
        $this->trickService = $this->createMock(TrickService::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(CreateTrickResponder::class, $this->responder);
        static::assertInstanceOf(Security::class, $this->security);
        static::assertInstanceOf(TrickService::class, $this->trickService);
        static::assertInstanceOf(TrickManager::class, $this->trickManager);
        static::assertInstanceOf(CreateTrickFormHandler::class, $this->createTrickFormHandler);

        $createTrickAction = new CreateTrickAction(
            $this->responder,
            $this->formFactory,
            $this->security,
            $this->trickManager,
            $this->trickService,
            $this->createTrickFormHandler
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
        // Comment mettre des arguments à la méthode handle ?
        $request = Request::create('/trick/add', 'POST');
        $this->createTrickFormHandler->method('handle')->willReturn(RedirectResponse::class);

        $createTrickAction = new CreateTrickAction(
            $this->responder,
            $this->formFactory,
            $this->security,
            $this->trickManager,
            $this->trickService,
            $this->createTrickFormHandler
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $createTrickAction($request)
        );


    }
}
