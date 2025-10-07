<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\OwnedByFaculty;
use App\Models\Encounter;
use App\Models\Faculty;

class Vital extends Model
{
    use OwnedByFaculty;

    protected $table = 'chartings_vitals';

    protected $fillable = [
        'encounter_id', 'faculty_id', 'taken_at',
        'temp_c', 'pulse_bpm', 'resp_rate',
        'bp_systolic', 'bp_diastolic', 'spo2',
        'pain_scale', 'remarks',
    ];

    protected $casts = [
        'taken_at'     => 'datetime',
        'temp_c'       => 'float',
        'pulse_bpm'    => 'integer',
        'resp_rate'    => 'integer',
        'bp_systolic'  => 'integer',
        'bp_diastolic' => 'integer',
        'spo2'         => 'integer',
        'pain_scale'   => 'integer',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
}
