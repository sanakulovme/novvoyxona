<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sold extends Model
{
    use HasFactory;
    protected $table = 'sold';
    protected $fillable = ['products_count', 'details', 'amount', 'branch_id', 'date'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

