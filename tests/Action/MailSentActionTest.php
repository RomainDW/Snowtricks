<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 7:31 PM.
 */

namespace App\Tests\Action;

use App\Action\MailSentAction;
use App\Domain\Entity\User;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class MailSentActionTest extends KernelTestCase
{
    private $flashBag;
    private $twig;
    private $urlGenerator;
    private $entityManager;

    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(FlashBagInterface::class, $this->flashBag);

        $mailSentAction = new MailSentAction(
            $this->flashBag
        );

        static::assertInstanceOf(
            MailSentAction::class,
            $mailSentAction
        );
    }

    public function testResponseWithAccess()
    {
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $user = new User();
        $user->updateRole(['ROLE_USER_NOT_VERIFIED']); // Has Access

        $mailSentAction = new MailSentAction(
            $this->flashBag
        );

        $response = $mailSentAction($user, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }

    public function testResponseWithoutAccess()
    {
        $responder = new TwigResponder($this->twig, $this->urlGenerator);
        $user = new User();
        $user->updateRole(['ROLE_USER']); // Do not have access

        $mailSentAction = new MailSentAction(
            $this->flashBag
        );

        $response = $mailSentAction($user, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }
}
