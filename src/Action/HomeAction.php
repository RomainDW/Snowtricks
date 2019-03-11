<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 18/01/2019
 * Time: 08:31.
 */

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Repository\TrickRepository;
use App\Responder\HomeResponder;
use App\Service\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class HomeAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;
    /**
     * @var HomeResponder
     */
    private $responder;

    /**
     * HomeAction constructor.
     *
     * @param TrickRepository $trickRepository
     * @param HomeResponder   $responder
     */
    public function __construct(TrickRepository $trickRepository, HomeResponder $responder)
    {
        $this->trickRepository = $trickRepository;
        $this->responder = $responder;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke()
    {
        $numberOfResults = SnowtrickConfig::getNumberOfResults();

        //Todo: Utiliser le manager

        $tricks = $this->trickRepository->getTricksPagination(0, $numberOfResults);

        $totalTricks = $this->trickRepository->getNumberOfTotalTricks();

        $responder = $this->responder;

        return $responder([
            'tricks' => $tricks,
            'number_of_results' => $numberOfResults,
            'total_tricks' => $totalTricks,
        ]);
    }
}
