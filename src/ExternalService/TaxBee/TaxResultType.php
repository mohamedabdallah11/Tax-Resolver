<?php

declare(strict_types=1);

namespace App\ExternalService\TaxBee;

enum TaxResultType: string {
    case VAT = 'VAT';
    case GST_HST = 'GST/HST';
    case PST = 'PST';
}
