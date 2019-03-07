<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 1:44 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EditTrickControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();

        $client->request('GET', '/trick/edit/trick-n-1');

        // Test if the user is redirected (when he is not connected)
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Test if the user is redirected on the login page
        $this->assertEquals('/login', $client->getResponse()->headers->get('location'));
    }
}
