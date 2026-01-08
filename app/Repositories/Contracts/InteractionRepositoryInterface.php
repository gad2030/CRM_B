<?php

namespace App\Repositories\Contracts;

use App\Models\Interaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InteractionRepositoryInterface
{
    /**
     * Get all interactions with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get interaction by ID.
     */
    public function findById(int $id): ?Interaction;

    /**
     * Create a new interaction.
     */
    public function create(array $data): Interaction;

    /**
     * Update an interaction.
     */
    public function update(Interaction $interaction, array $data): Interaction;

    /**
     * Delete an interaction.
     */
    public function delete(Interaction $interaction): bool;
}

