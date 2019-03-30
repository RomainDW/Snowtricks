<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Category;
use App\Domain\Entity\Trick;

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

    public static function createFromTrick(Trick $trick)
    {
        $trickRequest = new self(
            $trick->getTitle(),
            $trick->getDescription(),
            $trick->getCategory(),
            $trick->getImages()->toArray(),
            $trick->getVideos()->toArray()
        );

        $trickRequest->user = $trick->getUser();
        $trickRequest->createdAt = $trick->getCreatedAt();
        $trickRequest->updatedAt = $trick->getUpdatedAt();

        return $trickRequest;
    }


}
