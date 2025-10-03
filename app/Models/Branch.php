<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $fillable = ["name", "location"];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_connects', 'branch_id', 'product_id');
    }

    public function bakers()
    {
        return $this->hasMany(Baker::class);
    }
}
