<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Procedure extends Model
{
    use HasFactory;

    protected $table = 'procedures';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'level',                // beginner | intermediate | advanced
        'status',               // draft | published
        'hazards_text',
        'ppe_json',
        'tags_json',
        'video_url',
        'pdf_path',

        // creators / editors (CI + Admin)
        'created_by',           // Faculty ID (legacy: sometimes Admin ID)
        'created_by_admin',     // Admin ID (preferred)
        'updated_by',           // Faculty ID (legacy: sometimes Admin ID)
        'updated_by_admin',     // Admin ID (preferred)

        'published_at',
    ];

    protected $casts = [
        'ppe_json'     => 'array',
        'tags_json'    => 'array',
        'published_at' => 'datetime',
    ];

    // Useful computed fields for views / JSON
    protected $appends = [
        'created_by_name',
        'updated_by_name',
        'is_published',
    ];

    // Keep common creator/editor relations warm for snappy lists
    protected $with = [
        'adminCreator', 'author', 'adminCreatorLegacy',
        'adminEditor', 'editorFaculty', 'adminEditorLegacy',
    ];

    /* -----------------------------------------------------------------
     | Relationships
     |-----------------------------------------------------------------*/
    public function steps()
    {
        return $this->hasMany(ProcedureStep::class)->orderBy('step_no');
    }

    public function attachments()
    {
        return $this->hasMany(ProcedureAttachment::class);
    }

    /** CI creator stored in created_by (normal case for faculty-authored) */
    public function author()
    {
        return $this->belongsTo(\App\Models\Faculty::class, 'created_by');
    }

    /** Admin creator stored in created_by_admin (preferred for admin-authored) */
    public function adminCreator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin');
    }

    /** Legacy: some old rows stored an Admin ID in created_by */
    public function adminCreatorLegacy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    /** CI editor stored in updated_by */
    public function editorFaculty()
    {
        return $this->belongsTo(\App\Models\Faculty::class, 'updated_by');
    }

    /** Admin editor stored in updated_by_admin (preferred) */
    public function adminEditor()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'updated_by_admin');
    }

    /** Legacy: some old rows stored an Admin ID in updated_by */
    public function adminEditorLegacy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'updated_by');
    }

    /* -----------------------------------------------------------------
     | Accessors / Mutators
     |-----------------------------------------------------------------*/
    public function getCreatedByNameAttribute(): string
    {
        // Prefer explicit admin column, then legacy admin-in-created_by, then faculty
        return $this->adminCreator?->name
            ?? $this->adminCreatorLegacy?->name
            ?? $this->author?->name
            ?? '—';
    }

public function getUpdatedByNameAttribute(): string
{
    // Preferred: explicit admin editor
    $name =
        ($this->updaterAdmin?->full_name ?? $this->updaterAdmin?->name)
        // Legacy: admin id stored in updated_by
        ?? ($this->editorAdminLegacy?->full_name ?? $this->editorAdminLegacy?->name)
        // CI editor
        ?? ($this->editorFaculty?->full_name ?? $this->editorFaculty?->name);

    // If still empty, fall back to creator so you don’t see a dash
    return $name
        ?? ($this->creatorAdmin?->full_name ?? $this->creatorAdmin?->name
            ?? $this->adminAuthor?->full_name ?? $this->adminAuthor?->name
            ?? $this->author?->full_name ?? $this->author?->name
            ?? '—');
}

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    // Normalize arrays to never be null
    public function getPpeJsonAttribute($value): array
    {
        return is_array($value) ? $value : ($value ? (array) $value : []);
    }

    public function getTagsJsonAttribute($value): array
    {
        return is_array($value) ? $value : ($value ? (array) $value : []);
    }

    public function setTitleAttribute($value): void
    {
        $this->attributes['title'] = is_string($value) ? trim($value) : $value;
    }

    /* -----------------------------------------------------------------
     | Scopes
     |-----------------------------------------------------------------*/
    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function scopeDraft($q)
    {
        return $q->where('status', 'draft');
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        $term = trim($term);

        return $q->where(function ($qq) use ($term) {
            $qq->where('title', 'like', "%{$term}%")
               ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeStatus($q, ?string $status)
    {
        if (!$status) return $q;
        return $q->where('status', $status);
    }

    /* -----------------------------------------------------------------
     | Route binding by slug
     |-----------------------------------------------------------------*/
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /* -----------------------------------------------------------------
     | Helpers
     |-----------------------------------------------------------------*/
    public function publish(): void
    {
        $this->forceFill([
            'status'       => 'published',
            'published_at' => now(),
        ])->save();
    }

    public function unpublish(): void
    {
        $this->forceFill([
            'status'       => 'draft',
            'published_at' => null,
        ])->save();
    }

    /** Ensure slug exists and is unique. */
    public function ensureSlug(): void
    {
        if ($this->slug) return;

        $base = Str::slug((string) $this->title) ?: Str::random(8);
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        $this->slug = $slug;
    }
}
