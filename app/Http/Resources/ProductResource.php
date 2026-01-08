<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => $this->price,
            'cost_price' => $this->cost_price,
            'profit_margin' => $this->getProfitMargin(),
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'owner_id' => $this->owner_id,
            'is_active' => $this->is_active,
            'price_history' => ProductPriceResource::collection($this->whenLoaded('productPrices')),
            'current_price' => $this->when($this->relationLoaded('productPrices'), fn() => $this->getCurrentPrice()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
