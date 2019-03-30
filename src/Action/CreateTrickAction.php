<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Action;

use App\Domain\Service\TrickService;
use App\Form\TrickFormType;
use App\Handler\FormHandler\CreateTrickFormHandler;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreateTrickAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var CreateTrickFormHandler
     */
    private $formHandler;
    /**
     * @var TrickService
     */
    private $trickService;

    /**
     * CreateTrickAction constructor.
     *
     * @param FormFactoryInterface   $formFactory
     * @param Security               $security
     * @param CreateTrickFormHandler $formHandler
     * @param TrickService           $trickService
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, CreateTrickFormHandler $formHandler, TrickService $trickService)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
        $this->formHandler = $formHandler;
        $this->trickService = $trickService;
    }

    /**
     * @param Request                $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     *
     * @throws \Exception
     * @Route("/trick/add", name="app_create_trick")
     */
    public function __invoke(Request $request, TwigResponderInterface $responder)
    {
        $form = $this->formFactory->create(TrickFormType::class);
        $form->handleRequest($request);

        if ($this->formHandler->handle($form, $this->security)) {
            return $responder('app_show_trick', ['slug' => $this->trickService->getSlug()]);
        }

        return $responder('trick/tricks-form.html.twig', ['form' => $form->createView()]);
    }
}
