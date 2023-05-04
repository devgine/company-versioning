<?php

namespace App\Tests\Unit\Validator;

use App\Entity\Company;
use App\Formatter\ViolationFormatter;
use App\Model\Violation;
use App\Validator\Validator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    private ValidatorInterface|MockObject $validatorInterface;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        $this->validatorInterface = $this->createMock(ValidatorInterface::class);
        $this->validator = new Validator($this->validatorInterface, new ViolationFormatter());
    }

    /** @dataProvider providerObjects */
    public function testValidate($constraintViolationList, $expectedResult)
    {
        $this->validatorInterface
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList($constraintViolationList));

        $this->assertEquals($expectedResult, $this->validator->validate(new Company()));
    }

    public static function providerObjects(): array
    {
        return [
            [
                [],
                null,
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
                        'code#1'
                    ),
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
                        [], '',
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
