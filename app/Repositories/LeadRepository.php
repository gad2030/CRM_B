<?php

namespace App\Repositories;

use App\Models\Lead;
use App\Repositories\Contracts\LeadRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeadRepository implements LeadRepositoryInterface
{
    /**
     * Get all leads with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Lead::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        return $query->with('owner')->paginate($perPage);
    }

    /**
     * Get lead by ID.
     */
    public function findById(int $id): ?Lead
    {
        return Lead::with('owner')->find($id);
    }

    /**
     * Create a new lead.
     */
    public function create(array $data): Lead
    {
        return Lead::create($data);
    }

    /**
     * Update a lead.
     */
    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);
        return $lead->fresh();
    }

    /**
     * Delete a lead.
     */
    public function delete(Lead $lead): bool
    {
        return $lead->delete();
    }
}

