<?php
/**
 * Created by Romain Ollier.
 * Project: Snowtricks
 * Date: 3/9/19
 * Time: 11:15 AM.
 */

namespace App\Domain\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    private $constraintViolationList;

    /**
     * ValidationException constructor.
     * @param $constraintViolationList
     */
    public function __construct(ConstraintViolationListInterface $constraintViolationList)
    {
        parent::__construct();
        $this->constraintViolationList = $constraintViolationList;
    }

    public function getConstraintViolationList()
    {
        return $this->constraintViolationList;
    }
}
