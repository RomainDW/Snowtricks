<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/21/19
 * Time: 10:40 AM.
 */

namespace App\Tests\Action;

use App\Action\AccountAction;
use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
use App\Domain\Service\UserService;
use App\Handler\FormHandler\AccountFormHandler;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class AccountActionTest extends KernelTestCase
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
     * @var AccountFormHandler
     */
    private $accountFormHandler;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');
        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->accountFormHandler = $this->createMock(AccountFormHandler::class);
        $this->security = $this->createMock(Security::class);
        $this->userService = $this->createMock(UserService::class);
        $this->twig = $this->createMock(Environment::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(Security::class, $this->security);
        static::assertInstanceOf(AccountFormHandler::class, $this->accountFormHandler);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);

        $accountAction = new AccountAction(
            $this->formFactory,
            $this->security,
            $this->accountFormHandler,
            $this->userService
        );

        static::assertInstanceOf(
            AccountAction::class,
            $accountAction
        );
    }

    /**
     * @throws ValidationException
     */
    public function testCorrectHandling()
    {
        $request = Request::create('/my-account', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $accountAction = new AccountAction(
            $this->formFactory,
            $this->security,
            $this->accountFormHandler,
            $this->userService
        );

        $user = $this->createMock(User::class);
        $this->security->method('getUser')->willReturn($user);

        $this->accountFormHandler->method('handle')->willReturn(true);

        $response = $accountAction($request, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    /**
     * @throws ValidationException
     */
    public function testWrongHandling()
    {
        $request = Request::create('/my-account', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $accountAction = new AccountAction(
            $this->formFactory,
            $this->security,
            $this->accountFormHandler,
            $this->userService
        );

        $user = $this->createMock(User::class);
        $this->security->method('getUser')->willReturn($user);

        $this->accountFormHandler->method('handle')->willReturn(false);

        $response = $accountAction($request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
