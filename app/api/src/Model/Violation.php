<?php

namespace App\Model;

class Violation
{
    public function __construct(
        public ?string $message = null,
        public ?string $property = null,
        public mixed $value = null,
    ) {
    }
}
