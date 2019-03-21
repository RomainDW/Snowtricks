<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 18/01/2019
 * Time: 08:31.
 */

namespace App\Action;

use App\Repository\TrickRepository;
use App\Responder\HomeResponder;
use App\Utils\SnowtrickConfig;
use Symfony\Component\Routing\Annotation\Route;

class HomeAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * HomeAction constructor.
     *
     * @param TrickRepository $trickRepository
     */
    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    /**
     * @Route("/", name="homepage")
     *
     * @param HomeResponder $responder
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(HomeResponder $responder)
    {
        $numberOfResults = SnowtrickConfig::getNumberOfResults();

        $tricks = $this->trickRepository->getTricksPagination(0, $numberOfResults);

        $totalTricks = $this->trickRepository->getNumberOfTotalTricks();

        return $responder([
            'tricks' => $tricks,
            'number_of_results' => $numberOfResults,
            'total_tricks' => $totalTricks,
        ]);
    }
}
