<?php

declare(strict_types=1);

namespace App\Service\ExternalSystemManager;

use Symfony\Component\Validator\Constraints as Assert;

class RequestDto
{
    #[Assert\NotBlank(message: "Amount is required.")]
    #[Assert\Positive(message: "Amount must be a positive number.")]
    #[Assert\Type(type: 'numeric', message: "Amount must be a number.")]
    public ?string $amount;

    #[Assert\NotBlank(message: "Currency is required.")]
    #[Assert\Currency(message: "Invalid currency format.")]
    public ?string $currency;

    #[Assert\NotBlank(message: "Card number is required.")]
    #[Assert\Regex("/^\d{16}$/", message: "Card number must be 16 digits.")]
    public ?string $cardNumber;

    #[Assert\NotBlank(message: "Card expiration year is required.")]
    #[Assert\Regex("/^\d{4}$/", message: "Card expiration year must be a 4-digit number.")]
    #[Assert\Type(type: 'numeric', message: "Card expiration year must be a number.")]
    public ?string $cardExpYear;

    #[Assert\NotBlank(message: "Card expiration month is required.")]
    #[Assert\Range(min: 1, max: 12, notInRangeMessage: "Card expiration month must be between 1 and 12.")]
    #[Assert\Type(type: 'numeric', message: "Card expiration month must be a number.")]
    public ?string $cardExpMonth;

    #[Assert\NotBlank(message: "Card CVV is required.")]
    #[Assert\Regex("/^\d{3,4}$/", message: "Card CVV must be 3 or 4 digits.")]
    public ?string $cardCvv;
}