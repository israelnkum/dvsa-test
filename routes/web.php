<?php

use App\Models\Role;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/not', function () {
// Get the authenticated user
    $user = Role::find(3);

// Get notifications for users with the 'admin' role
    $notifications = $user->unreadNotifications->filter(function ($notification) {
        // Check if the user has the 'admin' role
        return $notification;
    });

    return response()->json($notifications);
});
//Route::post('broadcasting/auth', [BroadcastController::class, 'authenticate']);
//Route::resource('interviews', InterviewController::class);
