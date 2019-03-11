<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 10:17 PM.
 */

namespace App\Domain\Manager;

use App\Domain\Entity\Comment;
use App\Domain\Exception\ValidationException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentManager
{
    private $validator;
    private $doctrine;

    /**
     * TrickManager constructor.
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
