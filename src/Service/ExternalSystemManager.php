<?php

declare(strict_types=1);

namespace App\Service;

use App\ExternalSystem\Enum as ExternalSystemEnum;
use App\Service\ExternalSystemManager\RequestDto;
use App\Service\ExternalSystemManager\Response;

class ExternalSystemManager
{
    private array $availableExternalSystemIdList = [
        ExternalSystemEnum::SHIFT4->value,
        ExternalSystemEnum::OPPWA->value,
    ];

    public function isAvailable(string $systemId): bool
    {
        return in_array($systemId, $this->availableExternalSystemIdList, true);
    }

    public function getAvailableIdList(): array
    {
        return $this->availableExternalSystemIdList;
    }

    public function process(string $systemId, RequestDto $requestDto): Response
    {
        return new Response(
            uniqid('trx_', true),
            date('Y-m-d H:i:s'),
            $requestDto->amount,
            $requestDto->currency,
            substr($requestDto->cardNumber, 0, 6),
            $systemId
        );
    }
}
