<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    

    public function User(){
    	return $this->belongsToMany('App\User');
    }

    public function messages(){
    	return $this->hasMany('App\Message');
    }
}
