<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:08 PM.
 */

namespace App\Domain\DTO\Interfaces;

use App\Domain\Entity\Category;
use App\Domain\Entity\Trick;

interface TrickDTOInterface
{
    public function __construct($title, $description, Category $category, array $images, array $videos);

    public static function createFromTrick(Trick $trick): self;
}
