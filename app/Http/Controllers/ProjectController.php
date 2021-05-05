<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
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
     */
    public function index($id)
    {
        $requirements = DB::table('requirements')->where('idProject', $id)->get();

        return view('project', ['requirements' => $requirements, 'id' => $id]);
    }

    /**
     * Add new project
     */
    public function add(Request $request)
    {
        $id = DB::table('projects')->insertGetId([
            'name' => $request->projectName,
            'phase' => 0
        ]);

        DB::table('userprojects')->insert([
            'idUser' => Auth::id(),
            'idProject' => $id,
            'role' => 1
        ]);

        return redirect()->route('home', ['msg' => 1]);
    }
}
