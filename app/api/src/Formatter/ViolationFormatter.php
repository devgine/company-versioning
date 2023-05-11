<?php

namespace App\Formatter;

use App\Model\Violation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationFormatter
{
    /** @psalm-return array<Violation> */
    public function format(ConstraintViolationListInterface $violations): array
    {
        $message = [];

        foreach ($violations as $violation) {
            $message[] = new Violation(
                message: $violation->getMessage(),
                property: $violation->getPropertyPath(),
                value: $violation->getInvalidValue()
            );
        }

        return $message;
    }
}
