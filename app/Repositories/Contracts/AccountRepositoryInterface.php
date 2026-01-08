<?php

namespace App\Repositories\Contracts;

use App\Models\Account;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AccountRepositoryInterface
{
    /**
     * Get all accounts with pagination and optional filters.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Get account by ID.
     */
    public function findById(int $id): ?Account;

    /**
     * Create a new account.
     */
    public function create(array $data): Account;

    /**
     * Update an account.
     */
    public function update(Account $account, array $data): Account;

    /**
     * Delete an account.
     */
    public function delete(Account $account): bool;
}

