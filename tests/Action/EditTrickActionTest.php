<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/25/19
 * Time: 7:44 PM.
 */

namespace App\Tests\Action;

use App\Action\EditTrickAction;
use App\Domain\Entity\Trick;
use App\Domain\Service\TrickService;
use App\Handler\FormHandler\EditTrickFormHandler;
use App\Responder\TwigResponder;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class EditTrickActionTest extends KernelTestCase
{
    private $formFactory;
    private $formHandler;
    private $twig;
    private $urlGenerator;
    private $entityManager;

    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->formFactory = static::$kernel->getContainer()->get('form.factory');
        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->formHandler = $this->createMock(EditTrickFormHandler::class);
        $this->twig = $this->createMock(Environment::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(FormFactoryInterface::class, $this->formFactory);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);
        static::assertInstanceOf(EditTrickFormHandler::class, $this->formHandler);

        $createTrickAction = new EditTrickAction(
            $this->formFactory,
            $this->formHandler
        );

        static::assertInstanceOf(
            EditTrickAction::class,
            $createTrickAction
        );
    }

    /**
     * @throws Exception
     */
    public function testCorrectHandling()
    {
        $request = Request::create('/trick/edit/test', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $editTrickAction = new EditTrickAction(
            $this->formFactory,
            $this->formHandler
        );

        $trick = $this->entityManager->getRepository(Trick::class)->findOneBy(['title' => 'test']);

        $this->formHandler->method('handle')->willReturn(true);

        $response = $editTrickAction($trick, $request, $responder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    /**
     * @throws Exception
     */
    public function testWrongHandling()
    {
        $request = Request::create('/trick/edit/test', 'POST');
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $editTrickAction = new EditTrickAction(
            $this->formFactory,
            $this->formHandler
        );

        $trick = $this->entityManager->getRepository(Trick::class)->findOneBy(['title' => 'test']);

        $this->formHandler->method('handle')->willReturn(false);

        $response = $editTrickAction($trick, $request, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
