<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/26/19
 * Time: 7:53 PM.
 */

namespace App\Tests\Action;

use App\Action\HomeAction;
use App\Repository\TrickRepository;
use App\Responder\TwigResponder;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeActionTest extends KernelTestCase
{
    private $trickRepository;
    private $twig;
    private $urlGenerator;

    public function setUp()
    {
        static::bootKernel();

        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->twig = $this->createMock(Environment::class);
        $this->urlGenerator = static::$kernel->getContainer()->get('router');
    }

    public function testDependencies()
    {
        static::assertInstanceOf(TrickRepository::class, $this->trickRepository);
        static::assertInstanceOf(Environment::class, $this->twig);

        $homeAction = new HomeAction(
            $this->trickRepository
        );

        static::assertInstanceOf(
            HomeAction::class,
            $homeAction
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function testResponse()
    {
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $homeAction = new HomeAction($this->trickRepository);

        $response = $homeAction($responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
