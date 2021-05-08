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

        $project = DB::table('projects')->where('id', $idProject)->first();
        $userRole = DB::table('userprojects')->where('idProject', $idProject)->where('idUser', Auth::id())->first()->role;

        if ($project->phase < $phaseNumber) {
            abort(404);
        }

        if ($phaseNumber == 1) {
            $view = PhaseController::phase01View($project, $phaseNumber, $userRole);
        } else if ($phaseNumber == 2) {
            $view = PhaseController::phase02View($project, $phaseNumber, $userRole);
        } else {
            abort(404);
        }

        return $view;
    }

    /**
     * Update phase
     */
    public function updatePhase(Request $request)
    {
        DB::table('projects')->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase updated successfully');
    }

    /**
     * Open view first phase.
     */
    public function phase01View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $results = "Hawo";

            return view('phase.phase01', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole, 'results' => $results]);
        } else {
            return view('phase.phase01', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole]);
        }
    }

    /**
     * Open view second phase.
     */
    public function phase02View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $results = "Hawo";

            return view('phase.phase02', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole, 'results' => $results]);
        } else {
            return view('phase.phase02', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole]);
        }
    }
}
