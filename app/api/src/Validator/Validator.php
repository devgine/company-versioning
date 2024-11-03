<?php

declare(strict_types=1);

namespace App\Validator;

use App\Formatter\ViolationFormatter;
use App\Model\Violation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    public function __construct(
        protected ValidatorInterface $validator,
        protected ViolationFormatter $violationFormatter
    ) {
    }

    /**
     * @psalm-param array<Constraint> $constraints
     * @psalm-param array<string|GroupSequence> $groups
     *
     * @psalm-return Violation[]
     */
    public function validate(
        mixed $object,
        array|Constraint|null $constraints = null,
        array|string|GroupSequence|null $groups = null
    ): ?array {
        $constraintViolationList = $this->validator->validate($object, $constraints, $groups);

        if ($constraintViolationList->count() > 0) {
            return $this->violationFormatter->format($constraintViolationList);
        }

        return null;
    }
}
