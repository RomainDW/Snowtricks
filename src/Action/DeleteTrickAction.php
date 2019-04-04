<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/9/19
 * Time: 7:14 PM.
 */

namespace App\Action;

use App\Action\Interfaces\DeleteTrickActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickAction implements DeleteTrickActionInterface
{
    /**
     * @var TrickServiceInterface
     */
    private $trickService;

    /**
     * DeleteTrickAction constructor.
     *
     * @param TrickServiceInterface $trickService
     */
    public function __construct(TrickServiceInterface $trickService)
    {
        $this->trickService = $trickService;
    }

    /**
     * @Route("/trick/delete/{slug}", methods={"POST"}, name="app_delete_trick")
     *
     * @param Trick                  $trick
     * @param TwigResponderInterface $responder
     *
     * @return RedirectResponse
     */
    public function __invoke(Trick $trick, TwigResponderInterface $responder)
    {
        $this->trickService->deleteTrick($trick);

        return $responder('homepage');
    }
}
