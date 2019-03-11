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
use App\Service\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class LoadPaginationAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var LoadPaginationResponder
     */
    private $responder;

    /**
     * LoadPaginationAction constructor.
     *
     * @param TrickRepository         $trickRepository
     * @param LoadPaginationResponder $responder
     */
    public function __construct(TrickRepository $trickRepository, LoadPaginationResponder $responder)
    {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
    }

    /**
     * @Route("/more/{offset}", name="loadPagination", methods={"POST"})
     *
     * @param $offset
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($offset)
    {
        // Todo: utiliser le manager
        $tricks = $this->trickRepository->getTricksPagination($offset, SnowtrickConfig::getNumberOfResults());

        $responder = $this->responder;

        return $responder(['tricks' => $tricks]);
    }
}
