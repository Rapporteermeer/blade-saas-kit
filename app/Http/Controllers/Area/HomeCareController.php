<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeCareController extends Controller
{
    public function index()
    {
        return view('areas.home-care.index');
    }
}
