<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTreatmentRequest extends FormRequest
{
    public function authorize(): bool { return auth('faculty')->check(); }

    public function rules(): array
    {
        return [
            'procedure_name'    => ['required','string','max:180'],
            'indication'        => ['nullable','string','max:200'],
            'consent_obtained'  => ['sometimes','boolean'],
            'sterile_technique' => ['sometimes','boolean'],
            'started_at'        => ['nullable','date'],
            'ended_at'          => ['nullable','date','after_or_equal:started_at'],
            'performed_by'      => ['nullable','string','max:160'],
            'assisted_by'       => ['nullable','string','max:160'],
            'outcome'           => ['nullable','string','max:5000'],
            'complications'     => ['nullable','string','max:5000'],
            'remarks'           => ['nullable','string','max:2000'],
            'pre_notes'         => ['nullable','string','max:2000'],
            'post_notes'        => ['nullable','string','max:2000'],
        ];
    }
}