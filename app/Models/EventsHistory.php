<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class EventsHistory extends Model
{
    use HasFactory;

    protected $table = 'eventshistory';
    protected $primaryKey = '_id';

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
