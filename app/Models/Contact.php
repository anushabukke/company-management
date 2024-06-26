<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Contact extends Model
{
    use HasFactory, LogsActivity;
    protected static $logAttributes = ['name', 'email', 'phone'];

    protected static $logName = 'contact';
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This company has been {$eventName}";
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone'])
            ->logOnlyDirty()
            ->useLogName('company');
    }
}
