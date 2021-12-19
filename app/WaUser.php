<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WaUser extends Model
{
    protected $guarded = [];

    protected static function boot() {
        parent::boot();
        static::created(function($user){
            $user->subscription()->create([
                'plan' => 'none',
                'start' => 'none',
                'end' => 'none',
            ]);
        });
    }
    public function subscription() {
        return $this->hasOne(WaSubscription::class);
    }
}
