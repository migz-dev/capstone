<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MarRecord extends Model
{
    protected $table = 'chartings_mar_records';

    protected $fillable = [
        'faculty_id','patient_id','encounter_id',
        'medication','dose_amount','dose_unit','route','frequency',
        'is_prn','prn_reason','administered_at','omitted_reason','effects','remarks',
    ];

    protected $casts = [
        'is_prn'         => 'boolean',
        'administered_at'=> 'datetime',
        'dose_amount'    => 'decimal:2',
    ];

    public function scopeOwned(Builder $q): Builder
    {
        return $q->where('faculty_id', auth('faculty')->id());
    }

    public function faculty()   { return $this->belongsTo(Faculty::class); }
    public function patient()   { return $this->belongsTo(Patient::class); }
    public function encounter() { return $this->belongsTo(Encounter::class); }
}
