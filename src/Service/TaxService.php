<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Interface\TaxProviderInterface;
use Psr\Cache\CacheItemPoolInterface;
class TaxService
{
    public function __construct(
        public TaxProviderInterface $taxProvider,   
        private readonly CacheItemPoolInterface $cacheو
    ) {}

    public function getTaxes(string $country, ?string $state): array
    {
        $supportedCountries = ['US', 'CA', 'LT', 'LV', 'EE', 'PL', 'DE'];

        if (!in_array($country, $supportedCountries)) {
            throw new \Exception("This country is not supported", 400);
        }
       /*  
        $cacheKey = "taxes_{$country}_{$state}";
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
 */ 
        $taxes = [];

        if (in_array($country, ['LT', 'LV', 'EE', 'PL', 'DE'])) {
            try {
            $taxes = $this->taxProvider->getTaxes($country, $state);
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        } elseif (in_array($country, ['US', 'CA'])) {
            try {
                
              $taxes =  $this->taxProvider->getTaxes($country, $state);
                
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
        }

       /*  $cacheItem->set($taxes);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);
 */
        return $taxes;
    }
}