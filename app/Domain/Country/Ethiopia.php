<?php

namespace App\Domain\Country;

use App\Domain\Country\Interface\CountryRuleInterface;

class Ethiopia implements CountryRuleInterface
{
    public function countryName(): string
    {
        return 'Ethiopia';
    }

    public function countryCode(): string
    {
        return '251';
    }

    public function isValid(string $phone): bool
    {
        return (bool) preg_match('/^\(251\) ?[1-59]\d{8}$/', $phone);
    }
}
