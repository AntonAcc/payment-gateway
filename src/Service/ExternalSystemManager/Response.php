<?php

declare(strict_types=1);

namespace App\Service\ExternalSystemManager;

readonly class Response
{
    public function __construct(
        private string $transactionId,
        private string $dateOfCreation,
        private string $amount,
        private string $currency,
        private string $cardBin,
        private string $system
    ) {}

    public function toArray(): array
    {
        return [
            'transaction_id' => $this->transactionId,
            'date_of_creation' => $this->dateOfCreation,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'card_bin' => $this->cardBin,
            'system' => $this->system,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
