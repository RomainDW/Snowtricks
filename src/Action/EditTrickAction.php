<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Action;

use App\Action\Interfaces\EditTrickInterface;
use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Trick;
use App\Form\TrickFormType;
use App\Handler\FormHandler\Interfaces\EditTrickFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickAction implements EditTrickInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EditTrickFormHandlerInterface
     */
    private $formHandler;

    /**
     * EditTrickAction constructor.
     *
     * @param FormFactoryInterface          $formFactory
     * @param EditTrickFormHandlerInterface $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, EditTrickFormHandlerInterface $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/figure/edition/{slug}", name="app_edit_trick")
     *
     * @param Trick                  $trick
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     *
     * @throws Exception
     */
    public function __invoke(Trick $trick, Request $request, TwigResponderInterface $responder)
    {
        $form = $this->formFactory->create(TrickFormType::class, CreateTrickDTO::createFromTrick($trick));
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $trick)) {
            return $responder('app_edit_trick', ['slug' => $trick->getSlug()]);
        }

        return $responder('trick/tricks-form.html.twig', ['trick' => $trick, 'form' => $form->createView()]);
    }
}
