<?php

declare(strict_types=1);

namespace App\ExternalService\TaxBee;

final readonly class TaxResult
{
    public function __construct(
        public TaxResultType $type,
        public float         $taxPercentage
    )
    {
    }
}
