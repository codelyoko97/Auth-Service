<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceClient extends Model
{
    protected $fillable = [
        'name',
        'client_id',
        'client_secret'
    ];
}
