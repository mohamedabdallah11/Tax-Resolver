<?php 
namespace App\Adapter\Implementation;

use App\ExternalService\TaxBee\TaxBee;
use App\Adapter\Interface\TaxProviderInterface;


class TaxBeeAdapter implements TaxProviderInterface

{       
    private TaxBee $taxBee;
    public function __construct(TaxBee $taxBee)
    {
        $this->taxBee = $taxBee;
    }
    public function getTaxes(string $country, ?string $state): array
    {
        $results = $this->taxBee->getTaxes($country, $state, '', '', '');
        $taxes = [];
        foreach ($results as $result) {
            $taxes[] = [
                'taxType' => $result->type->value,
                'percentage' => $result->taxPercentage
                ];
        }  
        return $taxes;

      }
}