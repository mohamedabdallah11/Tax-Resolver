<?php

declare(strict_types=1);

namespace App\ExternalService\SeriousTax;

final class SeriousTaxService
{
    /**
     * @throws TimeoutException
     */
    public function getTaxesResult(Location $address): float
    {
        // it returns only VAT taxes because it is europe
        if ($address->country == 'PL') {
            throw new TimeoutException("could not retrieve");
        }

        $taxes = [
            'LT' => 21.0,
            'LV' => 22.0,
            'EE' => 20.0,
        ];

        return $taxes[$address->country] ?? 19.0;
    }
}
