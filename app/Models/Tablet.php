<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Tablet extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [ 'phone', 'password', 'branch_id'];

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
        return ['guard' => 'tablet']; // Add guard information to the token
    }

    // Optional: Override to use 'phone' instead of 'email' for auth
    public function getAuthIdentifierName()
    {
        return 'phone';
    }
}
