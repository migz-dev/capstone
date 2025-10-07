<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class StoreIntakeOutputRequest extends FormRequest
{
    public function authorize(): bool { return auth('faculty')->check(); }

    public function rules(): array
    {
        return [
            // quick patient & encounter
            'quick_patient.last_name'    => ['required','string','max:100'],
            'quick_patient.first_name'   => ['required','string','max:100'],
            'quick_patient.hospital_no'  => ['nullable','string','max:50'],

            'quick_encounter.unit'       => ['required','string','max:120'],
            'quick_encounter.started_at' => ['required','date'],
            'quick_encounter.remarks'    => ['nullable','string','max:1000'],

            // I&O fields
            'started_at' => ['nullable','date'],
            'ended_at'   => ['nullable','date','after_or_equal:started_at'],

            'intake_oral_ml'  => ['nullable','integer','min:0'],
            'intake_iv_ml'    => ['nullable','integer','min:0'],
            'intake_tube_ml'  => ['nullable','integer','min:0'],

            'output_urine_ml' => ['nullable','integer','min:0'],
            'output_stool_ml' => ['nullable','integer','min:0'],
            'output_emesis_ml'=> ['nullable','integer','min:0'],
            'output_drain_ml' => ['nullable','integer','min:0'],

            'remarks' => ['nullable','string','max:2000'],
        ];
    }
}
