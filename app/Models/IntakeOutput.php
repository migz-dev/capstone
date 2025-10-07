<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IntakeOutput extends Model
{
    protected $table = 'chartings_intake_outputs';

    protected $fillable = [
        'faculty_id','patient_id','encounter_id',
        'started_at','ended_at',
        'intake_oral_ml','intake_iv_ml','intake_tube_ml',
        'output_urine_ml','output_stool_ml','output_emesis_ml','output_drain_ml',
        'remarks',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    // CI ownership
    public function scopeOwned(Builder $q): Builder
    {
        return $q->where('faculty_id', auth('faculty')->id());
    }

    // Relations
    public function faculty()   { return $this->belongsTo(Faculty::class); }
    public function patient()   { return $this->belongsTo(Patient::class); }
    public function encounter() { return $this->belongsTo(Encounter::class); }

    // Derived totals
    public function getIntakeTotalMlAttribute(): int
    {
        return (int)($this->intake_oral_ml + $this->intake_iv_ml + $this->intake_tube_ml);
    }

    public function getOutputTotalMlAttribute(): int
    {
        return (int)($this->output_urine_ml + $this->output_stool_ml + $this->output_emesis_ml + $this->output_drain_ml);
    }

    public function getBalanceMlAttribute(): int
    {
        return $this->intake_total_ml - $this->output_total_ml;
    }
}
