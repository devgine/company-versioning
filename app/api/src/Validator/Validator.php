<?php

/*
 * This file is part of a Upply project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Validator;

use App\Formatter\ViolationFormatter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{

    public function __construct(
        protected ValidatorInterface $validator,
        protected ViolationFormatter $violationFormatter
    ) {
    }

    public function validate($object, $constraints = null, $groups = null): ?array
    {
        $constraintViolationList = $this->validator->validate($object, $constraints, $groups);

        if ($constraintViolationList->count() > 0) {
            return $this->violationFormatter->format($constraintViolationList);
        }

        return null;
    }
}
