<?php

namespace App\Domain\Phone;

use App\Domain\Country\Interface\CountryRuleInterface;

class PhoneNumberParser
{
    /**
     * @var array<int, CountryRuleInterface>
     */
    private array $rulesByCode = [];

    /**
     * @param CountryRuleInterface[] $rules
     */
    public function __construct(array $rules)
    {
        // Index rules by country code for fast lookup
        foreach ($rules as $rule) {
            $this->rulesByCode[$rule->countryCode()] = $rule;
        }
    }

    /**
     * Parse a raw phone number into a PhoneNumber object
     */
    public function parse(string $rawPhone): PhoneNumber
    {
        // Extract country code from format "(XXX) ..."
        preg_match('/\((\d{3})\)/', $rawPhone, $matches);
        $code = $matches[1] ?? null;

        $rule = $this->rulesByCode[$code] ?? null;

        // Extract the number part
        preg_match('/\((\d{3})\)\s*(.+)/', $rawPhone, $m);
        $numberPart = $m[2] ?? null;

        // Validate only if a rule exists
        $valid = $rule && $rule->isValid($rawPhone);
        $countryName = $rule?->countryName();

        return new PhoneNumber(
            raw: $rawPhone,
            countryName: $countryName,
            code: $code,
            number: $numberPart,
            valid: $valid
        );
    }

    /**
     * Return all supported countries
     */
    public function countries(): array
    {
        return array_map(fn($rule) => [
            'name' => $rule->countryName(),
            'code' => $rule->countryCode(),
        ], $this->rulesByCode);
    }
}
