<?php

namespace App\Api\v1\Model;

use Illuminate\Database\Eloquent\Model;
// use Carbon\Carbon;

class Links extends Model
{
    protected $tabl = "links";
    protected $guarded = [];

    // public function getCreatedAtAttribute($date) {
    //     if (Carbon::now() > Carbon::parse($date)->addDays(15)) {
    //         return Carbon::parse($date);
    //     }
    //     return Carbon::parse($date)->diffForHumans();
    // }
}

