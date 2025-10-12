<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [ 'branch_id', 'name', 'phone', 'role', 'status' ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
