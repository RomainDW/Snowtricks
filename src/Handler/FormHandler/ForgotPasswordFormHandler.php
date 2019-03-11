<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 6:55 PM.
 */

namespace App\Handler\FormHandler;

use App\Repository\UserRepository;
use Swift_Mailer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordFormHandler
{
    private $userRepository;
    private $mailer;
    private $flashBag;
    private $url_generator;
    private $templating;

    /**
     * ForgotPasswordFormHandler constructor.
     *
     * @param UserRepository        $userRepository
     * @param Swift_Mailer          $mailer
     * @param FlashBagInterface     $flashBag
     * @param UrlGeneratorInterface $url_generator
     * @param \Twig_Environment     $templating
     */
    public function __construct(UserRepository $userRepository, Swift_Mailer $mailer, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, \Twig_Environment $templating)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->templating = $templating;
    }

    /**
     * @param FormInterface $form
     *
     * @return \App\Domain\Entity\User|bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if (null !== $user) {
                $vkey = md5(random_bytes(10));
                $user->setVkey($vkey);
            }

            return $user;
        }

        return false;
    }
}
