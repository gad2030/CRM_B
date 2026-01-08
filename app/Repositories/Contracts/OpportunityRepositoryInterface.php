<?php

namespace App\Repositories\Contracts;

use App\Models\Opportunity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface OpportunityRepositoryInterface
{
    /**
     * Get all opportunities with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get opportunity by ID.
     */
    public function findById(int $id): ?Opportunity;

    /**
     * Create a new opportunity.
     */
    public function create(array $data): Opportunity;

    /**
     * Update an opportunity.
     */
    public function update(Opportunity $opportunity, array $data): Opportunity;

    /**
     * Delete an opportunity.
     */
    public function delete(Opportunity $opportunity): bool;
}

