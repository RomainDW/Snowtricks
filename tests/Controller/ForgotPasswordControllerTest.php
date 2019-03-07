<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 1:47 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForgotPasswordControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/forgot-password');

        // Test of the success of the request
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Test if there is a form
        $this->assertEquals(1, $crawler->filter('form')->count());
    }
}
