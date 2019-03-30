<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/28/19
 * Time: 9:30 AM.
 */

namespace App\Tests\Utils;

use App\Utils\SnowtrickConfig;
use PHPUnit\Framework\TestCase;

class SnowtrickConfigTest extends TestCase
{
    public function testGetNumberOfResults()
    {
        $resultToCompare = 6;
        $result = SnowtrickConfig::getNumberOfResults();

        static::assertEquals($resultToCompare, $result);
    }

    public function testGetNumberOfCommentsDisplayed()
    {
        $resultToCompare = 3;
        $result = SnowtrickConfig::getNumberOfCommentsDisplayed();

        static::assertEquals($resultToCompare, $result);
    }
}
