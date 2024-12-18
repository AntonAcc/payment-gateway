<?php

declare(strict_types=1);

namespace App\Service;

use App\ExternalSystem\Enum as ExternalSystemEnum;
use App\ExternalSystem\Shift4;
use App\Service\ExternalSystemManager\RequestDto;
use App\Service\ExternalSystemManager\Response;

class ExternalSystemManager
{
    private array $availableExternalSystemIdList = [
        ExternalSystemEnum::SHIFT4->value,
        ExternalSystemEnum::ACI->value,
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
        return match ($systemId) {
            ExternalSystemEnum::SHIFT4->value => new Shift4()->process($requestDto),
            default => new Response(
                uniqid('trx_', true),
                time(),
                (float) $requestDto->amount,
                $requestDto->currency,
                substr($requestDto->cardNumber, 0, 6),
                $systemId
            ),
        };
    }
}
