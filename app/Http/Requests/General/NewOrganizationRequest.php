<?php

namespace App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class NewOrganizationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "title"             =>"required|string",
            "description"       =>"nullable|string",
            "maturity"          =>"required|string",
            "amount_per_member"  =>"required|string",
            "start_date"        =>"required|string",
            "number_of_members" =>"required"
        ];
    }
}
