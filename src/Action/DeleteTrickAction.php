<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/9/19
 * Time: 7:14 PM.
 */

namespace App\Action;

use App\Domain\Entity\Trick;
use App\Domain\Manager\TrickManager;
use App\Responder\DeleteTrickResponder;
use App\Service\DeleteService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteTrickAction
{
    /**
     * @var DeleteTrickResponder
     */
    private $responder;

    /**
     * @var TrickManager
     */
    private $trickManager;

    /**
     * DeleteTrickAction constructor.
     *
     * @param DeleteTrickResponder $responder
     * @param TrickManager         $trickManager
     */
    public function __construct(DeleteTrickResponder $responder, TrickManager $trickManager)
    {
        $this->responder = $responder;
        $this->trickManager = $trickManager;
    }

    /**
     * @Route("/trick/delete/{slug}", methods={"POST"}, name="app_delete_trick")
     *
     * @param Trick $trick
     *
     * @return RedirectResponse
     */
    public function __invoke(Trick $trick)
    {
        $this->trickManager->deleteTrick($trick);

        $responder = $this->responder;

        return $responder();
    }
}
