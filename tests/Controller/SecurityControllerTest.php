<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 2:01 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testRegistration()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/login');

        // Test of the success of the request
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test if there is a form
        $this->assertEquals(1, $crawler->filter('form')->count());
    }
}
