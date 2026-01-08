<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'date' => $this->date?->toISOString(),
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'account_id' => $this->account_id,
            'account' => new AccountResource($this->whenLoaded('account')),
            'contact_id' => $this->contact_id,
            'contact' => new ContactResource($this->whenLoaded('contact')),
            'lead_id' => $this->lead_id,
            'lead' => new LeadResource($this->whenLoaded('lead')),
            'opportunity_id' => $this->opportunity_id,
            'opportunity' => new OpportunityResource($this->whenLoaded('opportunity')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

