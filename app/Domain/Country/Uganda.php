<?php

namespace App\Domain\Country;

use App\Domain\Country\Interface\CountryRuleInterface;

class Uganda implements CountryRuleInterface
{
    public function countryName(): string
    {
        return 'Uganda';
    }

    public function countryCode(): string
    {
        return '256';
    }

    public function isValid(string $phone): bool
    {
        return (bool) preg_match('/^\(256\) ?\d{9}$/', $phone);
    }
}
