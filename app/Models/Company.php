<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Company extends Model
{
    use HasFactory, LogsActivity;


    protected static $logAttributes = ['name', 'entity_no', 'status'];


    protected static $logName = 'company';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'entity_no',
        'entity_no',
        'date_of_incorporation',
        'entity_no_and_name',
        'jurisdiction_id',
        'status',
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This company has been {$eventName}";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'entity_no', 'status'])
            ->logOnlyDirty()
            ->useLogName('company');
    }
}
