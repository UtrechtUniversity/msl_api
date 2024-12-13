<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seed;
use App\Models\Seeder;
use App\Jobs\ProcessSeed;

class SeederController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $seeders = Seeder::all();
                
        return view('admin.seeders', ['seeders' => $seeders]);
    }
    
    public function seederSeeds($id)
    {
        $seeder = Seeder::where('id', $id)->first();
        
        if($seeder) {
            $seeds = $seeder->seeds;        
            return view('admin.seeder-seeds', ['seeder' => $seeder, 'seeds' => $seeds]);
        }
        abort(404, 'Invalid data requested');
    }
    
    public function createseed(Request $request)
    {
        if($request->has('seeder-id')) {
            $seederId = $request->input('seeder-id');
            
            $seed = Seed::create([
                'seeder_id' => $seederId
            ]);
            
            ProcessSeed::dispatch($seed);           
            
            $request->session()->flash('status', 'Seeder started');
        }
        
        return redirect()->route('seeders');
    }
    
    public function seeds($id) 
    {
        $seed = Seed::where('id', $id)->first();
        if($seed) {
            $creates = $seed->creates;
            return view('admin.seeds', ['seed' => $seed, 'creates' => $creates]);
        }
        
        abort(404, 'Invalid data requested');
    }
    
}
