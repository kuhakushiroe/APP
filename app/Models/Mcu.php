<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mcu extends Model
{
    use SoftDeletes;
    //
    protected $table = 'mcu';
    protected $guarded = [];
}
