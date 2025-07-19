<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticker',
        'fund_name',
        'last_quote',
    ];

    protected $casts = [
        'last_quote' => 'float',
    ];
}
