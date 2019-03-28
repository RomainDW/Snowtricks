<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/17/19
 * Time: 11:29 AM.
 */

namespace App\Domain\Service;

use App\Domain\Entity\User;
use App\Domain\Exception\ValidationException;
use App\Domain\Notifier\MailNotifier;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $validator;
    private $doctrine;
    private $flashBag;
    private $notifier;
    private $userId;

    /**
     * UserService constructor.
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
     *
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
     *
     * @throws ValidationException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function register(User $user)
    {
        $this->save($user);

        $this->userId = $user->getId();

        $this->notifier->notifyRegistration($user);
    }

    /**
     * @param User $user
     *
     * @throws ValidationException
     */
    public function update(User $user)
    {
        $this->save($user);
        $this->flashBag->add('success', 'Votre compte a bien été modifié !');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function verification(User $user)
    {
        $hasAccess = $user->hasRole('ROLE_USER_NOT_VERIFIED');

        if (!$hasAccess) {
            return false;
        } else {
            $user->updateRole(['ROLE_USER']);
            $user->eraseCredentials();
            $manager = $this->doctrine->getManager();
            $manager->persist($user);
            $manager->flush();
            return true;
        }
    }

    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $email
     * @throws \Exception
     */
    public function forgotPassword(string $email)
    {
        $manager = $this->doctrine->getManager();
        $user = $manager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null !== $user) {
            $vkey = md5(random_bytes(10));
            $user->setVkey($vkey);
        }

        if ($user instanceof User) {
            $this->save($user);
            $this->notifier->notifyResetPassword($user);
        }

        $this->flashBag->add('success', 'Un email vous a été envoyé avec un lien de réinitialisation de mot de passe.');

    }
}
