<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('chat.{conversation_id}', function ($user, $conversation_id) {

    return App\Conversation_user::where('user_id',$user->id)
    ->where('conversation_id', $conversation_id)
    ->first();
});


Broadcast::channel('conversations.{message_to}', function ($user, $message_to) {
    return true;
});
