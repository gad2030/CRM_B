<?php

namespace App\Repositories;

use App\Models\Opportunity;
use App\Repositories\Contracts\OpportunityRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OpportunityRepository implements OpportunityRepositoryInterface
{
    /**
     * Get all opportunities with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Opportunity::query();

        if (isset($filters['stage'])) {
            $query->where('stage', $filters['stage']);
        }

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        return $query->with(['owner', 'account', 'contact'])->paginate($perPage);
    }

    /**
     * Get opportunity by ID.
     */
    public function findById(int $id): ?Opportunity
    {
        return Opportunity::with(['owner', 'account', 'contact'])->find($id);
    }

    /**
     * Create a new opportunity.
     */
    public function create(array $data): Opportunity
    {
        return Opportunity::create($data);
    }

    /**
     * Update an opportunity.
     */
    public function update(Opportunity $opportunity, array $data): Opportunity
    {
        $opportunity->update($data);
        return $opportunity->fresh();
    }

    /**
     * Delete an opportunity.
     */
    public function delete(Opportunity $opportunity): bool
    {
        return $opportunity->delete();
    }
}

