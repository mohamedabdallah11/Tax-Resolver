<?php 
namespace App\Service\Implementation;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\Service\Interface\TaxProviderInterface;
use App\ExternalService\SeriousTax\Location;
class SeriousTaxAdapter implements TaxProviderInterface
{   
    private SeriousTaxService $seriousTax;
    public function __construct(SeriousTaxService $seriousTax)
    {
        $this->seriousTax = $seriousTax;
    }
    public function getTaxes(string $country, ?string $state): array
    {   
        $result= $this->seriousTax->getTaxesResult(new Location($country, $state));
        return ['taxType' => 'VAT', 'percentage' => $result];
    }
}