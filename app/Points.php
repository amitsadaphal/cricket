<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    public function teamPoints()
    {
        return $this->belongsTo('App\Team','team_id','id');
    }
}
