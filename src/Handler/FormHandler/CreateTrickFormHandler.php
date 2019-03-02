<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/1/19
 * Time: 2:55 PM.
 */

namespace App\Handler\FormHandler;

use App\Entity\User;
use App\Repository\TrickRepository;
use App\Service\TrickService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CreateTrickFormHandler
{
    private $trickService;
    private $trickRepository;
    private $flashBag;

    /**
     * CreateTrickFormHandler constructor.
     *
     * @param TrickService      $trickService
     * @param TrickRepository   $trickRepository
     * @param FlashBagInterface $flashBag
     */
    public function __construct(TrickService $trickService, TrickRepository $trickRepository, FlashBagInterface $flashBag)
    {
        $this->trickService = $trickService;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
    }

    /**
     * @param FormInterface $form
     * @param User          $user
     *
     * @return \App\Entity\Trick|bool
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $this->trickService->InitTrick($form->getData(), $user);

            $this->trickRepository->save($trick);

            $this->flashBag->add('success', 'La figure a bien été ajoutée !');

            return $trick;
        } else {
            return false;
        }
    }
}
