<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'category' => $this->resource->category,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'date' => $this->resource->date,
            'image' => $this->resource->image,
            'city' => $this->resource->city,
            'address' => $this->resource->address,
            'status' => $this->resource->status,
            'ticketsTypes' => TicketTypeResource::collection($this->whenLoaded('typeTickets')),
        ];
    }
}
