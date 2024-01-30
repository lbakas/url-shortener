<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get(env('FOLDER') ? '/'.env('FOLDER').'/{hash}' : '/{hash}', function ($hash) {
    $url = DB::table('urls')->where('hash', $hash)->first();
    if ($url) {
        return redirect($url->url);
    } else {
        abort(404);
    }
});