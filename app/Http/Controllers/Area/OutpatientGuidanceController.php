<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OutpatientGuidanceController extends Controller
{
    public function index()
    {
        return view('areas.outpatient-guidance.index');
    }
}
