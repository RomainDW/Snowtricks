<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/9/19
 * Time: 7:14 PM.
 */

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Responder\DeleteTrickResponder;
use App\Domain\Service\TrickService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickAction
{
    /**
     * @var TrickService
     */
    private $trickService;

    /**
     * DeleteTrickAction constructor.
     *
     * @param TrickService $trickService
     */
    public function __construct(TrickService $trickService)
    {
        $this->trickService = $trickService;
    }

    /**
     * @Route("/trick/delete/{slug}", methods={"POST"}, name="app_delete_trick")
     *
     * @param Trick                $trick
     * @param DeleteTrickResponder $responder
     *
     * @return RedirectResponse
     */
    public function __invoke(Trick $trick, DeleteTrickResponder $responder)
    {
        $this->trickService->deleteTrick($trick);

        return $responder();
    }
}
