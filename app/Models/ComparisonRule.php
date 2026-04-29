<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComparisonRule extends Model
{
    protected $fillable = [
        'rule_key',
        'min_value',
        'max_value',
        'status',
        'message_en',
        'message_ar',
        'is_active'
    ];
}