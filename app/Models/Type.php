<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class Type extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function event()
    {
        return $this->hasMany(Event::class);
    }

    protected $casts = [
        'restricted' => 'boolean',
        'maintenance' => 'boolean',
        'upgrade' => 'boolean'
    ];
}
