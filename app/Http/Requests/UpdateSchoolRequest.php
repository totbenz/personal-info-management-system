<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            // SCHOOL
            'school_id' => 'required|unique:schools|numeric',
            'school_name' => 'required',
            'address' => 'required',
            // 'region' => 'required',
            'division' => 'required',
            'district' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'curricular_classification' => 'required',

            // FUNDED ITEM
            'funded_item_title' => 'required',
            'funded_item_category' => 'required',
            'funded_item_incumbent' => 'required|numeric',

            // APPOINTMENTS FUNDING
            'appointments_funding_title' => 'required',
            'appointments_funding_appointment' => 'required',
            'appointments_funding_fund_source' => 'required',
            'appointments_funding_incumbent_teaching' => 'required|numeric',
            'appointments_funding_incumbent_non_teaching' => 'required|numeric',
        ];

        // $fundedItemRules = ['funded_item_title', 'funded_item_category', 'funded_item_incumbent'];
        // $newItemRules = ['new_item_title', 'new_item_category', 'new_item_incumbent'];
        // $appointmentsFundingRules = ['appointments_funding_title', 'appointments_funding_appointment', 'appointments_funding_fund_source', 'appointments_funding_incumbent_teaching', 'appointments_funding_incumbent_non_teaching'];
        // $newAppointmentRules = ['new_appointment_title', 'new_appointment_appointment', 'new_appointment_fund_source', 'new_appointment_incumbent_teaching', 'new_appointment_incumbent_non_teaching'];

        // foreach ($fundedItemRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }

        // foreach ($appointmentsFundingRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }
        // foreach ($newItemRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }

        // foreach ($newAppointmentRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }

        return $rules;
    }

    // private function addRequiredRules(&$rules, $inputs, $prefix)
    // {
    //     foreach ($inputs as $index => $input) {
    //         $rules["$prefix.{$index}"] = 'required';

    //         // if ($prefix === 'new_item_incumbent' || $prefix === 'new_appointment_incumbent_non_teaching') {
    //         //     $rules["$prefix.{$index}"] .= '|numeric';
    //         // }
    //     }
    // }

}
