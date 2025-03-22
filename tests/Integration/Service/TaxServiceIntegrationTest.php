<?php

namespace App\Tests\Integration;

use App\Service\TaxService;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxServiceIntegrationTest extends KernelTestCase
{
    private TaxService $taxService;
    private CacheItemPoolInterface $cache;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->taxService = $container->get(TaxService::class);
        $this->cache = $container->get('cache.app'); 

        $this->cache->clear();
    }
    public function testGetTaxesCachesResults()
    {
        $cacheItem = $this->cache->getItem('taxes_US_CA');
        $this->assertFalse($cacheItem->isHit(), 'Cache should be empty before the first request');

        $this->taxService->getTaxes('US', 'CA');

        $cacheItem = $this->cache->getItem('taxes_US_CA');
        $this->assertTrue($cacheItem->isHit(), 'Cache should store the result after the first request');
    }
}
