<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:24 PM.
 */

namespace App\Domain\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

interface CategoryInterface extends EntityInterface
{
    public function __construct();

    public function getName(): ?string;

    public function getTricks(): Collection;
}
