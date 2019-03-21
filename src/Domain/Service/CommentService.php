<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/17/19
 * Time: 11:25 AM.
 */

namespace App\Domain\Service;

use App\Domain\Entity\Comment;
use App\Domain\Exception\ValidationException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentService
{
    private $validator;
    private $doctrine;

    /**
     * CommentService constructor.
     *
     * @param ValidatorInterface $validator
     * @param ManagerRegistry    $doctrine
     */
    public function __construct(ValidatorInterface $validator, ManagerRegistry $doctrine)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    /**
     * @param Comment $comment
     * @throws ValidationException
     */
    public function save(Comment $comment)
    {
        if (count($errors = $this->validator->validate($comment))) {
            throw new ValidationException($errors);
        }

        $manager = $this->doctrine->getManager();
        $manager->persist($comment);
        $manager->flush();
    }
}
