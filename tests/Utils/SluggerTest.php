<?php

namespace App\Tests\Utils;

use App\Utils\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugify()
    {
        $slug = 'lorem zef ezf-e // M-u_Y';

        $result = Slugger::slugify($slug);

        static::assertEquals('lorem-zef-ezf-e-m-u-y', $result);
    }
}
