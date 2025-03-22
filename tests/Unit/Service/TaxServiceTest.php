<?php

namespace App\Tests\Unit\Service;

use App\Service\TaxService;
use App\Factory\TaxProviderFactory;
use App\Adapter\Interface\TaxProviderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class TaxServiceTest extends TestCase
{
    private TaxProviderFactory $taxProviderFactory;
    private CacheItemPoolInterface $cache;
    private TaxService $taxService;
    private CacheItemInterface $cacheItem;
    private TaxProviderInterface $provider;

    protected function setUp(): void
    {
        $this->taxProviderFactory = $this->createMock(TaxProviderFactory::class);
        $this->cache = $this->createMock(CacheItemPoolInterface::class);
        $this->cacheItem = $this->createMock(CacheItemInterface::class);
        $this->provider = $this->createMock(TaxProviderInterface::class);

        $this->taxService = new TaxService($this->taxProviderFactory, $this->cache);
    }

    public function testGetTaxesReturnsCachedValue()
    {
        $this->cacheItem->method('isHit')->willReturn(true);
        $this->cacheItem->method('get')->willReturn(['vat' => 15]);
        $this->cache->method('getItem')->willReturn($this->cacheItem);

        $this->taxProviderFactory->expects($this->never())->method('getProvider');
        $this->provider->expects($this->never())->method('getTaxes');

        $result = $this->taxService->getTaxes('US', 'CA');
        $this->assertSame(['vat' => 15], $result);
    }

    public function testGetTaxesFetchesAndCachesResultIfNotCached()
    {
        $this->cacheItem->method('isHit')->willReturn(false);
        $this->cache->method('getItem')->willReturn($this->cacheItem);
        $this->taxProviderFactory->method('getProvider')->willReturn($this->provider);
        $this->provider->method('getTaxes')->willReturn(['vat' => 20]);

        $this->cacheItem->expects($this->once())->method('set')->with(['vat' => 20]);
        $this->cacheItem->expects($this->once())->method('expiresAfter')->with(3600);
        $this->cache->expects($this->once())->method('save')->with($this->cacheItem);

        $result = $this->taxService->getTaxes('DE', 'BW');
        $this->assertSame(['vat' => 20], $result);
    }

    public function testRedisCacheStoresAndRetrievesData()
    {
        $this->cacheItem->method('isHit')->willReturn(false);
        $this->cache->method('getItem')->willReturn($this->cacheItem);
        
        $this->cacheItem->expects($this->once())->method('set')->with(['vat' => 25]);
        $this->cacheItem->expects($this->once())->method('expiresAfter')->with(3600);
        $this->cache->expects($this->once())->method('save')->with($this->cacheItem);

        $this->taxProviderFactory->method('getProvider')->willReturn($this->provider);
        $this->provider->method('getTaxes')->willReturn(['vat' => 25]);

        $result = $this->taxService->getTaxes('FR', 'IDF');
        $this->assertSame(['vat' => 25], $result);
    }

    public function testGetTaxesCachesResultAndRetrievesFromCache()
    {
        $this->cacheItem
            ->method('isHit')
            ->willReturnOnConsecutiveCalls(false, true);

        $this->cache->method('getItem')->willReturn($this->cacheItem);
        
        $this->taxProviderFactory->method('getProvider')->willReturn($this->provider);
        $this->provider->method('getTaxes')->willReturn(['vat' => 18]);

        $this->cacheItem->expects($this->once())->method('set')->with(['vat' => 18]);
        $this->cacheItem->expects($this->once())->method('expiresAfter')->with(3600);
        $this->cache->expects($this->once())->method('save')->with($this->cacheItem);

        $result = $this->taxService->getTaxes('IT', 'RM');
        $this->assertSame(['vat' => 18], $result);

        $this->cacheItem->method('get')->willReturn(['vat' => 18]); 

        $this->taxProviderFactory->expects($this->never())->method('getProvider');
        $this->provider->expects($this->never())->method('getTaxes');

        $cachedResult = $this->taxService->getTaxes('IT', 'RM');
        $this->assertSame(['vat' => 18], $cachedResult);
    }
}