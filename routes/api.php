<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\TestPublicEvent;
use App\Events\TestPrivateEvent;
use App\Events\TestPresenceEvent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::post('/access-token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Revoke all tokens
    Route::post('/user/revoke-access-tokens', function (Request $request) {
        return $request->user()->tokens()->delete();
    });

    // Revoke current token
    Route::post('/user/revoke-access-token', function (Request $request) {
        return $request->user()->currentAccessToken()->delete();
    });

    // Access token in plain text
    Route::get('/user/access-token/plain-text', function (Request $request) {
        return [ 'token' => $request->bearerToken() ];
    });

    // Public event test
    Route::post('/broadcast/test/public-event', function (Request $request) {
        TestPublicEvent::dispatch($request->user());
    });

    // Private event test
    Route::post('/broadcast/test/private-event', function (Request $request) {
        $id = $request->input('id');
        $user = User::where('id', '=', $id)->first();
        
        TestPrivateEvent::dispatch($user, $user->id);
    });

    // Presence event test
    Route::post('/broadcast/test/presence-event', function (Request $request) {
        $id = $request->input('id');
        $roomId = $request->input('roomId');
        $user = User::where('id', '=', $id)->first();

        TestPresenceEvent::dispatch($user, $roomId);
    });
});
