<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class mahasiswaController extends Controller
{
    public function index(){

        $mahasiswa = mahasiswa::all();

        return response()->json($mahasiswa);
    }
}
