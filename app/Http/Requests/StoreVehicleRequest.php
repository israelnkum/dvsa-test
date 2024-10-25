<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Vehicle;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "registration_number" => ["required", "string", Rule::unique(Vehicle::class)],
            "make"=> ["string"],
            "model"=> ["string"],
            "company_id" => ["required", "exists:companies,id"]
        ];
    }
}
