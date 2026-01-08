<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Interaction\StoreInteractionRequest;
use App\Http\Requests\Interaction\UpdateInteractionRequest;
use App\Http\Resources\InteractionCollection;
use App\Http\Resources\InteractionResource;
use App\Repositories\Contracts\InteractionRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InteractionController extends ApiController
{
    public function __construct(
        private readonly InteractionRepositoryInterface $interactionRepository
    ) {
    }

    /**
     * Display a listing of interactions.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['account_id', 'contact_id', 'lead_id', 'opportunity_id']);
        $filters['user_id'] = $request->user()->isAdmin() ? null : $request->user()->id;

        $interactions = $this->interactionRepository->getAll($filters, 15);

        return $this->ok(
            new InteractionCollection($interactions),
            'Interactions retrieved successfully'
        );
    }

    /**
     * Store a newly created interaction.
     */
    public function store(StoreInteractionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $interaction = $this->interactionRepository->create($data);

        return $this->ok(
            new InteractionResource($interaction),
            'Interaction created successfully',
            201
        );
    }

    /**
     * Display the specified interaction.
     */
    public function show(int $id): JsonResponse
    {
        $interaction = $this->interactionRepository->findById($id);

        if (!$interaction) {
            return $this->fail('Interaction not found', 404);
        }

        return $this->ok(
            new InteractionResource($interaction),
            'Interaction retrieved successfully'
        );
    }

    /**
     * Update the specified interaction.
     */
    public function update(UpdateInteractionRequest $request, int $id): JsonResponse
    {
        $interaction = $this->interactionRepository->findById($id);

        if (!$interaction) {
            return $this->fail('Interaction not found', 404);
        }

        $this->authorize('update', $interaction);

        $interaction = $this->interactionRepository->update($interaction, $request->validated());

        return $this->ok(
            new InteractionResource($interaction),
            'Interaction updated successfully'
        );
    }

    /**
     * Remove the specified interaction.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $interaction = $this->interactionRepository->findById($id);

        if (!$interaction) {
            return $this->fail('Interaction not found', 404);
        }

        $this->authorize('delete', $interaction);

        $this->interactionRepository->delete($interaction);

        return $this->ok(null, 'Interaction deleted successfully');
    }
}

