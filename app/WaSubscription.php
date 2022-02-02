<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaSubscription extends Model
{
    protected $guarded = [];
    public function user() {
        return $this->belongsTo(WaUser::class);
    }
}
