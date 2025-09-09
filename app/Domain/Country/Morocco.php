<?php

namespace App\Domain\Country;

use App\Domain\Country\Interface\CountryRuleInterface;

class Morocco implements CountryRuleInterface
{
    public function countryName(): string
    {
        return 'Morocco';
    }

    public function countryCode(): string
    {
        return '212';
    }

    public function isValid(string $phone): bool
    {
        return (bool) preg_match('/^\(212\)\ ?[5-9]\d{8}$/', $phone);
    }
}
