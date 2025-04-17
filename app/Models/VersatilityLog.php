<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VersatilityLog extends Model
{
    protected $fillable = ['nrp', 'kendaraan_baru'];
    public $timestamps = false;
    protected $table = 'versatility_logs';
}
