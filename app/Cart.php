<?php

namespace Doomus;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function user(){
        return $this->belongsTo('Doomus\User');
    }
}
