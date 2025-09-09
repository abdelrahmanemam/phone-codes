<?php

namespace App\Domain\Phone;

final readonly class PhoneNumber
{
    public function __construct(
        public string  $raw,          // Original, e.g. "(212) 698054317"
        public ?string $countryName, // Morocco
        public ?string $code,        // 212
        public ?string $number,      // 698054317
        public bool    $valid           // true/false
    ) {}
}
