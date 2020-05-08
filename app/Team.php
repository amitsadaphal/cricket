<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function players()
    {
        return $this->hasMany('App\Player','country','club_state');
    }

    public function pointSum()
    {
        return $this->hasMany('App\Points','team_id','id')->whereNotNull('team_point');
    }

    
}
