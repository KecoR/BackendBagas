<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    public function pemandu()
    {
        return $this->belongsTo('App\User', 'pemandu_id', 'id');
    }

    public function museum()
    {
        return $this->belongsTo('App\Museum', 'museum_id', 'id');
    }
}
