<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IpkController;

// Route::get('/', function () {
//     return view('welcome');
// });

// routes/web.php
Route::middleware('api')->post('/hitung-ipk', [IpkController::class, 'hitungIpk']);
Route::get('/ipk/{nim}', [IpkController::class, 'getIpk']);