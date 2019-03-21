<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/23/19
 * Time: 4:00 PM.
 */

namespace App\Domain\Service;

use App\Domain\Exception\ValidationException;
use App\DTO\CreateTrickDTO;
use App\Domain\Entity\Image;
use App\Domain\Entity\Trick;
use App\Domain\Entity\Video;
use App\Event\ImageRemoveEvent;
use App\Event\ImageUploadEvent;
use App\Event\VideoUploadEvent;
use App\Utils\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrickService
{
    private $dispatcher;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
        FlashBagInterface $flashBag
    ) {
        $this->dispatcher = $dispatcher;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
    }

    /**
     * @param CreateTrickDTO $createTrickDTO
     * @param UserInterface  $user
     *
     * @return Trick
     *
     * @throws \Exception
     */
    public function InitTrick(CreateTrickDTO $createTrickDTO, UserInterface $user)
    {
        $createTrickDTO->createdAt = new \DateTime();

        $createTrickDTO->user = $user;
        $createTrickDTO->slug = Slugger::slugify($createTrickDTO->title);

        $trick = new Trick($createTrickDTO);

        foreach ($trick->getImages() as $image) {
            $image->setTrick($trick);
            $event = new ImageUploadEvent($image);
            $this->dispatcher->dispatch(ImageUploadEvent::NAME, $event);
            $image->setFile(null);
        }

        foreach ($trick->getVideos() as $video) {
            $video->setTrick($trick);
            $event = new VideoUploadEvent($video);
            $this->dispatcher->dispatch(VideoUploadEvent::NAME, $event);
        }

        return $trick;
    }

    /**
     * @param Trick          $trick
     * @param CreateTrickDTO $trickDTO
     *
     * @return CreateTrickDTO
     *
     * @throws \Exception
     */
    public function UpdateTrick(Trick $trick, CreateTrickDTO $trickDTO)
    {
        $trickDTO->updatedAt = new \DateTime();
        $trickDTO->slug = Slugger::slugify($trickDTO->title);

        $originalImages = new ArrayCollection();
        $originalVideos = new ArrayCollection();

        // Create an ArrayCollection of the current Image objects in the database
        foreach ($trick->getImages() as $image) {
            $originalImages->add($image);
        }

        foreach ($trick->getVideos() as $video) {
            $originalVideos->add($video);
        }

        foreach ($trickDTO->images as $image) {
            if ($image instanceof Image) {
                if (!empty($image->getFile() && !empty($image->getFileName()))) {
                    $imageRemoveEvent = new ImageRemoveEvent($image);
                    $this->dispatcher->dispatch(ImageRemoveEvent::NAME, $imageRemoveEvent);
                }
                if (!empty($image->getFile())) {
                    $image->setTrick($trick);
                    $imageUploadEvent = new ImageUploadEvent($image);
                    $this->dispatcher->dispatch(ImageUploadEvent::NAME, $imageUploadEvent);
                    $image->setFile(null);
                }
            }
        }

        foreach ($originalImages as $image) {
            // remove old images that has been removed
            if (false === in_array($image, $trickDTO->images)) {
                // remove the Image from the Trick
                $trick->getImages()->removeElement($image);
                $event = new ImageRemoveEvent($image);
                $this->dispatcher->dispatch(ImageRemoveEvent::NAME, $event);
            }
        }

        foreach ($trickDTO->videos as $video) {
            if ($video instanceof Video) {
                $video->setTrick($trick);
                $event = new VideoUploadEvent($video);
                $this->dispatcher->dispatch(VideoUploadEvent::NAME, $event);
            }
        }

        foreach ($originalVideos as $originalVideo) {
            if (false === in_array($originalVideo, $trickDTO->videos)) {
                $trick->getVideos()->removeElement($originalVideo);
            }
        }

        return $trickDTO;
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

        if ('update' == $type) {
            $this->flashBag->add('success', 'La figure a bien été modifiée !');
        } elseif ('add' == $type) {
            $this->flashBag->add('success', 'La figure a bien été ajoutée !');
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
