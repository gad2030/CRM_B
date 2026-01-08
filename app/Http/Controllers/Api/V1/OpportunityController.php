<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Opportunity\StoreOpportunityRequest;
use App\Http\Requests\Opportunity\UpdateOpportunityRequest;
use App\Http\Resources\OpportunityCollection;
use App\Http\Resources\OpportunityResource;
use App\Repositories\Contracts\OpportunityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpportunityController extends ApiController
{
    public function __construct(
        private readonly OpportunityRepositoryInterface $opportunityRepository
    ) {
    }

    /**
     * Display a listing of opportunities.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['stage']);
        $filters['owner_id'] = $request->user()->isAdmin() ? null : $request->user()->id;

        $opportunities = $this->opportunityRepository->getAll($filters, 15);

        return $this->ok(
            new OpportunityCollection($opportunities),
            'Opportunities retrieved successfully'
        );
    }

    /**
     * Store a newly created opportunity.
     */
    public function store(StoreOpportunityRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        $opportunity = $this->opportunityRepository->create($data);

        return $this->ok(
            new OpportunityResource($opportunity),
            'Opportunity created successfully',
            201
        );
    }

    /**
     * Display the specified opportunity.
     */
    public function show(int $id): JsonResponse
    {
        $opportunity = $this->opportunityRepository->findById($id);

        if (!$opportunity) {
            return $this->fail('Opportunity not found', 404);
        }

        return $this->ok(
            new OpportunityResource($opportunity),
            'Opportunity retrieved successfully'
        );
    }

    /**
     * Update the specified opportunity.
     */
    public function update(UpdateOpportunityRequest $request, int $id): JsonResponse
    {
        $opportunity = $this->opportunityRepository->findById($id);

        if (!$opportunity) {
            return $this->fail('Opportunity not found', 404);
        }

        $this->authorize('update', $opportunity);

        $opportunity = $this->opportunityRepository->update($opportunity, $request->validated());

        return $this->ok(
            new OpportunityResource($opportunity),
            'Opportunity updated successfully'
        );
    }

    /**
     * Remove the specified opportunity.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $opportunity = $this->opportunityRepository->findById($id);

        if (!$opportunity) {
            return $this->fail('Opportunity not found', 404);
        }

        $this->authorize('delete', $opportunity);

        $this->opportunityRepository->delete($opportunity);

        return $this->ok(null, 'Opportunity deleted successfully');
    }
}

