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
        } else if ($phaseNumber == 3) {
            $view = PhaseController::phase03View($project, $phaseNumber, $userRole);
        } else if ($phaseNumber == 4) {
            $view = PhaseController::phase04View($project, $phaseNumber, $userRole);
        } else if ($phaseNumber == 5) {
            $view = PhaseController::phase05View($project, $phaseNumber, $userRole);
        } else if ($phaseNumber == 6) {
            $view = PhaseController::phase06View($project, $phaseNumber, $userRole);
        } else if ($phaseNumber == 7) {
            $view = PhaseController::phase07View($project, $phaseNumber, $userRole);
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
        } else if ($currentPhaseNumber == 4) {
            PhaseController::calculateCriteriaWeightVotes($request->projectId);
        } else if ($currentPhaseNumber == 5) {
            PhaseController::calculateScores($request->projectId);
            PhaseController::determineScorestatus($request->projectId);
        } else if ($currentPhaseNumber == 6) {
            PhaseController::calculateScoreStatusVotes($request->projectId);
        } else if ($currentPhaseNumber == 7) {
            PhaseController::calculateRequirementScore($request->projectId);
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
     * Open view third phase.
     */
    public function phase03View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 3) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            return view('phase.phase03', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit Weight of Criteria', 'role' => $userRole, 'users' => $users]);
        } else {
            $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();

            return view('phase.phase03', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit Weight of Criteria', 'role' => $userRole, 'criterias' => $criterias]);
        }
    }

    /**
     * Submit third phase.
     */
    public function phase03Submit(Request $request)
    {
        DB::table('criteriaweights')->updateOrInsert(
            ['idCriteria' => $request->criteria1, 'weight' => $request->weight1],
            ['idUser' => Auth::id()]
        );

        DB::table('criteriaweights')->updateOrInsert(
            ['idCriteria' => $request->criteria2, 'weight' => $request->weight2],
            ['idUser' => Auth::id()]
        );

        DB::table('criteriaweights')->updateOrInsert(
            ['idCriteria' => $request->criteria3, 'weight' => $request->weight3],
            ['idUser' => Auth::id()]
        );

        DB::table('criteriaweights')->updateOrInsert(
            ['idCriteria' => $request->criteria4, 'weight' => $request->weight4],
            ['idUser' => Auth::id()]
        );

        DB::table('criteriaweights')->updateOrInsert(
            ['idCriteria' => $request->criteria5, 'weight' => $request->weight5],
            ['idUser' => Auth::id()]
        );

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 3 submitted successfully');
    }

    /**
     * Open view fourth phase.
     */
    public function phase04View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 4) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            return view('phase.phase04', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote Weight', 'role' => $userRole, 'users' => $users]);
        } else {
            $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();
            
            $weights = [];

            foreach($criterias as $criteria) {
                $weights[] = DB::table('criteriaweights')->where('idCriteria', $criteria->id)->get();
            }
            
            return view('phase.phase04', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote Weight', 'role' => $userRole, 'criterias' => $criterias, 'weights' => $weights]);
        }
    }

    /**
     * Submit fourth phase.
     */
    public function phase04Submit(Request $request)
    {
        if ($request->weight1) {
            DB::table('criteriaweightvotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idWeight' => $request->weight1
            ]);
        }

        if ($request->weight2) {
            DB::table('criteriaweightvotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idWeight' => $request->weight2
            ]);
        }

        if ($request->weight3) {
            DB::table('criteriaweightvotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idWeight' => $request->weight3
            ]);
        }

        if ($request->weight4) {
            DB::table('criteriaweightvotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idWeight' => $request->weight4
            ]);
        }

        if ($request->weight5) {
            DB::table('criteriaweightvotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idWeight' => $request->weight5
            ]);
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 4 submitted successfully');
    }

    /**
     * Open view fifth phase.
     */
    public function phase05View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 5) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            return view('phase.phase05', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit Score', 'role' => $userRole, 'users' => $users]);
        } else {
            $requirements = DB::table('requirements')->where('idProject', $project->id)->get();

            $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();

            return view('phase.phase05', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Submit Score', 'role' => $userRole, 'requirements' => $requirements, 'criterias' => $criterias]);
        }
    }

    /**
     * Submit fifth phase.
     */
    public function phase05Submit(Request $request)
    {
        $requirements = DB::table('requirements')->where('idProject', $request->projectId)->get();

        for ($i = 1; $i <= count($requirements); $i++) {
            for ($j = 1; $j <= 5; $j++) {
                DB::table('userscores')->updateOrInsert([
                    'idUser' => Auth::id(),
                    'idRequirement' => $request->input('requirement-' . $i . '-' . $j),
                    'idCriteria' => $request->input('criteria-' . $i . '-' . $j),
                    'score' => $request->input('score-' . $i . '-' . $j)
                ]);
            }
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 5 submitted successfully');
    }

    /**
     * Open view sixth phase.
     */
    public function phase06View($project, $phaseNumber, $userRole)
    {
        if ($userRole == 1) {
            $members = DB::table('userprojects')->where('idProject', $project->id)->where('role', 2)->get();

            $users = [];
            foreach($members as $member) {
                $user = DB::table('users')->where('id', $member->idUser)->first();

                $status = "Done";
                if ($member->phase == 6) {
                    $status = "WIP";
                }

                $userWithStatus = array(
                    "name" => $user->name,
                    "status" => $status
                );
    
                $users[] = $userWithStatus;
            }

            $requirements = DB::table('requirements')->where('idProject', $project->id)->get();

            $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();

            $scores = [];

            foreach($requirements as $requirement) {
                foreach($criterias as $criteria) {
                    $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();

                    $scores[] = $score;
                }
            }

            return view('phase.phase06', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote for Unapproved Score', 'role' => $userRole, 'users' => $users, 'requirements' => $requirements, 'criterias' => $criterias, 'scores' => $scores]);
        } else {
            $requirements = DB::table('requirements')->where('idProject', $project->id)->get();

            $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();

            $scores = [];
            $isAllApproved = true;

            foreach($requirements as $requirement) {
                foreach($criterias as $criteria) {
                    $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();

                    if ($score->status == 0) {
                        $isAllApproved = false;
                    }

                    $scores[] = $score;
                }
            }

            return view('phase.phase06', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Vote for Unapproved Score', 'role' => $userRole, 'requirements' => $requirements, 'criterias' => $criterias, 'scores' => $scores, 'isAllApproved' => $isAllApproved]);
        }
    }

    /**
     * Submit sixth phase.
     */
    public function phase06Submit(Request $request)
    {
        foreach($request->scoreId as $index => $id) {
            DB::table('scorevotes')->updateOrInsert([
                'idUser' => Auth::id(),
                'idScore' => $id,
                'vote' => $request->scoreVal[$index]
            ]);
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 6 submitted successfully');
    }

    /**
     * Open view seventh phase.
     */
    public function phase07View($project, $phaseNumber, $userRole)
    {
        $requirements = DB::table('requirements')->where('idProject', $project->id)->get();

        $criterias = DB::table('criterias')->where('idProject', $project->id)->where('used', 1)->get();

        $scores = [];
        $isAllApproved = true;

        foreach($requirements as $requirement) {
            foreach($criterias as $criteria) {
                $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();

                if ($score->status == 0) {
                    $isAllApproved = false;
                }

                $scores[] = $score;
            }
        }

        return view('phase.phase07', ['project' => $project, 'phaseNumber' => $phaseNumber, 'phaseName' => 'Discuss Unapproved Score', 'role' => $userRole, 'requirements' => $requirements, 'criterias' => $criterias, 'scores' => $scores, 'isAllApproved' => $isAllApproved]);
    }

    /**
     * Submit seventh phase.
     */
    public function phase07Submit(Request $request)
    {
        foreach($request->scoreId as $index => $id) {
            $affected = DB::table('scores')->where('id', $id)->update(['status' => 1]);
            $affected = DB::table('scores')->where('id', $id)->update(['score' => $request->scoreVal[$index]]);
        }

        DB::table('userprojects')->where('idUser', Auth::id())->increment('phase');

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Phase 7 submitted successfully');
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

    /**
     * Calculate criteria weight votes from fourth phase.
     */
    public function calculateCriteriaWeightVotes($projectId) 
    {
        $criterias = DB::table('criterias')->where('idProject', $projectId)->where('used', 1)->get();
            
        $weights = [];

        foreach($criterias as $criteria) {
            $weights[] = DB::table('criteriaweights')->where('idCriteria', $criteria->id)->get();
        }

        $weightVotes = DB::table('criteriaweightvotes')->get();

        $finalWeight = [];

        foreach($weights as $weight) {
            $votes = [];

            foreach($weight as $singleWeight) {
                $vote = 0;

                foreach($weightVotes as $weightVote) {
                    if ($singleWeight->id == $weightVote->idWeight) {
                        $vote += 1;
                    }
                }

                $votes[] = $vote;
            }

            $id = -1;
            $max = -1;

            for ($j = 0; $j < count($votes); $j++) {
                if ($max <= $votes[$j]) {
                    $id = $j;
                    $max = $votes[$j];
                }
            }

            $finalWeight[] = $weight[$id]->id;
        }

        foreach($finalWeight as $final) {
            $weight = DB::table('criteriaweights')->where('id', $final)->first();

            $affected = DB::table('criterias')->where('id', $weight->idCriteria)->update(['weight' => $weight->weight]);
        }
    }

    /**
     * Calculate scores from fifth phase.
     */
    public function calculateScores($projectId) 
    {
        $requirements = DB::table('requirements')->where('idProject', $projectId)->get();

        $criterias = DB::table('criterias')->where('idProject', $projectId)->where('used', 1)->get();
            
        foreach($requirements as $requirement) {
            foreach($criterias as $criteria) {
                $scores = DB::table('userscores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->get();
            
                $sumScore = 0;
                foreach($scores as $score) {
                    $sumScore += $score->score;
                }

                $avgScore = $sumScore / count($scores);

                DB::table('scores')->updateOrInsert([
                    'idRequirement' => $requirement->id,
                    'idCriteria' => $criteria->id,
                    'score' => $avgScore,
                    'status' => 0
                ]);
            }
        }
    }

    /**
     * Determine score status from fifth phase.
     */
    public function determineScoreStatus($projectId) 
    {
        $requirements = DB::table('requirements')->where('idProject', $projectId)->get();

        $criterias = DB::table('criterias')->where('idProject', $projectId)->where('used', 1)->get();
        
        $countMembers = DB::table('userprojects')->where('idProject', $projectId)->where('role', 2)->count();

        $agreementLimit = $countMembers * 2 / 3;
        $threshold = 1;

        foreach($requirements as $requirement) {
            foreach($criterias as $criteria) {
                $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();
            
                $userScores = DB::table('userscores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->get();

                $agreementCount = 0;
                $minVal = $score->score - $threshold;
                $maxVal = $score->score + $threshold;
                
                foreach($userScores as $userScore) {
                    if (($minVal <= $userScore->score) && ($userScore->score <= $maxVal)) {
                        $agreementCount += 1;
                    }
                }

                if ($agreementCount >= $agreementLimit) {
                    $affected = DB::table('scores')->where('id', $score->id)->update(['status' => 1]);
                }
            }
        }
    }

    /**
     * Calculate criteria weight votes from fourth phase.
     */
    public function calculateScoreStatusVotes($projectId) 
    {
        $requirements = DB::table('requirements')->where('idProject', $projectId)->get();

        $criterias = DB::table('criterias')->where('idProject', $projectId)->where('used', 1)->get();
        
        $scores = [];
        $countMembers = DB::table('userprojects')->where('idProject', $projectId)->where('role', 2)->count();
        $agreementLimit = $countMembers * 2 / 3;

        foreach($requirements as $requirement) {
            foreach($criterias as $criteria) {
                $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->where('status', 0)->first();

                if($score) {
                    $scores[] = $score;
                }
            }
        }

        $scoreVotes = DB::table('scoreVotes')->get();

        $finalStatus = [];
        foreach($scores as $score) {
            $vote = 0;

            foreach($scoreVotes as $scoreVote) {
                if ($score->id == $scoreVote->idScore) {
                    $vote += $scoreVote->vote;
                }
            }

            if ($vote > $agreementLimit) {
                $affected = DB::table('scores')->where('id', $score->id)->update(['status' => 1]);
            }            
        }
    }

    /**
     * Calculate requirement score from seventh phase.
     */
    public function calculateRequirementScore($projectId) 
    {
        $requirements = DB::table('requirements')->where('idProject', $projectId)->get();

        $criterias = DB::table('criterias')->where('idProject', $projectId)->where('used', 1)->get();

        foreach($requirements as $requirement) {

            $requirementScore = 0;

            foreach($criterias as $criteria) {
                $criteriaScore = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();

                $requirementScore += $criteriaScore->score * $criteria->weight;
            }

            $affected = DB::table('requirements')->where('id', $requirement->id)->update(['score' => $requirementScore]);
        }
    }
}
