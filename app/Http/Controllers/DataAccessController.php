<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DataAccessController extends Controller
{
    public function index(Request $request): View
    {

        return view('frontend.dp-map');
    }
}
