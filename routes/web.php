<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    Redis::set("key1", "value1" );

//    print_r(Redis::get("key1"));

//    return print_r(app()->make("redis"));
    return view('welcome');
});
