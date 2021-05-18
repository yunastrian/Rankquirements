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
        $currentPhaseNumber = DB::table('projects')->where('id', $request->projectId)->first()->phase;

        if ($currentPhaseNumber == 2) {
            PhaseController::calculateCriteriaVotes($request->projectId);
        }

        DB::table('projects')->where('id', $request->projectId)->increment('phase');

        DB::table('userprojects')->where('idProject', $request->projectId)->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase updated successfully');
    }

    /**
     * Open view first phase.
     */
    public function phase01View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 1) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            return view('phase.phase01', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole, 'users' => $users]);
        } else {
            return view('phase.phase01', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit value criteria candidate', 'role' => $userRole]);
        }
    }

    /**
     * Submit first phase.
     */
    public function phase01Submit(Request $request)
    {
        DB::table('criterias')->updateOrInsert([
            'idProject' => $request->projectId,
            'name' => $request->criteria1,
            'used' => 0
        ]);

        DB::table('criterias')->updateOrInsert([
            'idProject' => $request->projectId,
            'name' => $request->criteria2,
            'used' => 0
        ]);

        DB::table('criterias')->updateOrInsert([
            'idProject' => $request->projectId,
            'name' => $request->criteria3,
            'used' => 0
        ]);

        if ($request->criteria4 != null) {
            DB::table('criterias')->updateOrInsert([
                'idProject' => $request->projectId,
                'name' => $request->criteria4,
                'used' => 0
            ]);
        }

        if ($request->criteria5 != null) {
            DB::table('criterias')->updateOrInsert([
                'idProject' => $request->projectId,
                'name' => $request->criteria5,
                'used' => 0
            ]);
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 1 submitted successfully');
    }

    /**
     * Open view second phase.
     */
    public function phase02View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 2) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            return view('phase.phase02', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote Criteria', 'role' => $userRole, 'users' => $users]);
        } else {
            $criterias = DB::table('criterias')->where('idProject', $project->id)->get();
            
            return view('phase.phase02', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote Criteria', 'role' => $userRole, 'criterias' => $criterias]);
        }
    }

    /**
     * Submit second phase.
     */
    public function phase02Submit(Request $request)
    {
        if ($request->criteria1) {
            DB::table('criteriavotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idCriteria' => $request->criteria1
            ]);
        }

        if ($request->criteria2) {
            DB::table('criteriavotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idCriteria' => $request->criteria2
            ]);
        }

        if ($request->criteria3) {
            DB::table('criteriavotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idCriteria' => $request->criteria3
            ]);
        }

        if ($request->criteria4) {
            DB::table('criteriavotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idCriteria' => $request->criteria4
            ]);
        }

        if ($request->criteria5) {
            DB::table('criteriavotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idCriteria' => $request->criteria5
            ]);
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 2 submitted successfully');
    }

    /**
     * Calculate criteria votes from second phase.
     */
    public function calculateCriteriaVotes($projectId) 
    {
        $criterias = DB::table('criterias')->where('idProject', $projectId)->get();

        $criteriaVotes = DB::table('criteriavotes')->get();

        $votes = [];

        foreach($criterias as $criteria) {
            $vote = 0;

            foreach($criteriaVotes as $criteriaVote) {
                if ($criteria->id == $criteriaVote->idCriteria) {
                    $vote += 1;
                }
            }

            $votes[] = $vote;
        }

        $finalCriteria = [];

        for ($i = 0; $i < 5; $i++) {
            $id = -1;
            $max = -1;

            for ($j = 0; $j < count($votes); $j++) {
                if ($max <= $votes[$j]) {
                    $id = $j;
                    $max = $votes[$j];
                }
            }

            $finalCriteria[] = $criterias[$id]->id;
            $votes[$id] = -2;
        }

        for ($i = 0; $i < count($finalCriteria); $i++) {
            $affected = DB::table('criterias')->where('id', $finalCriteria[$i])->update(['used' => 1]);
        }
    }
}
