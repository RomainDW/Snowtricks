<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 7:36 PM.
 */

namespace App\Action;

use App\Action\Interfaces\LoadPaginationInterface;
use App\Repository\TrickRepository;
use App\Responder\Interfaces\TwigResponderInterface;
use App\Utils\SnowtrickConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadPaginationAction implements LoadPaginationInterface
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * LoadPaginationAction constructor.
     *
     * @param TrickRepository $trickRepository
     */
    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/more/{offset}", name="loadPagination", methods={"POST"})
     *
     * @param $offset
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke($offset, TwigResponderInterface $responder)
    {
        $tricks = $this->trickRepository->getTricksPagination($offset, SnowtrickConfig::getNumberOfResults());

        return $responder('homepage/_partials/ajax-content.html.twig', ['tricks' => $tricks]);
    }
}
