<?php

namespace App\Domain\Entity\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageInterface extends EntityInterface
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
     */
    public function setFile($file);

    /**
     * @param string $file_name
     *
     * @return mixed
     */
    public function setFileName(string $file_name);

    /**
     * @return string|null
     */
    public function getAlt(): ?string;


}
