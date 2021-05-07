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
            $view = PhaseController::firstPhaseView($idProject, $phaseNumber);
        } else if ($phaseNumber == 2) {
            $view = PhaseController::secondPhaseView($idProject, $phaseNumber);
        }

        return $view;
    }

    /**
     * Open view first phase.
     */
    public function firstPhaseView($idProject, $phaseNumber)
    {
        $project = DB::table('projects')->where('id', $idProject)->first();

        return view('phase.firstPhase', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate']);
    }

    /**
     * Open view second phase.
     */
    public function secondPhaseView($idProject, $phaseNumber)
    {
        $project = DB::table('projects')->where('id', $idProject)->first();

        return view('phase.secondPhase', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Voting value criteria']);
    }
}
