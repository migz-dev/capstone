<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TreatmentRecord extends Model
{
    protected $table = 'chartings_treatment_records';

    protected $fillable = [
        'faculty_id','patient_id','encounter_id',
        'procedure_name','indication','consent_obtained','sterile_technique',
        'started_at','ended_at','performed_by','assisted_by',
        'outcome','complications','remarks','pre_notes','post_notes',
    ];

    protected $casts = [
        'consent_obtained' => 'boolean',
        'sterile_technique'=> 'boolean',
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
    ];

    public function scopeOwned(Builder $q): Builder
    {
        return $q->where('faculty_id', auth('faculty')->id());
    }

    public function faculty()   { return $this->belongsTo(Faculty::class); }
    public function patient()   { return $this->belongsTo(Patient::class); }
    public function encounter() { return $this->belongsTo(Encounter::class); }
}
