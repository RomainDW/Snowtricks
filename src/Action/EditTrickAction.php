<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/6/19
 * Time: 7:26 PM.
 */

namespace App\Action;

use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Trick;
use App\Form\TrickFormType;
use App\Handler\FormHandler\EditTrickFormHandler;
use App\Domain\Service\TrickService;
use App\Responder\Interfaces\TwigResponderInterface;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var TrickService
     */
    private $trickService;
    /**
     * @var EditTrickFormHandler
     */
    private $formHandler;

    /**
     * EditTrickAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TrickService         $trickService
     * @param EditTrickFormHandler $formHandler
     */
    public function __construct(FormFactoryInterface $formFactory, TrickService $trickService, EditTrickFormHandler $formHandler)
    {
        $this->formFactory = $formFactory;
        $this->trickService = $trickService;
        $this->formHandler = $formHandler;
    }

    /**
     * @Route("/trick/edit/{slug}", name="app_edit_trick")
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
