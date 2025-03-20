<?php

declare(strict_types=1);

namespace App\Service;

use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\SeriousTax\Location;
use App\ExternalService\TaxBee\TaxBee;
use Psr\Cache\CacheItemPoolInterface;
class TaxService
{
    public function __construct(
        private readonly SeriousTaxService $seriousTaxService,
        private readonly TaxBee $taxBee,
        private readonly CacheItemPoolInterface $cache
    ) {
    }

    public function getTaxes(string $country, ?string $state): array
    {
        $supportedCountries = ['US', 'CA', 'LT', 'LV', 'EE', 'PL', 'DE'];

        if (!in_array($country, $supportedCountries)) {
            throw new \Exception("This country is not supported", 400);
        }
        
        $cacheKey = "taxes_{$country}_{$state}";
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $taxes = [];

        if (in_array($country, ['LT', 'LV', 'EE', 'PL', 'DE'])) {
            try {
                $taxRate = $this->seriousTaxService->getTaxesResult(new Location($country, $state));
                $taxes[] = ['taxType' => 'VAT', 'percentage' => $taxRate];
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        } elseif (in_array($country, ['US', 'CA'])) {
            try {
                $results = $this->taxBee->getTaxes($country, strtolower($state ?? ''), '', '', '');
                foreach ($results as $result) {
                    $taxes[] = ['taxType' => $result->type->value, 'percentage' => $result->taxPercentage];
                }
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

        $cacheItem->set($taxes);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);

        return $taxes;
    }
}