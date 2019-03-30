<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 18/01/2019
 * Time: 08:31.
 */

namespace App\Action;

use App\Repository\TrickRepository;
use App\Responder\Interfaces\TwigResponderInterface;
use App\Utils\SnowtrickConfig;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\HttpFoundation\Response;
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
     * @param TwigResponderInterface $responder
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    public function __invoke(TwigResponderInterface $responder)
    {
        $numberOfResults = SnowtrickConfig::getNumberOfResults();

        $tricks = $this->trickRepository->getTricksPagination(0, $numberOfResults);

        $totalTricks = $this->trickRepository->getNumberOfTotalTricks();

        return $responder('homepage/index.html.twig', [
            'tricks' => $tricks,
            'number_of_results' => $numberOfResults,
            'total_tricks' => $totalTricks,
        ]);
    }
}
