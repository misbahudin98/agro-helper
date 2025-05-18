<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataActivities extends Model
{
    use HasFactory;
    protected $guarded = [
        'id'    ];

   

    public function DataPlant()
    {
        return $this->belongsTo(DataPlant::class);
    }
}
