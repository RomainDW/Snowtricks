<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 4/4/19
 * Time: 10:24 AM.
 */

namespace App\Handler\FormHandler\Interfaces;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Service\Interfaces\TrickServiceInterface;
use App\Repository\TrickRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface EditTrickFormHandlerInterface
{
    public function __construct(TrickServiceInterface $trickService, TrickRepository $trickRepository, FlashBagInterface $flashBag, UrlGeneratorInterface $url_generator, ValidatorInterface $validator);

    public function handle(FormInterface $form, TrickInterface $trick);
}
