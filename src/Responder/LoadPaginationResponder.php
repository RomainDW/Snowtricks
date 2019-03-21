<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 7:35 PM.
 */

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoadPaginationResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * EditTrickResponder constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param array $args
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(array $args): Response
    {
        return new Response($this->twig->render('homepage/_partials/ajax-content.html.twig', $args));
    }
}