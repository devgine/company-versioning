<?php

namespace App\Tests\Unit\Formatter;

use App\Formatter\ViolationFormatter;
use App\Model\Violation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ViolationFormatterTest extends TestCase
{
    /** @dataProvider providerViolations */
    public function testFormat($input, $expectedResult)
    {
        $this->assertEquals($expectedResult, (new ViolationFormatter())->format(new ConstraintViolationList($input)));
    }

    public static function providerViolations(): array
    {
        return [
            [
                [],
                [],
            ],
            [
                [
                    new ConstraintViolation(
                        'The email could not be blank',
                        null,
                        [],
                        '',
                        'email',
                        '',
                        null,
                        'code#1'),
                ],
                [
                    new Violation(
                        message: 'The email could not be blank',
                        property: 'email',
                        value: ''
                    ),
                ],
            ],
            [
                [
                    new ConstraintViolation(
                        'The email is not valid',
                        null,
                        [],
                        '',
                        'email',
                        'test',
                        null,
                        'code#2'
                    ),
                    new ConstraintViolation(
                        'The password could not be blank',
                        null,
                        [],
                        '',
                        'password',
                        '',
                        null,
                        'code#3'
                    ),
                ],
                [
                    new Violation(
                        message: 'The email is not valid',
                        property: 'email',
                        value: 'test'
                    ),
                    new Violation(
                        message: 'The password could not be blank',
                        property: 'password',
                        value: ''
                    ),
                ],
            ],
        ];
    }
}
