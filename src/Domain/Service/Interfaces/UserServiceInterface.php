<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:53 PM.
 */

namespace App\Domain\Service\Interfaces;

use App\Domain\Entity\Interfaces\UserInterface;
use App\Domain\Notifier\MailNotifier;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface UserServiceInterface
{
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine, FlashBagInterface $flashBag, MailNotifier $notifier);

    public function save(UserInterface $user);

    public function register(UserInterface $user);

    public function update(UserInterface $user);

    public function verification(UserInterface $user): bool;

    public function forgotPassword(string $email);

    public function getUserId();
}
