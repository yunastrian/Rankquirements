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
        $role = DB::table('userprojects')->where('idProject', $id)->where('idUser', Auth::id())->first()->role;
        $moderatorId = DB::table('userprojects')->where('idProject', $id)->where('role', 1)->first()->idUser;
        $memberIds = DB::table('userprojects')->where('idProject', $id)->where('role', 2)->pluck('idUser');

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

        return view('project', ['requirements' => $requirements, 'id' => $id, 'role' => $role, 'moderator' => $moderatorName, 'members' => $memberNames, 'users' => $pickableUsers]);
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

    /**
     * Add new member
     */
    public function addMember(Request $request)
    {
        DB::table('userprojects')->insert([
            'idUser' => $request->userId,
            'idProject' => $request->projectId,
            'role' => 2
        ]);

        return redirect()->route('project', ['id' => $request->projectId, 'msg' => 2]);
    }
}
