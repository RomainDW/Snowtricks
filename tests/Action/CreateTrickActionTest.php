<?php
/**
 * Created by PhpStorm.
 * User: saysa
 * Date: 2019-03-07
 * Time: 15:45.
 */

namespace App\Tests\Action;

use App\Action\CreateTrickAction;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\CreateTrickResponder;
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
    private $createTrickFormHanfler;

    /**
     * @var CreateTrickResponder
     */
    private $responder;

    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');

        $this->createTrickFormHanfler = $this->createMock(CreateTrickFormHandler::class);
        $this->responder = $this->createMock(CreateTrickResponder::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);

        $createTrickAction = new CreateTrickAction(
            $this->responder,
            $this->formFactory,
            $this->security
        );

        static::assertInstanceOf(
            CreateTrickAction::class,
            $createTrickAction
        );
    }

    public function testCorrecthandling()
    {
        $request = Request::create('/trick/add', 'POST');
        $this->createTrickFormHanfler->method('handle')->willReturn(true);

        $createTrickAction = new CreateTrickAction(
            $this->responder,
            $this->formFactory,
            $this->security
        );

        static::assertInstanceOf(
            RedirectResponse::class,
            $createTrickAction($request, $this->createTrickFormHanfler);
        );


    }
}
