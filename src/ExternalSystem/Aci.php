<?php

declare(strict_types=1);

namespace App\ExternalSystem;

use App\ExternalSystem\Enum as ExternalSystemEnum;
use App\Service\ExternalSystemManager\RequestDto;
use App\Service\ExternalSystemManager\Response;

class Aci implements AdapterInterface
{
    public function process(RequestDto $requestDto): Response
    {
        $responseData = $this->useRequestMannerFromDoc($requestDto);

        // todo: introduce AciResponse object for bettering maintainability
        $responseArray = json_decode($responseData, true);

        return new Response(
            $responseArray['id'],
            new \DateTime($responseArray['timestamp'])->getTimestamp(),
            // todo: implement ValueObject for Amount, Currency and correct dealing with minor units
            (float) $responseArray['amount'],
            $responseArray['currency'],
            $responseArray['card']['bin'],
            ExternalSystemEnum::ACI->value,
        );
    }

    private function useRequestMannerFromDoc(RequestDto $requestDto): string
    {
        // see: https://docs.oppwa.com/integrations/server-to-server

        $url = "https://eu-test.oppwa.com/v1/payments";
        $data = "entityId=8ac7a4c79394bdc801939736f17e063d" .
            sprintf("&amount=%s", $requestDto->amount) .
            sprintf("&currency=%s", $requestDto->currency) .
            "&paymentBrand=VISA" .
            "&paymentType=DB" .
            sprintf("&card.number=%s", $requestDto->cardNumber) .
            "&card.holder=Jane Jones" .
            sprintf("&card.expiryMonth=%s", $requestDto->cardExpMonth) .
            sprintf("&card.expiryYear=%s", $requestDto->cardExpYear) .
            sprintf("&card.cvv=%s", $requestDto->cardCvv);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8Ulh5az9pd2ZNdXprRVpRYjdFcWs='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            throw new Exception(sprintf('oppwa request error: %s', curl_error($ch)));
        }
        curl_close($ch);

        return $responseData;
    }
}
