<?php

namespace App\Http\Requests\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntakeOutputRequest extends FormRequest
{
    public function authorize(): bool { return auth('faculty')->check(); }

    public function rules(): array
    {
        return [
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