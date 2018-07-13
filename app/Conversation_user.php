<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation_user extends Model
{
	protected $fillable=[
		"user_id",
		"conversation_id"
	];
    
    protected $table='conversation_user';
}
