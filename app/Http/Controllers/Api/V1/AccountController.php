<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Http\Resources\AccountCollection;
use App\Http\Resources\AccountResource;
use App\Repositories\Contracts\AccountRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends ApiController
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository
    ) {
    }

    /**
     * Display a listing of accounts.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $filters = $request->only(['name']);
        $filters['owner_id'] = $user->isAdmin() ? null : $user->id;
        
        // Filter by current employer (global scope will also handle this)
        if ($user->currentEmployer) {
            $filters['employer_id'] = $user->currentEmployer->id;
        }

        $accounts = $this->accountRepository->getAll($filters, 15);

        return $this->ok(
            new AccountCollection($accounts),
            'Accounts retrieved successfully'
        );
    }

    /**
     * Store a newly created account.
     */
    public function store(StoreAccountRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $data['owner_id'] = $user->id;
        
        // Set employer_id from current employer
        if ($user->currentEmployer) {
            $data['employer_id'] = $user->currentEmployer->id;
        }

        $account = $this->accountRepository->create($data);

        return $this->ok(
            new AccountResource($account),
            'Account created successfully',
            201
        );
    }

    /**
     * Display the specified account.
     */
    public function show(int $id): JsonResponse
    {
        $account = $this->accountRepository->findById($id);

        if (!$account) {
            return $this->fail('Account not found', 404);
        }

        return $this->ok(
            new AccountResource($account),
            'Account retrieved successfully'
        );
    }

    /**
     * Update the specified account.
     */
    public function update(UpdateAccountRequest $request, int $id): JsonResponse
    {
        $account = $this->accountRepository->findById($id);

        if (!$account) {
            return $this->fail('Account not found', 404);
        }

        $this->authorize('update', $account);

        $account = $this->accountRepository->update($account, $request->validated());

        return $this->ok(
            new AccountResource($account),
            'Account updated successfully'
        );
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $account = $this->accountRepository->findById($id);

        if (!$account) {
            return $this->fail('Account not found', 404);
        }

        $this->authorize('delete', $account);

        $this->accountRepository->delete($account);

        return $this->ok(null, 'Account deleted successfully');
    }
}

