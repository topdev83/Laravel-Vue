<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class AuthState extends Model
{
    use CentralConnection;

    protected $fillable = [
        'user_id',
        'tenant_id'
    ];

    public function generateToken()
    {
        $this->token = Str::random(32);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

}
