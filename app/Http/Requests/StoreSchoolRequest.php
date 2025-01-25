<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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
            'school_id' => 'required|unique:schools|numeric',
            'school_name' => 'required',
            'address' => 'required',
            // 'region' => 'required',
            'division' => 'required',
            'district' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'curricular_classification' => 'required',
        ];

        $newItemRules = ['new_item_title', 'new_item_category', 'new_item_incumbent'];
        $newAppointmentRules = ['new_appointment_title', 'new_appointment_appointment', 'new_appointment_fund_source', 'new_appointment_incumbent_teaching', 'new_appointment_incumbent_non_teaching'];

        // foreach ($newItemRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }

        // foreach ($newAppointmentRules as $rule) {
        //     $this->addRequiredRules($rules, $this->input($rule), $rule);
        // }

        return $rules;
    }

    private function addRequiredRules(&$rules, $inputs, $prefix)
    {
        foreach ($inputs as $index => $input) {
            $rules["$prefix.{$index}"] = 'required';

            if ($prefix === 'new_item_incumbent' || $prefix === 'new_appointment_incumbent_non_teaching') {
                $rules["$prefix.{$index}"] .= '|numeric';
            }
        }
    }

}
