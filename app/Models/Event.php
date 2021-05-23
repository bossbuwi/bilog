<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\System;
use App\Models\Type;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = '_id';

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function scopeSystemUpgrade($query, $type)
    {
        $eventType = Type::where('event_code', 'SYSUP')->first();
        if ($type === 'all') {
            return $query->where('type_id', $eventType->id);
        } else if ($type === 'last') {
            return $query->where('type_id', $eventType->id)
                ->orderBy('end_date', 'DESC');
        }
    }
}
