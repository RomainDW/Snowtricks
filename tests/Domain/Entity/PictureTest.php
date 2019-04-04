<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 2:05 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Interfaces\ImageInterface;
use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureTest extends TestCase
{
    public function testImplementation()
    {
        $alt = 'test';
        $fileName = 'test';
        $file = $this->createMock(UploadedFile::class);
        $user = $this->createMock(User::class);

        $picture = new Picture();
        $picture
            ->setAlt($alt)
            ->setFileName($fileName)
            ->setFile($file)
            ->setUser($user)
        ;

        static::assertInstanceOf(ImageInterface::class, $picture);
        static::assertSame($alt, $picture->getAlt());
        static::assertSame($fileName, $picture->getFileName());
        static::assertSame($file, $picture->getFile());
        static::assertSame($user, $picture->getUser());
    }
}
