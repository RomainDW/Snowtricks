<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 12:35 PM.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * https://symfony.com/doc/current/testing/http_authentication.html.
 */
class AccountControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = self::createClient();

        $client->request('GET', '/my-account');

        // Test if the user is redirected (when he is not connected)
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        // Test if the user is redirected on the login page
        $this->assertEquals('/login', $client->getResponse()->headers->get('location'));
    }
}
