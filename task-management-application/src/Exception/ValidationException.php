<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \Exception
{
    private ConstraintViolationList $violations;

    public function __construct(ConstraintViolationList $violations)
    {
        parent::__construct('Validation failed');
        $this->violations = $violations;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }
}
