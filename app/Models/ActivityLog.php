<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

    protected $casts = [
        'properties' => 'json',
        // Other casted fields
    ];

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'event',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'batch_uuid',
        'created_at',
        'updated_at',
    ];

    public function getFieldAttribute()
    {
        $properties = $this->properties ?? [];

        if (isset($properties['attributes'])) {
            $keys = array_keys($properties['attributes']);
            return $keys[0] ?? 'N/A';
        }

        return 'N/A';
    }

    public function getOldAttribute()
    {
        $properties = $this->properties ?? [];

        if (isset($properties['old'])) {
            $oldValues = array_values($properties['old']);
            return isset($oldValues[0]) ? $oldValues[0] : 'N/A';
        }

        return 'N/A';
    }

    public function getNewAttribute()
    {
        $properties = $this->properties ?? [];

        if (isset($properties['attributes'])) {
            $attributes = $properties['attributes'];
            $newValues = [];
            foreach ($attributes as $key => $value) {
                $newValues[] = " $value";
            }
            return implode(', ', $newValues);
        }

        return 'N/A';
    }

    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'subject_id');
    }
}
