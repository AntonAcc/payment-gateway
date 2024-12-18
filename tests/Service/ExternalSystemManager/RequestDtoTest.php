<?php

/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace App\Tests\Service\ExternalSystemManager;

use App\Service\ExternalSystemManager\RequestDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RequestDtoTest extends KernelTestCase
{
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = self::getContainer()->get(ValidatorInterface::class);
    }

    public function testAmountIsBlank()
    {
        $requestDto = $this->getValidRequestDto();

        $requestDto->amount = null;

        $errors = $this->validator->validate($requestDto);
        $this->assertEquals(1, $errors->count());
    }

    public function testAmountIsNegative()
    {
        $requestDto = $this->getValidRequestDto();

        $requestDto->amount = '-123.45';

        $errors = $this->validator->validate($requestDto);
        $this->assertEquals(1, $errors->count());
    }

    public function testAmountIsNotNumber()
    {
        $requestDto = $this->getValidRequestDto();

        $requestDto->amount = 'some string';

        $errors = $this->validator->validate($requestDto);
        $this->assertEquals(1, $errors->count());
    }

    public function testCurrencyIsBlank()
    {
        $requestDto = $this->getValidRequestDto();

        $requestDto->currency = null;

        $errors = $this->validator->validate($requestDto);
        $this->assertEquals(1, $errors->count());
    }

    public function testCurrencyWrongFormat()
    {
        $requestDto = $this->getValidRequestDto();

        $requestDto->currency = 'NOT_ISO_CODE';

        $errors = $this->validator->validate($requestDto);
        $this->assertEquals(1, $errors->count());
    }

    // todo: write other tests

    private function getValidRequestDto(): RequestDto
    {
        $requestDto = new RequestDto();
        $requestDto->amount = '100.00';
        $requestDto->currency = 'USD';
        $requestDto->cardNumber = '4111111111111111';
        $requestDto->cardExpYear = '2025';
        $requestDto->cardExpMonth = '12';
        $requestDto->cardCvv = '123';

        return $requestDto;
    }
}

