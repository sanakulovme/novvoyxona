<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductConnect extends Model
{
    use HasFactory;
    protected $table = 'product_connects';
    protected $fillable = ['branch_id', 'product_id'];
}
