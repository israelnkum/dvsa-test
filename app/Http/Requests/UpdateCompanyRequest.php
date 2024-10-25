<?php

namespace App\Http\Requests;

use App\Enums\CompanyTypeEnum;
use App\Models\Company;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
        $company = $this->route('company');
        return [
            "name" => ["required", "string", Rule::unique(Company::class)->ignore($company->id)],
            "address"=> ["string"],
            "phone"=> ["string"],
            "type" => ["required", Rule::in(CompanyTypeEnum::cases())],
            "contact_person"=> ["string"],
            "email"=> ["string", "email"],
            "website"=> ["string"]
        ];
    }
}
