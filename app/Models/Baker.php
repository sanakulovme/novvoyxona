<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Baker extends Model
{
    use HasFactory;

    protected $fillable = ["name", "phone", "status", "branch_id"];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'baker_id');
    }
}
