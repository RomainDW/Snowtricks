<?php

namespace App\DTO;

use App\Entity\Category;

class CreateTrickDTO
{
    public $title;
    public $description;
    public $slug;
    public $category;
    public $user;
    public $images;
    public $videos;
    public $createdAt;
    public $updatedAt;

    /**
     * CreateTrickDTO constructor.
     * @param $title
     * @param $description
     * @param $category
     * @param $images
     * @param $videos
     */
    public function __construct($title, $description, Category $category, array $images, array $videos)
    {
        $this->title = $title;
        $this->description = $description;
        $this->category = $category;
        $this->images = $images;
        $this->videos = $videos;
    }


}
