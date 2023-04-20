<?php

namespace App\Formatter;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationFormatter
{
    public function format(ConstraintViolationListInterface $violations): ?array
    {
        $message = [];

        foreach ($violations as $violation) {
            $message[] = [
                'message' => $violation->getMessage(),
                'property' => $violation->getPropertyPath(),
                'value' => $violation->getInvalidValue(),
            ];
        }

        return $message;
    }
}
