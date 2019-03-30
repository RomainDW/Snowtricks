<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 2:00 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Image;
use App\Domain\Entity\ImageInterface;
use App\Domain\Entity\Trick;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTest extends TestCase
{
    public function testImplementation()
    {
        $alt = 'test';
        $fileName = 'test';
        $file = $this->createMock(UploadedFile::class);
        $trick = $this->createMock(Trick::class);

        $image = new Image();
        $image
            ->setAlt($alt)
            ->setFileName($fileName)
            ->setFile($file)
            ->setTrick($trick)
        ;

        static::assertInstanceOf(ImageInterface::class, $image);
        static::assertSame($alt, $image->getAlt());
        static::assertSame($fileName, $image->getFileName());
        static::assertSame($file, $image->getFile());
        static::assertSame($trick, $image->getTrick());
    }
}
