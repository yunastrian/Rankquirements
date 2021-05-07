<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PhaseController extends Controller
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
    public function index($idProject, $phaseNumber)
    {
        $view;

        if ($phaseNumber == 1) {
            $view = PhaseController::phase01View($idProject, $phaseNumber);
        } else if ($phaseNumber == 2) {
            $view = PhaseController::phase02View($idProject, $phaseNumber);
        } else {
            abort(404);
        }

        return $view;
    }

    /**
     * Open view first phase.
     */
    public function phase01View($idProject, $phaseNumber)
    {
        $project = DB::table('projects')->where('id', $idProject)->first();

        return view('phase.phase01', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate']);
    }

    /**
     * Open view second phase.
     */
    public function phase02View($idProject, $phaseNumber)
    {
        $project = DB::table('projects')->where('id', $idProject)->first();

        return view('phase.phase02', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Voting value criteria']);
    }
}
