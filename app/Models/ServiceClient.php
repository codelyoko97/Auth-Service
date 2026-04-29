<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceClient extends Model
{
    protected $table = 'service_clients';

    protected $fillable = [
        'name',
        'client_id',
        'client_secret'
    ];

    public function sessions() {
        return $this->hasMany(ServiceSession::class);
    }
}
