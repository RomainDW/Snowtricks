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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTrickFormHandler
{
    private $trickService;
    private $trickRepository;
    private $flashBag;
    private $url_generator;
    private $validator;

    /**
     * CreateTrickFormHandler constructor.
     *
     * @param TrickService          $trickService
     * @param TrickRepository       $trickRepository
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $url_generator
     * @param ValidatorInterface    $validator
     */
    public function __construct(TrickService $trickService, TrickRepository $trickRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, ValidatorInterface $validator)
    {
        $this->trickService = $trickService;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->validator = $validator;
    }

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool|RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(FormInterface $form, UserInterface $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $this->trickService->InitTrick($form->getData(), $user);

            $violations = $this->validator->validate($trick);

            if (0 !== count($violations)) {
                foreach ($violations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }
                return false;
            } else {
                $this->trickRepository->save($trick);

                $this->flashBag->add('success', 'La figure a bien été ajoutée !');

                return new RedirectResponse($this->url_generator->generate('app_show_trick', ['slug' => $trick->getSlug()]));
            }
        } else {
            return false;
        }
    }
}
