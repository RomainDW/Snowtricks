<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 11:57 AM.
 */

namespace App\Responder;

use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class TwigResponder implements TwigResponderInterface
{
    private $twig;
    private $urlGenerator;

    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param string     $view
     * @param array|null $args
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(string $view, array $args = [])
    {
        if (false === strpos($view, 'twig')) {
            return new RedirectResponse($this->urlGenerator->generate($view, $args));
        }

        return new Response($this->twig->render($view, $args));
    }
}
