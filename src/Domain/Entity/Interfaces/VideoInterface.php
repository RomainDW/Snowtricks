<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:32 PM.
 */

namespace App\Domain\Entity\Interfaces;

use App\Domain\Entity\Trick;

interface VideoInterface extends EntityInterface
{
    public function getEmbed(): ?string;

    public function getTrick(): ?Trick;

    public function getType(): ?string;

    public function getEmbed_Id(): ?string;

    public function setEmbed_Id(string $embedId);

    public function setType(string $type);
}
