<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\ContactResource;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends ApiController
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository
    ) {
    }

    /**
     * Display a listing of contacts.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['account_id']);
        $filters['owner_id'] = $request->user()->isAdmin() ? null : $request->user()->id;

        $contacts = $this->contactRepository->getAll($filters, 15);

        return $this->ok(
            new ContactCollection($contacts),
            'Contacts retrieved successfully'
        );
    }

    /**
     * Store a newly created contact.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        $contact = $this->contactRepository->create($data);

        return $this->ok(
            new ContactResource($contact),
            'Contact created successfully',
            201
        );
    }

    /**
     * Display the specified contact.
     */
    public function show(int $id): JsonResponse
    {
        $contact = $this->contactRepository->findById($id);

        if (!$contact) {
            return $this->fail('Contact not found', 404);
        }

        return $this->ok(
            new ContactResource($contact),
            'Contact retrieved successfully'
        );
    }

    /**
     * Update the specified contact.
     */
    public function update(UpdateContactRequest $request, int $id): JsonResponse
    {
        $contact = $this->contactRepository->findById($id);

        if (!$contact) {
            return $this->fail('Contact not found', 404);
        }

        $this->authorize('update', $contact);

        $contact = $this->contactRepository->update($contact, $request->validated());

        return $this->ok(
            new ContactResource($contact),
            'Contact updated successfully'
        );
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $contact = $this->contactRepository->findById($id);

        if (!$contact) {
            return $this->fail('Contact not found', 404);
        }

        $this->authorize('delete', $contact);

        $this->contactRepository->delete($contact);

        return $this->ok(null, 'Contact deleted successfully');
    }
}

