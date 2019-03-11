<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 6:34 PM.
 */

namespace App\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ForgotPasswordResponder
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * EditTrickResponder constructor.
     *
     * @param Environment           $twig
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param array       $args
     * @param string|null $type
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(array $args, string $type = null)
    {
        if ('redirect' === $type) {
            return new RedirectResponse($this->urlGenerator->generate('app_forgot_password'));
        }

        return new Response($this->twig->render('security/forgot-password.html.twig', $args));
    }
}
