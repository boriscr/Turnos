<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;

class HomeController extends Controller
{
    public function index()
    {
        $specialties = Specialty::select('name', 'description')->get();
        return view('home', compact('specialties'));
    }
}
