<?php

declare(strict_types=1);

namespace App\Service;

use App\Factory\TaxProviderFactory;
use Psr\Cache\CacheItemPoolInterface;
class TaxService
{
    public function __construct(
        public TaxProviderFactory $taxProvider,
        private readonly CacheItemPoolInterface $cache
    ) {}

    public function getTaxes(string $country, ?string $state): array
    {
         
        $cacheKey = "taxes_{$country}_{$state}";
        $cacheItem = $this->cache->getItem($cacheKey);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }
 
        $country = strtoupper($country);
        $state = strtolower($state);
            $provider = $this->taxProvider->getProvider($country);
                          
            $taxes = $provider->getTaxes($country, $state);
        

         $cacheItem->set($taxes);
        $cacheItem->expiresAfter(3600);
        $this->cache->save($cacheItem);

        return $taxes;
    }
}