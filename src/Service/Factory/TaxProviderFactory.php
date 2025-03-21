<?php
namespace App\Service\Factory;

use App\Service\Implementation\SeriousTaxAdapter;
use App\Service\Implementation\TaxBeeAdapter;
use App\Service\Interface\TaxProviderInterface;
class TaxProviderFactory
{
   
    public function __construct(
        private TaxBeeAdapter $taxBeeAdapter,
        private SeriousTaxAdapter $seriousTaxAdapter,
    ) {
        
    }


    public function getProvider(string $country): TaxProviderInterface
    {
        $supportedCountries = ['US', 'CA', 'LT', 'LV', 'EE', 'PL', 'DE'];

        if (!in_array($country, $supportedCountries)) {
            throw new \Exception("This country is not supported", 400);
        }
        if (in_array($country, ['LT', 'LV', 'EE', 'PL', 'DE'])) {
            return $this->seriousTaxAdapter;

        }
        elseif (in_array($country, ['US', 'CA'])) {
             return $this->taxBeeAdapter;

        }
   
        throw new \InvalidArgumentException("No tax provider available for country: $country");

    }
}