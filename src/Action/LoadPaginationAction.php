<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 7:36 PM.
 */

namespace App\Action;

use App\Repository\TrickRepository;
use App\Responder\LoadPaginationResponder;
use App\Utils\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class LoadPaginationAction
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
     * @param LoadPaginationResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($offset, LoadPaginationResponder $responder)
    {
        $tricks = $this->trickRepository->getTricksPagination($offset, SnowtrickConfig::getNumberOfResults());

        return $responder(['tricks' => $tricks]);
    }
}
