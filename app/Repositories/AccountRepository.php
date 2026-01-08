<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountRepositoryInterface
{
    /**
     * Get all accounts with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Account::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['owner_id'])) {
            $query->where('owner_id', $filters['owner_id']);
        }

        return $query->with('owner')->paginate($perPage);
    }

    /**
     * Get account by ID.
     */
    public function findById(int $id): ?Account
    {
        return Account::with(['owner', 'contacts', 'opportunities'])->find($id);
    }

    /**
     * Create a new account.
     */
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    /**
     * Update an account.
     */
    public function update(Account $account, array $data): Account
    {
        $account->update($data);
        return $account->fresh();
    }

    /**
     * Delete an account.
     */
    public function delete(Account $account): bool
    {
        return $account->delete();
    }
}

