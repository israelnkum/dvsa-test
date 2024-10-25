<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = $this?->userable?->personnelable_type;
        return [
            'id' => $this->id,
            'first_name'=> $this->userable?->first_name,
            'last_name'=> $this->userable?->last_name,
            'other_names'=> $this->userable?->other_names,
            'phone_number'=> $this->userable?->phone_number,
            'address'=> $this->userable?->address,
            'email'=> $this->email,
            'personnel_id' => $this?->userable?->personnelable_id,
            'password_changed'=> $this->password_changed == 1,
            'display_picture'=> env('API_URL') . '/file/'.env('DISPLAY_PICTURE_PATH').'/' . $this->display_picture,
            'roles' => $this->getRoleNames(),
            'permissions' => $this->getPermissionsViaRoles()->pluck('name')->merge
            ($this->getDirectPermissions()->pluck('name'))
        ];
    }
}
