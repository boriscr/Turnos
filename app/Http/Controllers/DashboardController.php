<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gender;

class DashboardController extends Controller
{
    public function index()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('dashboard.index', compact('settings'));
    }

    public function design()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('dashboard.design', compact('settings'));
    }
    public function general()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('dashboard.general', compact('settings'));
    }
    public function appointment()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('dashboard.appointment', compact('settings'));
    }
    public function privacy()
    {
        $settings = app('settings'); // o Setting::all()->pluck('value', 'key')
        return view('dashboard.privacy', compact('settings'));
    }
   
}
