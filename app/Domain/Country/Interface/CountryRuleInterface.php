<?php

namespace App\Domain\Country\Interface;

interface CountryRuleInterface
{

    public function countryName(): string;
    public function countryCode(): string;
    public function isValid(string $phone): bool;
}
