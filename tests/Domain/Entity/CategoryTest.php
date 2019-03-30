<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 1:55 PM.
 */

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testImplementation()
    {
        $name = 'test';

        $category = new Category();
        $category->setName($name);

        static::assertSame($name, $category->getName());
    }
}
