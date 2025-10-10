<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ["name", "price", 'branch_id'];

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'product_connects', 'product_id', 'branch_id');
    }

}
