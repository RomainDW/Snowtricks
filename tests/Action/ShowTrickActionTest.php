<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 9:09 PM.
 */

namespace App\Tests\Action;

use App\Action\ShowTrickAction;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Handler\FormHandler\CommentFormHandler;
use App\Repository\CommentRepository;
use App\Responder\ShowTrickResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ShowTrickActionTest extends KernelTestCase
{
    private $security;
    private $commentRepository;
    private $formFactory;
    private $flashBag;
    private $formHandler;
    private $twig;
    private $urlGenerator;
    private $entityManager;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');
        $this->formFactory = static::$kernel->getContainer()->get('form.factory');
        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->twig = $this->createMock(Environment::class);
        $this->flashBag = $this->createMock(FlashBagInterface::class);
        $this->formHandler = $this->createMock(CommentFormHandler::class);
        $this->security = $this->createMock(Security::class);
        $this->commentRepository = $this->createMock(CommentRepository::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(FlashBagInterface::class, $this->flashBag);
        static::assertInstanceOf(CommentFormHandler::class, $this->formHandler);
        static::assertInstanceOf(Security::class, $this->security);
        static::assertInstanceOf(CommentRepository::class, $this->commentRepository);

        $showTrickAction = new ShowTrickAction(
            $this->security,
            $this->commentRepository,
            $this->formFactory,
            $this->flashBag,
            $this->formHandler
        );

        static::assertInstanceOf(
            ShowTrickAction::class,
            $showTrickAction
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testCorrectHandling()
    {
        $tricks = $this->entityManager->getRepository(Trick::class)->findAll();
        $trick = $tricks[0];
        $request = Request::create('/trick/show/test', 'POST');
        $responder = new ShowTrickResponder($this->twig, $this->urlGenerator);

        $this->security->method('getUser')->willreturn(new User());
        $this->security->method('isGranted')->willreturn(true);
        $this->formHandler->method('handle')->willreturn(true);

        $showTrickAction = new ShowTrickAction(
            $this->security,
            $this->commentRepository,
            $this->formFactory,
            $this->flashBag,
            $this->formHandler
        );

        $response = $showTrickAction($trick, $request, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testWrongHandling()
    {
        $tricks = $this->entityManager->getRepository(Trick::class)->findAll();
        $trick = $tricks[0];
        $request = Request::create('/trick/show/test', 'POST');
        $responder = new ShowTrickResponder($this->twig, $this->urlGenerator);

        // If any of these return false => Wrong handling
        $this->security->method('getUser')->willreturn(false);
        $this->security->method('isGranted')->willreturn(true);
        $this->formHandler->method('handle')->willreturn(true);

        $showTrickAction = new ShowTrickAction(
            $this->security,
            $this->commentRepository,
            $this->formFactory,
            $this->flashBag,
            $this->formHandler
        );

        $response = $showTrickAction($trick, $request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
