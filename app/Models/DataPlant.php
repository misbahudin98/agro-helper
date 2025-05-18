<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPlant extends Model
{
    use HasFactory;
    protected $guarded = [
        "id"
    ];
    public function DataField()
    {
        return $this->belongsTo(DataField::class);
    }
    public function activities()
    {
        return $this->hasMany(DataActivities::class);
    }
}
