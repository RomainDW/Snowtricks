<?php
/**
 * Created by PhpStorm.
 * User: saysa
 * Date: 2019-03-07
 * Time: 15:26.
 */

namespace App\Responder;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class CreateTrickResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * CreateTrickResponder constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(FormInterface $form)
    {
        return new Response($this->twig->render('trick/tricks-form.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
