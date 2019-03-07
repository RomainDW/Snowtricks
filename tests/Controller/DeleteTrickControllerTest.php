<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 1:13 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteTrickControllerTest extends WebTestCase
{
    public function testDelete()
    {
        $client = self::createClient();

        // Test delete a trick with GET method
        $client->request('GET', '/trick/delete/trick-n-1');

        // Test if the user get a "Method Not Allowed" error
        $this->assertEquals(405, $client->getResponse()->getStatusCode());

        // Test delete a trick with POST method
        $client->request('POST', '/trick/delete/trick-n-1');

        // Test if the user redirected (when he is not connected)
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Test if the user is redirected on the login page
        $this->assertEquals('/login', $client->getResponse()->headers->get('location'));
    }
}
