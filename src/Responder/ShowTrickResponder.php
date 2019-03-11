<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/7/19
 * Time: 8:45 PM.
 */

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ShowTrickResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * AccountResponder constructor.
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
    public function __invoke(array $args)
    {
        return new Response($this->twig->render('trick/show.html.twig', $args));
    }
}
