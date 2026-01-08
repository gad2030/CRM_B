<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     * Get all contacts with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Contact::query();

        if (isset($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        return $query->with(['owner', 'account'])->paginate($perPage);
    }

    /**
     * Get contact by ID.
     */
    public function findById(int $id): ?Contact
    {
        return Contact::with(['owner', 'account'])->find($id);
    }

    /**
     * Create a new contact.
     */
    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     * Update a contact.
     */
    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact->fresh();
    }

    /**
     * Delete a contact.
     */
    public function delete(Contact $contact): bool
    {
        return $contact->delete();
    }
}

