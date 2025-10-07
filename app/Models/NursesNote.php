<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NursesNote extends Model
{
    use SoftDeletes;

    protected $table = 'chartings_nurses_notes';

// app/Models/NursesNote.php
protected $fillable = [
  'faculty_id','patient_name','encounter_id','noted_at','format',
  'narrative',
  'dar_data','dar_action','dar_response',
  'soapie_s','soapie_o','soapie_a','soapie_p','soapie_i','soapie_e',
  'pie_p','pie_i','pie_e',
  'remarks',
];

protected $casts = ['noted_at' => 'datetime'];


    /* ------------------------------------ *
     | Relationships
     * ------------------------------------ */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id');
    }

    /* ------------------------------------ *
     | Scopes
     * ------------------------------------ */
    /** Limit to the logged-in CI */
    public function scopeOwned($q)
    {
        return $q->where('faculty_id', auth('faculty')->id());
    }

    /* ------------------------------------ *
     | Accessors
     * ------------------------------------ */
    /** Small text preview for tables */
    public function getPreviewAttribute(): string
    {
        $text = match ($this->format) {
            'dar'    => trim(($this->dar_data ?? '') . ' ' . ($this->dar_action ?? '') . ' ' . ($this->dar_response ?? '')),
            'soapie' => trim(($this->soapie_s ?? '') . ' ' . ($this->soapie_o ?? '') . ' ' . ($this->soapie_a ?? '')),
            'pie'    => trim(($this->pie_p ?? '') . ' ' . ($this->pie_i ?? '') . ' ' . ($this->pie_e ?? '')),
            default  => (string) ($this->narrative ?? ''),
        };

        $text = preg_replace('/\s+/', ' ', $text ?? '');
        return Str::limit(trim($text), 120, '…');
    }

    /* ------------------------------------ *
     | Hooks
     * ------------------------------------ */
    protected static function booted(): void
    {
        // Auto-set faculty_id on create if not provided
        static::creating(function (self $note) {
            if (blank($note->faculty_id) && auth('faculty')->check()) {
                $note->faculty_id = auth('faculty')->id();
            }
        });
    }
}