<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/2/19
 * Time: 1:49 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\Image;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Handler\FormHandler\Interfaces\EditTrickFormHandlerInterface;
use App\Repository\TrickRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditTrickFormHandler implements EditTrickFormHandlerInterface
{
    private $trickService;
    private $trickRepository;
    private $flashBag;
    private $url_generator;
    private $validator;

    /**
     * CreateTrickFormHandler constructor.
     *
     * @param TrickServiceInterface $trickService
     * @param TrickRepository       $trickRepository
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $url_generator
     * @param ValidatorInterface    $validator
     */
    public function __construct(TrickServiceInterface $trickService, TrickRepository $trickRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, ValidatorInterface $validator)
    {
        $this->trickService = $trickService;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->validator = $validator;
    }

    /**
     * @param FormInterface  $form
     * @param TrickInterface $trick
     *
     * @return bool|RedirectResponse
     */
    public function handle(FormInterface $form, TrickInterface $trick)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $updatedTrickDTO = $this->trickService->UpdateTrick($trick, $form->getData());

            $trick->updateFromDTO($updatedTrickDTO);

            $TrickViolations = $this->validator->validate($trick, null, ['Default', 'edit_trick']);

            if (0 !== count($TrickViolations)) {
                foreach ($TrickViolations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            }

            $this->trickService->save($trick, 'update');

            return true;
        }

        foreach ($form->getData()->images as $image) {
            /* @var Image $image */
            $image->setFile(null);
        }

        return false;
    }
}
