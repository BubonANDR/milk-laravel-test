<?php

use Illuminate\Support\Facades\DB;
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

Route::get('limits', function () {
    
    $current_volume = DB::table('milks')
    ->select('bottle_num',DB::raw('SUM(volume) as s_volume'))
    ->groupBy('bottle_num')
    ->orderBy('bottle_num')
    ->get();
    

    return $current_volume;
});
