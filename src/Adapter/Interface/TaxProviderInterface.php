<?php
namespace App\Adapter\Interface;

interface TaxProviderInterface
{
    public function getTaxes(string $country, ?string $state): array;
}