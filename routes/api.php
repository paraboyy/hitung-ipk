<?php

use App\Http\Controllers\IpkController;

Route::post('/hitung-ipk', [IpkController::class, 'hitungIpk']);
Route::get('/ipk/{nim}', [IpkController::class, 'getIpk']);