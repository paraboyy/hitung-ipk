<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matakuliah;

class matakuliahController extends Controller
{
    public function index(){

        $matakuliah = Matakuliah::all();

        return response()->json($matakuliah);
    }
}
