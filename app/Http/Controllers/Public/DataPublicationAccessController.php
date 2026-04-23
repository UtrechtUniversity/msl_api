<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataPublicationAccessController extends Controller
{
    public function index(Request $request): View
    {

        return view('public.dataPublication-map');
    }

    public function indexDeprecated(Request $request): View
    {

        return view('public.dataPublication-map_DEPRECATED');
    }
}
