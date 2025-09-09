<?php

namespace App\Services;

use App\Models\Customer;
use App\Domain\Phone\PhoneNumberParser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PhoneNumberService
{
    public function __construct(private PhoneNumberParser $parser) {}

    /**
     * List phone numbers with filters and pagination
     *
     * @param array{country?: string, state?: string, per_page?: int, page?: int} $filters
     * @return array
     */
    public function listPhoneNumbers(array $filters = []): array
    {
        $perPage = $filters['per_page'] ?? 10;
        $page    = $filters['page'] ?? 1;

        $query = $this->buildQuery($filters);

        // Laravel pagination
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        // Transform and apply in-memory filters
        $items = $this->transformCollection($paginator->getCollection(), $filters);

        // Replace paginator collection with filtered items
        $paginator->setCollection($items);

        return $this->formatResponse($paginator);
    }

    /** Build Eloquent query with DB-level filters */
    private function buildQuery(array $filters): Builder
    {
        $query = Customer::query();

        if (!empty($filters['country'])) {
            $countryCode = $filters['country'];
            $query->where('phone', 'like', "($countryCode)%");
        }

        return $query;
    }

    /** Transform collection and apply in-memory filters */
    private function transformCollection(Collection $collection, array $filters): Collection
    {
        $countryFilter = $filters['country'] ?? null;
        $stateFilter   = $filters['state'] ?? null;

        return $collection->map(function ($customer) use ($countryFilter, $stateFilter) {
            $parsed = $this->parser->parse($customer->phone);

            $record = [
                'country'      => $parsed->countryName,
                'state'        => $parsed->valid ? 'valid' : 'invalid',
                'country_code' => $parsed->code ? '+' . $parsed->code : null,
                'number'       => $parsed->number,
            ];

            return $this->passesFilters($record, $parsed, $countryFilter, $stateFilter) ? $record : null;
        })->filter()->values();
    }

    /** Check if a record passes filters */
    private function passesFilters(array $record, $parsed, ?string $countryFilter, ?string $stateFilter): bool
    {
        if ($countryFilter) {
            if ($record['country_code'] !== '+' . $countryFilter || !$parsed->valid) {
                return false;
            }
        }

        if ($stateFilter && $record['state'] !== $stateFilter) {
            return false;
        }

        return true;
    }

    private function formatResponse(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => $paginator->items(),
            'meta' => [
                'page'      => $paginator->currentPage(),
                'per_page'  => $paginator->perPage(),
                'total'     => $paginator->total(),
                'has_more'  => $paginator->hasMorePages(),
                'countries' => $this->parser->countries(),
            ],
        ];
    }
}
