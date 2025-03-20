<?php

declare(strict_types=1);

namespace App\ExternalService\SeriousTax;

readonly class Location
{
    public function __construct(public string $country, public ?string $state)
    {
    }
}
