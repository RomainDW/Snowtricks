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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EditTrickFormHandler
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

            if ($form->get('images')->getData()) {
                foreach ($form->get('images')->getData() as $image) {
                    $imageViolations = $this->validator->validate($image, null, ['edit_trick']);

                    if (0 !== count($imageViolations)) {
                        foreach ($imageViolations as $violation) {
                            $this->flashBag->add('error', $violation->getMessage());
                        }

                        $image->setFile(null);

                        return false;
                    }
                }
            }

            $updatedTrickDTO = $this->trickService->UpdateTrick($trick, $form->getData());

            $TrickDTOViolations = $this->validator->validate($updatedTrickDTO);

            if (0 !== count($TrickDTOViolations)) {
                foreach ($TrickDTOViolations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            }

            $trick->updateFromDTO($updatedTrickDTO);

            $TrickViolations = $this->validator->validate($trick, null, ['edit_trick']);

            if (0 !== count($TrickViolations)) {
                foreach ($TrickViolations as $violation) {
                    $this->flashBag->add('error', $violation->getMessage());
                }

                return false;
            }

            $this->trickRepository->save($trick);
            $this->flashBag->add('success', 'La figure a bien été modifiée !');

            return new RedirectResponse($this->url_generator->generate('app_edit_trick', ['slug' => $trick->getSlug()]));
        }

        return false;
    }
}
