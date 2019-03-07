<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 2:03 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowTrickControllerTest extends WebTestCase
{
    public function testValidSlug()
    {
        $client = self::createClient();

        $client->request('GET', '/trick/show/trick-n-1');

        // Test of the success of the request
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
