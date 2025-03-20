<?php

declare(strict_types=1);

namespace App\ExternalService\TaxBee;

// use for USA and Canada
use Exception;

final class TaxBee
{
    /**
     * @param string $country
     * @param string $state
     * @param string $city
     * @param string $street
     * @param string $postcode
     * @return TaxResult[]
     * @throws TaxBeeException
     */
    public function getTaxes(string $country, string $state, string $city, string $street, string $postcode): array
    {
        if ($country != 'US' && $country != 'CA') {
            throw new TaxBeeException("This country is not supported");
        }

        $allTaxRates = $this->getAllTaxRatesForStates();

        $state = mb_strtolower($state);

        if (!isset($allTaxRates[$country][$state])) {
            return [ new TaxResult(TaxResultType::VAT, 20.0) ];
        }

        return $allTaxRates[$country][$state];
    }

    private function getAllTaxRatesForStates(): array
    {
        return [
            'CA' => [
                'quebec' => [
                    new TaxResult(TaxResultType::GST_HST, 5.0),
                    new TaxResult(TaxResultType::PST, 9.975),
                ],
                'ontario' => [
                    new TaxResult(TaxResultType::GST_HST, 13),
                ],
            ],
            'US' => [
                'california' => [
                    new TaxResult(TaxResultType::VAT, 7.25),
                ]
            ]
        ];
    }
}
