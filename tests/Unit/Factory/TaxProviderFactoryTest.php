<?php
namespace App\tests\Unit\Factory;
use App\Factory\TaxProviderFactory;
use App\Adapter\Implementation\SeriousTaxAdapter;
use App\Adapter\Implementation\TaxBeeAdapter;
use App\Adapter\Interface\TaxProviderInterface;
use PHPUnit\Framework\TestCase;

class TaxProviderFactoryTest extends TestCase
{
    private TaxBeeAdapter $taxBeeAdapter;
    private SeriousTaxAdapter $seriousTaxAdapter;
    private TaxProviderFactory $factory;

    protected function setUp(): void
    {
        $this->taxBeeAdapter = $this->createMock(TaxBeeAdapter::class);
        $this->seriousTaxAdapter = $this->createMock(SeriousTaxAdapter::class);

        $this->factory = new TaxProviderFactory(
            $this->taxBeeAdapter,
            $this->seriousTaxAdapter
        );
    }

    public function testGetProviderReturnsTaxBeeForUSTaxBeeCountries()
    {
        $provider = $this->factory->getProvider('US');
        $this->assertInstanceOf(TaxProviderInterface::class, $provider);
        $this->assertSame($this->taxBeeAdapter, $provider);
    }

    public function testGetProviderReturnsSeriousTaxForEuropeCountries()
    {
        $provider = $this->factory->getProvider('DE');
        $this->assertInstanceOf(TaxProviderInterface::class, $provider);
        $this->assertSame($this->seriousTaxAdapter, $provider);
    }

    public function testGetProviderThrowsExceptionForUnsupportedCountry()
    {
        $this->expectException(\Exception::class);
        $this->factory->getProvider('BR'); 
    }
}
