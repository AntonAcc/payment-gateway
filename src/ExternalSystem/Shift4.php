<?php

declare(strict_types=1);

namespace App\ExternalSystem;

use App\ExternalSystem\Enum as ExternalSystemEnum;
use App\Service\ExternalSystemManager\RequestDto;
use App\Service\ExternalSystemManager\Response;
use Shift4\Shift4Gateway;
use Shift4\Exception\Shift4Exception;

class Shift4 implements AdapterInterface
{
    public function process(RequestDto $requestDto): Response
    {
        $gateway = new Shift4Gateway('pr_test_tXHm9qV9qV9bjIRHcQr9PLPa');

        $request = [
            // todo: implement ValueObject for Amount, Currency and correct dealing with minor units
            'amount' => $requestDto->amount * 100,
            'currency' => $requestDto->currency,
            'card' => [
                'number' => $requestDto->cardNumber,
                'expMonth' => $requestDto->cardExpMonth,
                'expYear' => $requestDto->cardExpYear
            ]
        ];

        try {
            $charge = $gateway->createCharge($request);

            // do something with charge object - see https://dev.shift4.com/docs/api#charge-object
            return new Response(
                $charge->getId(),
                $charge->getCreated(),
                // todo: implement ValueObject for Amount, Currency and correct dealing with minor units
                $charge->getAmount() / 100,
                $charge->getCurrency(),
                $charge->getCard()->getFirst6(),
                ExternalSystemEnum::SHIFT4->value,
            );
        } catch (Shift4Exception $e) {
            // handle error response - see https://dev.shift4.com/docs/api#error-object
            $errorMessage = sprintf(
                'Shift4 error: %s; Type: %s; Code: %s',
                $e->getMessage(),
                $e->getType(),
                $e->getCode(),
            );
            throw new Exception($errorMessage);
        }
    }
}
