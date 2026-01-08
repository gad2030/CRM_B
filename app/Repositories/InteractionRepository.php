<?php

namespace App\Repositories;

use App\Models\Interaction;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class InteractionRepository implements InteractionRepositoryInterface
{
    /**
     * Get all interactions with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Interaction::query();

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        if (isset($filters['contact_id'])) {
            $query->where('contact_id', $filters['contact_id']);
        }

        if (isset($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if (isset($filters['opportunity_id'])) {
            $query->where('opportunity_id', $filters['opportunity_id']);
        }

        return $query->with(['user', 'account', 'contact', 'lead', 'opportunity'])->paginate($perPage);
    }

    /**
     * Get interaction by ID.
     */
    public function findById(int $id): ?Interaction
    {
        return Interaction::with(['user', 'account', 'contact', 'lead', 'opportunity'])->find($id);
    }

    /**
     * Create a new interaction.
     */
    public function create(array $data): Interaction
    {
        return Interaction::create($data);
    }

    /**
     * Update an interaction.
     */
    public function update(Interaction $interaction, array $data): Interaction
    {
        $interaction->update($data);
        return $interaction->fresh();
    }

    /**
     * Delete an interaction.
     */
    public function delete(Interaction $interaction): bool
    {
        return $interaction->delete();
    }
}

