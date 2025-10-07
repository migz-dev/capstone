<?php
// app/Http/Requests/Faculty/StoreNcpRequest.php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class StoreNcpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('faculty')->check();
    }

    public function rules(): array
    {
        return [
            'patient_name'        => ['required','string','max:255'],
            'encounter_id'        => ['nullable','integer','exists:chartings_encounters,id'],
            'noted_at'            => ['required','date'],

            'dx_primary'          => ['required','string'],
            'dx_related_to'       => ['nullable','string'],
            'dx_as_evidenced_by'  => ['nullable','string'],

            'goal_short'          => ['nullable','string'],
            'goal_long'           => ['nullable','string'],

            'interventions'       => ['required','string'],
            'evaluation'          => ['nullable','string'],

            'remarks'             => ['nullable','string','max:2000'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(array_map(fn($v) => $v === '' ? null : $v, $this->all()));
    }
}
