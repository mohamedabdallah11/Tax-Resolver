<?php
namespace App\Tests\EndToEnd;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaxServiceEndToEndTest extends WebTestCase
{public function testGetTaxesReturnsCorrectData(): void
    {
        $client = static::createClient();
    
        $client->request('GET', 'http://localhost:54242/taxes?country=US&state=CA');

    $this->assertResponseIsSuccessful();

    $responseData = json_decode($client->getResponse()->getContent(), true);

    $this->assertNotEmpty($responseData);
    $this->assertIsArray($responseData);

    $this->assertArrayHasKey(0, $responseData);

    $this->assertArrayHasKey('percentage', $responseData[0]);

    $this->assertEquals(20, $responseData[0]['percentage']);
}
}
