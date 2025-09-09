<?php

namespace App\Domain\Country;

use App\Domain\Country\Interface\CountryRuleInterface;

class Cameroon implements CountryRuleInterface
{
    public function countryName(): string
    {
        return 'Cameroon';
    }

    public function countryCode(): string
    {
        return '237';
    }

    public function isValid(string $phone): bool
    {
        return (bool) preg_match('/^\(237\) ?[2368]\d{7,8}$/', $phone);
    }
}
