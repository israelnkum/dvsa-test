<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            "phone" => $this->phone,
            "type" => $this->type,
            "contact_person" => $this->contact_person,
            "email" => $this->email,
            "website" => $this->website,
            "vehicles" => $this->vehicles
        ];
    }
}
