<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 9:40 AM.
 */

namespace App\Event\Interfaces;

use App\Domain\Entity\Interfaces\VideoInterface;

interface VideoEventInterface
{
    public function __construct(VideoInterface $video);

    public function getVideo(): VideoInterface;
}
