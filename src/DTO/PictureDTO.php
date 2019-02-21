<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/19/19
 * Time: 8:04 PM.
 */

namespace App\DTO;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureDTO
{
    public $file_name;
    public $alt;
    public $user;
    public $file;

    /**
     * PictureDTO constructor.
     *
     * @param $file_name
     * @param $alt
     * @param $user
     * @param $file
     */
    public function __construct(UploadedFile $file, string $file_name = null, string $alt = null, User $user = null)
    {
        $this->file_name = $file_name;
        $this->alt = $alt;
        $this->user = $user;
        $this->file = $file;
    }
}
