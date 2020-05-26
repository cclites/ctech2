<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $projects = \App\Project::all();
        return response()->json(['projects'=>$projects]);
    }
}
