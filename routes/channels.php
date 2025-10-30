<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('users', function (User $user) {
    // \Log::info($user);
    // return (int) $user->id === (int) $userId;
    return true;
});

// Test public channel
Broadcast::channel('channel', function () {
    return true;
});

// Test private channel
Broadcast::channel('channel-{userId}', function (User $user, int $userId) {
    return (int) $user->id === (int) $userId;
});

// Test presence channel    
Broadcast::channel('presence-channel-{roomId}', function (User $user, int $roomId) {
    return [ 'user' => $user ];
});
