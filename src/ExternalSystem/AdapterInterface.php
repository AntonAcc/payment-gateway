<?php

declare(strict_types=1);

namespace App\ExternalSystem;

use App\Service\ExternalSystemManager\RequestDto;
use App\Service\ExternalSystemManager\Response;

interface AdapterInterface
{
    public function process(RequestDto $requestDto): Response;
}
