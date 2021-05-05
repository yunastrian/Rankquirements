<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
        $userProjects = DB::table('userprojects')->where('idUser', Auth::id())->get();

        $projects = [];
        foreach($userProjects as $userProject) {
            $projectName = DB::table('projects')->where('id', $userProject->idProject)->first();

            $role = "Moderator";
            if ($userProject->role == 2) {
                $role = "Member";
            }

            $project = array(
                "id" => $userProject->idProject,
                "name" => $projectName->name,
                "role" => $role
            );

            $projects[] = $project;
        }

        return view('home', ['projects' => $projects]);
    }
}
