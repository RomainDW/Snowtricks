<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 1:50 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Category;
use App\Domain\Entity\Trick;
use Exception;
use PHPUnit\Framework\TestCase;

class TrickTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testImplementation()
    {
        $title = 'test';
        $description = 'test';
        $category = $this->createMock(Category::class);

        $trickDTO = new CreateTrickDTO($title, $description, $category, [], []);

        $trick = new Trick($trickDTO);

        static::assertSame($title, $trick->getTitle());
        static::assertSame($description, $trick->getDescription());
        static::assertSame($category, $trick->getCategory());
    }
}
