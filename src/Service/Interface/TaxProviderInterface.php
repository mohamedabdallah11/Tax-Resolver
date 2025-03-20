<?php
namespace App\Service\Interface;

interface TaxProviderInterface
{
    public function getTaxes(string $country, ?string $state): array;
}