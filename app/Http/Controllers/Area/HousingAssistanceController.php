<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HousingAssistanceController extends Controller
{
    public function index()
    {
        return view('areas.housing-assistance.index');
    }
}
