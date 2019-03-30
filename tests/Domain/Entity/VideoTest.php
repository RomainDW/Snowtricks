<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 2:15 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Trick;
use App\Domain\Entity\Video;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testImplementation()
    {
        $embed = 'test';
        $trick = $this->createMock(Trick::class);
        $type = 'youtube';

        $video = new Video();
        $video
            ->setEmbed($embed)
            ->setTrick($trick)
            ->setType($type)
        ;

        static::assertSame($embed, $video->getEmbed());
        static::assertSame($trick, $video->getTrick());
        static::assertSame($type, $video->getType());
    }
}
