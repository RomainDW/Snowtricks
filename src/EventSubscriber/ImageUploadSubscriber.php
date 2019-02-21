<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/8/19
 * Time: 3:13 PM.
 */

namespace App\EventSubscriber;

use App\DTO\PictureDTO;
use App\Entity\Image;
use App\Event\ImageRemoveEvent;
use App\Event\ImageUploadEvent;
use App\Event\UserPictureUploadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploadSubscriber implements EventSubscriberInterface
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ImageUploadEvent::NAME => 'onImageUpload',
            ImageRemoveEvent::NAME => 'onImageRemove',
            UserPictureUploadEvent::NAME => 'onUserPictureUpload',
        ];
    }

    public function onImageUpload(ImageUploadEvent $event)
    {
        $image = $event->getImage();
        $fileName = $this->upload($image);

        if (null === $fileName) {
            return;
        }

        $image->setFileName($fileName);
    }

    public function onImageRemove(ImageRemoveEvent $event)
    {
        $image = $event->getImage();

        $this->remove($image);
    }

    public function onUserPictureUpload(UserPictureUploadEvent $event)
    {
        $picture = $event->getPicture();
        $fileName = $this->uploadUserPicture($picture);

        if (null === $fileName) {
            return;
        }

        $picture->file_name = $fileName;
    }

    private function upload(Image $file)
    {
        if (!$file->getFile() instanceof UploadedFile) {
            return null;
        }

        $fileName = md5(uniqid()).'.'.$file->getFile()->guessClientExtension();

        try {
            $file->getFile()->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    private function remove(Image $image)
    {
        if (file_exists($this->getTargetDirectory().$image->getFileName())) {
            unlink($this->getTargetDirectory().$image->getFileName());
        }
    }

    private function uploadUserPicture(PictureDTO $picture)
    {
        if (!$picture->file instanceof UploadedFile) {
            return null;
        }

        $fileName = md5(uniqid()).'.'.$picture->file->guessClientExtension();

        try {
            $picture->file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
