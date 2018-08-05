<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public function todos(){   
        return $this->belongsToMany(Todo::class);
    }
}
