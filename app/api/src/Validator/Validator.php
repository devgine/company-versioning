<?php

/*
 * This file is part of a Upply project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @psalm-param Constraint[] $constraints
     * @psalm-param int[]|string[]|GroupSequence[] $groups
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
