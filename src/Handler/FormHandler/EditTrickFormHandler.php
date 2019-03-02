<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/2/19
 * Time: 1:49 PM.
 */

namespace App\Handler\FormHandler;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use App\Service\TrickService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EditTrickFormHandler
{
    private $trickService;
    private $trickRepository;
    private $flashBag;
    private $url_generator;

    /**
     * CreateTrickFormHandler constructor.
     *
     * @param TrickService          $trickService
     * @param TrickRepository       $trickRepository
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $url_generator
     */
    public function __construct(TrickService $trickService, TrickRepository $trickRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator)
    {
        $this->trickService = $trickService;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
    }

    /**
     * @param FormInterface $form
     * @param Trick         $trick
     *
     * @return bool|RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(FormInterface $form, Trick $trick)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedTrickDTO = $this->trickService->UpdateTrick($trick, $form->getData());

            $trick->updateFromDTO($updatedTrickDTO);

            $this->trickRepository->save($trick);

            $this->flashBag->add('success', 'La figure a bien été modifiée !');

            return new RedirectResponse($this->url_generator->generate('app_edit_trick', ['slug' => $trick->getSlug()]));
        } else {
            return false;
        }
    }
}
