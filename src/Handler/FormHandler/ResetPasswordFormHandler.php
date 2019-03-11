<?php
/**
 * Created by PhpStorm.
 * User: romain
 * Date: 3/4/19
 * Time: 7:05 PM.
 */

namespace App\Handler\FormHandler;

use App\Domain\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ResetPasswordFormHandler
{
    private $userRepository;
    private $flashBag;
    private $url_generator;
    private $passwordEncoder;
    private $security;

    /**
     * ResetPasswordFormHandler constructor.
     *
     * @param UserRepository               $userRepository
     * @param FlashBagInterface            $flashBag
     * @param UrlGeneratorInterface        $url_generator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security                     $security
     */
    public function __construct(UserRepository $userRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->flashBag = $flashBag;
        $this->url_generator = $url_generator;
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }

    /**
     * @param FormInterface $form
     * @param User          $user
     *
     * @return bool|RedirectResponse
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid() && $user->exist($form->get('email')->getData())) {
            $password = $this->passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData());
            $user->newPassword($password);
            $user->eraseCredentials();

            return true;
        }

        return false;
    }
}
