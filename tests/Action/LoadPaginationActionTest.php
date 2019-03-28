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
use App\Responder\LoadPaginationResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoadPaginationActionTest extends KernelTestCase
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

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponse()
    {
        $offset = 0;
        $responder = new LoadPaginationResponder($this->twig);

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
