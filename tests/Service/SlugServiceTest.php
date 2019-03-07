<?php

namespace App\Tests\Service;

use App\Service\SlugService;
use PHPUnit\Framework\TestCase;

class SlugServiceTest extends TestCase
{
    public function testSlugify()
    {
        $slug = 'lorem zef ezf-e // M-u_Y';

        $result = SlugService::slugify($slug);

        static::assertEquals('lorem-zef-ezf-e-m-u-y', $result);
    }
}
