<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 4:59 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Trick;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Handler\FormHandler\Interfaces\CreateTrickFormHandlerInterface;
use Exception;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTrickFormHandler implements CreateTrickFormHandlerInterface
{
    /**
     * @var TrickServiceInterface
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
     * @param TrickServiceInterface $trickService
     * @param ValidatorInterface    $validator
     * @param FlashBagInterface     $flashBag
     */
    public function __construct(TrickServiceInterface $trickService, ValidatorInterface $validator, FlashBagInterface $flashBag)
    {
        $this->trickService = $trickService;
        $this->validator = $validator;
        $this->flashBag = $flashBag;
    }

    /**
     * @param FormInterface $form
     * @param Security      $security
     *
     * @return bool
     *
     * @throws Exception
     */
    public function handle(FormInterface $form, Security $security): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $this->trickService->InitTrick($form->getData(), $security->getUser());

            if (count($errors = $this->validator->validate($trick, null, ['Default', 'add_trick']))) {
                foreach ($errors as $error) {
                    $this->flashBag->add('error', $error->getMessage());
                }

                return false;
            }

            $this->trickService->save($trick);

            return true;
        }

        return false;
    }
}
