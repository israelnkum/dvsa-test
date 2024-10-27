<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyVehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "type" => $this->type,
            "contactDetails" => [
                "phone" => $this->phone,
                "contact_person" => $this->contact_person,
                "email" => $this->email,
                "website" => $this->website,
            ],
            "vehicles" => VehicleResource::collection($this->vehicles)
        ];
    }
}
