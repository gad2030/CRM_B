<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ContactRepositoryInterface
{
    /**
     * Get all contacts with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get contact by ID.
     */
    public function findById(int $id): ?Contact;

    /**
     * Create a new contact.
     */
    public function create(array $data): Contact;

    /**
     * Update a contact.
     */
    public function update(Contact $contact, array $data): Contact;

    /**
     * Delete a contact.
     */
    public function delete(Contact $contact): bool;
}

