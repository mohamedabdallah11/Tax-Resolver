<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\TaxProviderFactory;
class TaxService
{
    public function __construct(
        public TaxProviderFactory $taxProvider,
    ) {}

    public function getTaxes(string $country, ?string $state): array
    {
      
 

            $provider = $this->taxProvider->getProvider($country);
                          
            $taxes = $provider->getTaxes($country, $state);
        


        return $taxes;
    }
}