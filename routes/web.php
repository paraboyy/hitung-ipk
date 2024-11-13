<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IpkController;
use App\Http\Controllers\mahasiswaController;
use App\Http\Controllers\matakuliahController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/hitung-ipk/{nim}', [IpkController::class, 'hitungIpk']);
// Route::middleware('api')->post('/hitung-ipk', [IpkController::class, 'hitungIpk']);


//GET Master Data
Route::get('/ipk/{nim}', [IpkController::class, 'getIpk']);

//GET Master Mahasiswa
Route::get('/mahasiswa', [mahasiswaController::class, 'index']);

//GET Master Matakuliah
Route::get('/matakuliah', [matakuliahController::class, 'index']);

Route::post('/krs', [matakuliahController::class, 'store']);

