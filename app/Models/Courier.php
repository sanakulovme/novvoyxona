<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Courier extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ["name", "phone", "password", "status", "branch_id"];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Required for JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Returns the primary key (e.g., id)
    }

    // Required for JWTSubject
    public function getJWTCustomClaims()
    {
        return ['guard' => 'courier']; // Add guard information to the token
    }

    // Optional: Override to use 'phone' instead of 'email' for auth
    public function getAuthIdentifierName()
    {
        return 'phone';
    }
}
