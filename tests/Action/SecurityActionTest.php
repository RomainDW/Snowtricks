<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 9:00 PM.
 */

namespace App\Tests\Action;

use App\Action\SecurityAction;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

class SecurityActionTest extends KernelTestCase
{
    private $flashBag;
    private $security;
    private $twig;
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->security = $this->createMock(Security::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(FlashBagInterface::class, $this->flashBag);
        static::assertInstanceOf(Security::class, $this->security);

        $securityAction = new SecurityAction(
            $this->security,
            $this->flashBag
        );

        static::assertInstanceOf(
            SecurityAction::class,
            $securityAction
        );
    }

    public function testResponseAlreadyConnected()
    {
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $authenticationUtils = $this->createMock(AuthenticationUtils::class);

        $securityAction = new SecurityAction(
            $this->security,
            $this->flashBag
        );

        $this->security->method('isGranted')->willreturn(true);

        $response = $securityAction($authenticationUtils, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    public function testResponseNotConnected()
    {
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $authenticationUtils = $this->createMock(AuthenticationUtils::class);

        $securityAction = new SecurityAction(
            $this->security,
            $this->flashBag
        );

        $this->security->method('isGranted')->willreturn(false);

        $response = $securityAction($authenticationUtils, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
