<?php

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(Image $file)
    {
        if (!$file->getFile() instanceof UploadedFile) {
            return null;
        }

        $fileName = md5(uniqid()).'.'.$file->getFile()->guessExtension();

        try {
            $file->getFile()->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function remove(Image $image)
    {
        unlink($this->getTargetDirectory().$image->getFileName());
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
