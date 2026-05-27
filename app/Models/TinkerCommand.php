<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TinkerCommand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'command',
        'result',
        'is_favorite',
        'execution_time', 
    'memory_usage'

    ];
}
