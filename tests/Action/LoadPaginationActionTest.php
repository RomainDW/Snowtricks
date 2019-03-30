<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/27/19
 * Time: 7:25 PM.
 */

namespace App\Tests\Action;

use App\Action\LoadPaginationAction;
use App\Repository\TrickRepository;
use App\Responder\TwigResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoadPaginationActionTest extends KernelTestCase
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
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(TrickRepository::class, $this->trickRepository);

        $loadPagination = new LoadPaginationAction(
            $this->trickRepository
        );

        static::assertInstanceOf(
            LoadPaginationAction::class,
            $loadPagination
        );
    }

    public function testResponse()
    {
        $offset = 0;
        $responder = new TwigResponder($this->twig, $this->urlGenerator);

        $loadPagination = new LoadPaginationAction(
            $this->trickRepository
        );

        $response = $loadPagination($offset, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
