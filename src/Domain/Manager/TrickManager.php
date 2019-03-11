<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 11:04 AM.
 */

namespace App\Domain\Manager;

use App\Domain\Entity\Trick;
use App\Domain\Exception\ValidationException;
use App\Event\ImageRemoveEvent;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickManager
{
    private $validator;
    private $doctrine;
    private $flashBag;
    private $dispatcher;

    /**
     * TrickManager constructor.
     *
     * @param ValidatorInterface       $validator
     * @param ManagerRegistry          $doctrine
     * @param FlashBagInterface        $flashBag
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, FlashBagInterface $flashBag, EventDispatcherInterface $dispatcher)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Trick  $trick
     * @param string $type
     *
     * @throws ValidationException
     */
    public function save(Trick $trick, string $type = 'add')
    {
        if (count($errors = $this->validator->validate($trick))) {
            throw new ValidationException($errors);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($trick);
        $manager->flush();

        switch ($type) {
            case 'update':
                $this->flashBag->add('success', 'La figure a bien été modifiée !');
                break;
            case 'add':
                $this->flashBag->add('success', 'La figure a bien été ajoutée !');
                break;
        }
    }

    /**
     * @param Trick $trick
     */
    public function deleteTrick(Trick $trick)
    {
        foreach ($trick->getImages() as $image) {
            $imageRemoveEvent = new ImageRemoveEvent($image);
            $this->dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
        }

        $manager = $this->doctrine->getManager();
        $manager->remove($trick);
        $manager->flush();

        $this->flashBag->add('success', 'la figure '.$trick->getTitle().' a bien été supprimée.');
    }
}
