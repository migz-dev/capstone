<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcedureStep extends Model
{
    use HasFactory;

    // Table: procedure_steps (default matches)
    protected $fillable = [
        'procedure_id',
        'step_no',
        'title',
        'body',
        'rationale',
        'caution',
        'duration_seconds',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
    ];

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }
}
