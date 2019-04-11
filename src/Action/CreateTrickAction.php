<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 2/2/19
 * Time: 12:00 PM.
 */

namespace App\Action;

use App\Action\Interfaces\CreateTrickActionInterface;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Form\TrickFormType;
use App\Handler\FormHandler\Interfaces\CreateTrickFormHandlerInterface;
use App\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreateTrickAction implements CreateTrickActionInterface
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
     * @var CreateTrickFormHandlerInterface
     */
    private $formHandler;
    /**
     * @var TrickServiceInterface
     */
    private $trickService;

    /**
     * CreateTrickAction constructor.
     *
     * @param FormFactoryInterface            $formFactory
     * @param Security                        $security
     * @param CreateTrickFormHandlerInterface $formHandler
     * @param TrickServiceInterface           $trickService
     */
    public function __construct(FormFactoryInterface $formFactory, Security $security, CreateTrickFormHandlerInterface $formHandler, TrickServiceInterface $trickService)
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
     * @Route("/figure/creation", name="app_create_trick")
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
