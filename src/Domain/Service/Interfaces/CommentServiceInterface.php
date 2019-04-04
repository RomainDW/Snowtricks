<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/30/19
 * Time: 6:37 PM.
 */

namespace App\Domain\Service\Interfaces;

use App\Domain\Entity\Interfaces\CommentInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface CommentServiceInterface
{
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine);

    public function save(CommentInterface $user);
}
