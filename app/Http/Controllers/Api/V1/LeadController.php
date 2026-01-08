<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Http\Resources\LeadCollection;
use App\Http\Resources\LeadResource;
use App\Repositories\Contracts\LeadRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends ApiController
{
    public function __construct(
        private readonly LeadRepositoryInterface $leadRepository
    ) {
    }

    /**
     * Display a listing of leads.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status']);
        $filters['owner_id'] = $request->user()->isAdmin() ? null : $request->user()->id;

        $leads = $this->leadRepository->getAll($filters, 15);

        return $this->ok(
            new LeadCollection($leads),
            'Leads retrieved successfully'
        );
    }

    /**
     * Store a newly created lead.
     */
    public function store(StoreLeadRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        $lead = $this->leadRepository->create($data);

        return $this->ok(
            new LeadResource($lead),
            'Lead created successfully',
            201
        );
    }

    /**
     * Display the specified lead.
     */
    public function show(int $id): JsonResponse
    {
        $lead = $this->leadRepository->findById($id);

        if (!$lead) {
            return $this->fail('Lead not found', 404);
        }

        return $this->ok(
            new LeadResource($lead),
            'Lead retrieved successfully'
        );
    }

    /**
     * Update the specified lead.
     */
    public function update(UpdateLeadRequest $request, int $id): JsonResponse
    {
        $lead = $this->leadRepository->findById($id);

        if (!$lead) {
            return $this->fail('Lead not found', 404);
        }

        $this->authorize('update', $lead);

        $lead = $this->leadRepository->update($lead, $request->validated());

        return $this->ok(
            new LeadResource($lead),
            'Lead updated successfully'
        );
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $lead = $this->leadRepository->findById($id);

        if (!$lead) {
            return $this->fail('Lead not found', 404);
        }

        $this->authorize('delete', $lead);

        $this->leadRepository->delete($lead);

        return $this->ok(null, 'Lead deleted successfully');
    }
}

