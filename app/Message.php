<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
	protected $fillable = [
		'body',
		'conversation_id',
        'author_id'
    ];
    
    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function conversation(){
    	return $this->belongsTo('App\Conversation');
    }
}
