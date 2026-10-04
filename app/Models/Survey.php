<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_range',
        'monthly_payment',
        'term',
        'user_id',
        'down_payment',
    ];
}