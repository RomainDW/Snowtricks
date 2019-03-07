<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 1:09 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateTrickControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();

        $client->request('GET', '/trick/add');

        // Test if the user is redirected (when he is not connected)
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Test if the user is redirected on the login page
        $this->assertEquals('/login', $client->getResponse()->headers->get('location'));
    }
}
