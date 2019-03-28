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
use App\Responder\HomeResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomeActionTest extends KernelTestCase
{
    private $trickRepository;
    private $twig;

    public function setUp()
    {
        static::bootKernel();

        $this->trickRepository = $this->createMock(TrickRepository::class);
        $this->twig = $this->createMock(Environment::class);
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
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponse()
    {
        $responder = new HomeResponder($this->twig);

        $homeAction = new HomeAction($this->trickRepository);

        $response = $homeAction($responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
