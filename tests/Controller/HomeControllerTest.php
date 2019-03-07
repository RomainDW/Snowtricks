<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 12:02 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/');

        // Test of the success of the request
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test the number of tricks displayed (all tricks have the "card" class)
        $this->assertEquals(6, $crawler->filter('.card')->count());
    }
}
