<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ["courier_name", "customer_name", "customer_phone", "order_time", "amount", "status", "location", "details"];
}
