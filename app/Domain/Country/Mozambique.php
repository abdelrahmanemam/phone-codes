<?php

namespace App\Domain\Country;

use App\Domain\Country\Interface\CountryRuleInterface;

class Mozambique implements CountryRuleInterface
{
    public function countryName(): string
    {
        return 'Mozambique';
    }

    public function countryCode(): string
    {
        return '258';
    }

    public function isValid(string $phone): bool
    {
        return (bool) preg_match('/^\(258\) ?[28]\d{7,8}$/', $phone);
    }
}
