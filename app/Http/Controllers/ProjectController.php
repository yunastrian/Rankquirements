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
        $project = DB::table('projects')->where('id', $id)->first();
        $requirements = DB::table('requirements')->where('idProject', $id)->get();
        $role = DB::table('userprojects')->where('idProject', $id)->where('idUser', Auth::id())->first()->role;
        $moderatorId = DB::table('userprojects')->where('idProject', $id)->where('role', 1)->first()->idUser;
        $memberIds = DB::table('userprojects')->where('idProject', $id)->where('role', 2)->pluck('idUser');
        $userPhase = DB::table('userprojects')->where('idProject', $id)->where('idUser', Auth::id())->first()->phase;

        $criterias = DB::table('criterias')->where('idProject', $id)->where('used', 1)->get();

        $scores = [];

        foreach($requirements as $requirement) {
            foreach($criterias as $criteria) {
                $score = DB::table('scores')->where('idRequirement', $requirement->id)->where('idCriteria', $criteria->id)->first();

                if ($score) {
                    $scores[] = $score;   
                }
            }
        }

        $moderatorName = DB::table('users')->where('id', $moderatorId)->first()->name;

        $memberNames = [];

        $users = DB::table('users')->get();

        foreach($memberIds as $memberId) {
            $memberName = DB::table('users')->where('id', $memberId)->first()->name;

            $memberNames[] = $memberName;
        }

        $pickableUsers = [];

        foreach($users as $user) {
            $flag = 0;

            if($user->id == $moderatorId) {
                $flag = 1;
            } 
            
            foreach($memberIds as $memberId) {
                if ($memberId == $user->id)
                $flag = 1;
            }


            if($flag == 0) {
                $pickableUsers[] = $user;
            }
        }

        return view('project', ['project' => $project, 'requirements' => $requirements, 'criterias' => $criterias, 'scores' => $scores, 'id' => $id, 'role' => $role, 'userPhase' => $userPhase, 'moderator' => $moderatorName, 'members' => $memberNames, 'users' => $pickableUsers, 'maxPhase' => 7]);
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
            'role' => 1,
            'phase' => 0
        ]);

        return redirect()->route('home')->with('msg', 'Project added successfully');
    }

    /**
     * Add new member
     */
    public function addMember(Request $request)
    {
        DB::table('userprojects')->insert([
            'idUser' => $request->userId,
            'idProject' => $request->projectId,
            'role' => 2,
            'phase' => 1
        ]);

        return redirect()->route('project', ['id' => $request->projectId])->with('msg', 'Member added successfully');
    }
}
