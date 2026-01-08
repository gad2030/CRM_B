<?php

namespace App\Repositories\Contracts;

use App\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LeadRepositoryInterface
{
    /**
     * Get all leads with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get lead by ID.
     */
    public function findById(int $id): ?Lead;

    /**
     * Create a new lead.
     */
    public function create(array $data): Lead;

    /**
     * Update a lead.
     */
    public function update(Lead $lead, array $data): Lead;

    /**
     * Delete a lead.
     */
    public function delete(Lead $lead): bool;
}

