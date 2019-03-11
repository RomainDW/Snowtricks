<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 7:43 PM.
 */

namespace App\Service;

class SnowtrickConfig
{
    public static function getNumberOfResults()
    {
        return 6;
    }

    public static function getNumberOfCommentsDisplayed()
    {
        return 3;
    }
}
