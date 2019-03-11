<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 4:59 PM.
 */

namespace App\Handler\FormHandler;

use App\Service\TrickService;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTrickFormHandler
{
    /**
     * @var TrickService
     */
    private $trickService;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * CreateTrickFormHandler constructor.
     *
     * @param TrickService       $trickService
     * @param ValidatorInterface $validator
     * @param FlashBagInterface  $flashBag
     */
    public function __construct(TrickService $trickService, ValidatorInterface $validator, FlashBagInterface $flashBag)
    {
        $this->trickService = $trickService;
        $this->validator = $validator;
        $this->flashBag = $flashBag;
    }

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return \App\Domain\Entity\Trick|bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form, UserInterface $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $this->trickService->InitTrick($form->getData(), $user);

            if (count($errors = $this->validator->validate($trick, null, ['Default', 'add_trick']))) {
                foreach ($errors as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }

                return false;
            }

            return $trick;
        }

        return false;
    }
}
