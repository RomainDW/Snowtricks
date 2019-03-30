<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 2:22 PM.
 */

namespace App\Tests\Responder;

use App\Responder\Interfaces\TwigResponderInterface;
use App\Responder\TwigResponder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class TwigResponderTest extends TestCase
{
    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function testResponse()
    {
        $twig = $this->createMock(Environment::class);
        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/');

        $responder = new TwigResponder($twig, $urlGenerator);
        $response = $responder('account/my-account.html.twig');
        $redirectResponse = $responder('homepage');

        static::assertInstanceOf(TwigResponderInterface::class, $responder);
        static::assertInstanceOf(Response::class, $response);
        static::assertInstanceOf(RedirectResponse::class, $redirectResponse);
    }
}
