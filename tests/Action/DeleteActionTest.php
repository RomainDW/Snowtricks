<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/25/19
 * Time: 7:15 PM.
 */

namespace App\Tests\Action;

use App\Action\DeleteTrickAction;
use App\Domain\Entity\Trick;
use App\Domain\Service\TrickService;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class DeleteActionTest extends KernelTestCase
{
    private $trickService;
    private $twig;
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->urlGenerator = static::$kernel->getContainer()->get('router');

        $this->trickService = $this->createMock(TrickService::class);
        $this->twig = $this->createMock(Environment::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(TrickService::class, $this->trickService);
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(UrlGeneratorInterface::class, $this->urlGenerator);

        $deleteTrickAction = new DeleteTrickAction(
            $this->trickService
        );

        static::assertInstanceOf(
            DeleteTrickAction::class,
            $deleteTrickAction
        );
    }

    public function testResponse()
    {
        $deleteTrickAction = new DeleteTrickAction(
            $this->trickService
        );

        $trick = $this->createMock(Trick::class);
        $deleteTrickResponder = new TwigResponder($this->twig, $this->urlGenerator);

        $deleteTrickAction($trick, $deleteTrickResponder);

        static::assertInstanceOf(
            RedirectResponse::class,
            $deleteTrickAction($trick, $deleteTrickResponder)
        );
    }
}
