<?php
/**
 * Created by PhpStorm.
 * User: saysa
 * Date: 2019-03-07
 * Time: 15:26.
 */

namespace App\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class CreateTrickResponder
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
     * CreateTrickResponder constructor.
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
     * @param array  $args
     * @param string $type
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
            return new RedirectResponse($this->urlGenerator->generate('app_show_trick', $args));
        }

        return new Response($this->twig->render('trick/tricks-form.html.twig', $args));
    }
}
