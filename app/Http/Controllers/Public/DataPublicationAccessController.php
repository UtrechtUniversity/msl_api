<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataPublicationAccessController extends Controller
{
    public function index(Request $request): View
    {

        return view('frontend.dataPublication-map');
    }
}
