<?php
namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all()->map(function ($project) {
            return [
                'name' => $project->name,
                'description' => $project->description,
                // Return full URL for frontend to render the image
                'image_url' => asset('storage/' . $project->image_url)
            ];
        });

        return response()->json($projects);
    }

    
}
