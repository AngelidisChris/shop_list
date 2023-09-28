<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorService
{
    public static function buildErrorArray(ConstraintViolationList $violations):array {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}