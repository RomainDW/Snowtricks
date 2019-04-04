<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:20 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Service\Interfaces\TrickServiceInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface CreateTrickFormHandlerInterface
{
    public function __construct(TrickServiceInterface $trickService, ValidatorInterface $validator, FlashBagInterface $flashBag);

    public function handle(FormInterface $form, Security $security): bool;
}
