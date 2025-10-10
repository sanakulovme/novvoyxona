<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["products_count", "branch_id", "courier_id", "customer_name", "customer_phone", "order_time", "amount", "status", "location", "details"];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
