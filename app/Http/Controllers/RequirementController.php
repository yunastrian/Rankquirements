<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequirementController extends Controller
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
     * Add new requirement
     */
    public function add(Request $request)
    {
        $requirements = DB::table('requirements')->where('idProject', $request->projectId)->get();

        DB::table('requirements')->insert([
            'idProject' => $request->projectId,
            'number' => count($requirements),
            'name' => $request->requirementName,
            'score' => null
        ]);

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Requirement added successfully');
    }
}
