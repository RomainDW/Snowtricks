<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/26/19
 * Time: 8:05 PM.
 */

namespace App\Tests\Action;

use App\Action\LoadCommentsAction;
use App\Domain\Entity\Trick;
use App\Repository\CommentRepository;
use App\Responder\LoadCommentsResponder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoadCommentsActionTest extends KernelTestCase
{
    private $commentRepository;
    private $twig;
    private $entityManager;

    public function setUp()
    {
        static::bootKernel();

        $this->entityManager = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->commentRepository = $this->createMock(CommentRepository::class);
        $this->twig = $this->createMock(Environment::class);
    }

    public function testDependencies()
    {
        static::assertInstanceOf(Environment::class, $this->twig);
        static::assertInstanceOf(CommentRepository::class, $this->commentRepository);

        $loadCommentsAction = new LoadCommentsAction(
            $this->commentRepository
        );

        static::assertInstanceOf(
            LoadCommentsAction::class,
            $loadCommentsAction
        );
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponse()
    {
        $trick = $trick = $this->entityManager->getRepository(Trick::class)->findOneBy(['title' => 'test']);
        $offset = 0;
        $responder = new LoadCommentsResponder($this->twig);

        $loadCommentsAction = new LoadCommentsAction(
            $this->commentRepository
        );

        $response = $loadCommentsAction($trick, $offset, $responder);

        static::assertInstanceOf(
            Response::class,
            $response
        );
    }
}
