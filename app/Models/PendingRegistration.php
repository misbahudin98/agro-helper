<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PendingRegistration   extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'verification_token',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
