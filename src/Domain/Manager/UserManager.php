<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 3:45 PM.
 */

namespace App\Domain\Manager;

use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
use App\Domain\Notifier\MailNotifier;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager
{
    private $validator;
    private $doctrine;
    private $flashBag;
    private $notifier;

    /**
     * TrickManager constructor.
     *
     * @param ValidatorInterface $validator
     * @param ManagerRegistry    $doctrine
     * @param FlashBagInterface  $flashBag
     * @param MailNotifier       $notifier
     */
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, FlashBagInterface $flashBag, MailNotifier $notifier)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->flashBag = $flashBag;
        $this->notifier = $notifier;
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function save(User $user)
    {
        if (count($errors = $this->validator->validate($user))) {
            throw new ValidationException($errors);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @param $user
     * @throws ValidationException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register($user)
    {
        $this->save($user);

        $this->notifier->notifyRegistration($user);
    }

    /**
     * @param $user
     *
     * @throws ValidationException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function forgotPassword($user)
    {
        if ($user instanceof User) {
            $this->save($user);
            $this->notifier->notifyResetPassword($user);
        }

        $this->flashBag->add('success', 'Un email vous a été envoyé avec un lien de réinitialisation de mot de passe.');
    }

    /**
     * @param User $user
     * @throws ValidationException
     */
    public function update(User $user)
    {
        $this->save($user);
        $this->flashBag->add('success', 'Votre compte a bien été modifié !');
    }
}
