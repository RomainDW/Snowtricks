<?php

namespace App\Domain\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageInterface
{
    /**
     * @return string|null
     */
    public function getFileName();

    /**
     * @return UploadedFile
     */
    public function getFile();

    /**
     * @param UploadedFile $file
     *
     * @return Image
     */
    public function setFile($file);

    /**
     * @param string $file_name
     *
     * @return mixed
     */
    public function setFileName(string $file_name);
}
