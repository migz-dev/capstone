<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\OwnedByFaculty;

class NcpPlan extends Model
{
    use OwnedByFaculty;

    protected $table = 'chartings_ncp';

    protected $fillable = [
        'encounter_id','faculty_id',
        'nanda_dx','goal','status','reviewed_at','interventions','evaluation',
    ];

    protected $casts = [
        'reviewed_at' => 'date',
    ];

    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class, 'encounter_id'); }
    public function faculty(): BelongsTo   { return $this->belongsTo(Faculty::class,   'faculty_id'); }
}
