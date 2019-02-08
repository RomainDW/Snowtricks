<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/5/19
 * Time: 7:47 PM.
 */

namespace App\EventListener;

use App\Entity\Image;
use App\Service\ImageUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class ImageUploadListener
{
    private $uploader;

    public function __construct(ImageUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $fileName = $this->uploader->upload($entity);

        if (null === $fileName) {
            return;
        }

        $entity->setFileName($fileName);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Image) {
            return;
        }

        $fileName = $this->uploader->upload($entity);

        if (null === $fileName) {
            return;
        }

        $entity->setFileName($fileName);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Image) {
            return;
        }
        $this->uploader->remove($entity);
    }
}
