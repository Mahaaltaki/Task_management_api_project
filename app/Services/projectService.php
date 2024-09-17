<?php
namespace App\Services;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;
use Request;
class projectService
{
    /*
     * @param Request $request 
     * @return array containing paginated project resources.
     */
    public function getAllProjects(): array
    {
        // query builder instance for the project model
        $query = Project::with('users');
        // Paginate the results
        $projects = $query->paginate(10);

        // Return the paginated projects wrapped in a projectResource collection
        return ProjectResource::collection($projects)->toArray(request());
    }

    /**
     * Store a new project.
     * @param array $data array containing 'name','description'.
     * @return array array containing the created project resource.
     * @throws \Exception
     * Throws an exception if the project creation fails */
    public function storeProject(ProjectRequest $request): array
    {
        // Create a new project
        $project = Project::create([
            'name' => $request['name'],
            'description' => $request['description'],
        ]);
        $project->users()->attach($request->user_id, [
            'role' => $request->role,
            'hours' => $request->hours,
            'last_activity' => now(),
        ]);

            $project->save();
        // if the project was created successfully
        if (!$project) {
            throw new \Exception('Failed to create project.');
        }

        // Return the created project as a resource
        return projectResource::make($project)->toArray(request());
    }

    /*Retrieve a specific project by its ID.
     * @param int $id of the project.
     * @return array containing the project resource.
     * @throws \Exception exception if the project is not found.*/
    public function showproject(int $id): array
    {
        // Find project by ID
        $project = Project::with('users')->findOrFail($id);

        $users = $project->users->map(function ($user) {
            return [
                'name' => $user->name,
                'role' => $user->pivot->role,  //to getting to the role in pivot table
                'hours' => $user->pivot->hours,  // the hours which contribute to it
                'last_activity' => $user->pivot->last_activity,  // the last activity
            ];
        });
        // If project is not found, throw an exception
        if (!$project) {
            throw new \Exception('project not found.');
        }

        // Return the found project
        return ProjectResource::make($project)->toArray(request());
    }

    /**
     * Update an project.
     * @param project $project
     * update The project model.
     * @param array $data array containing the fields to update (name,description).
     * @return array containing the updated project resource.
     */
    public function updateproject(ProjectRequest $request, array $data): array
    {
        // Update only the fields that are provided in the data array
        $request->update(array_filter([
            'title' => $data['title'] ?? $request->title,
            'description' => $data['description'] ?? $request->description,
        ]));
        
        // Return the updated project as a resource
        return ProjectResource::make($request)->toArray(request());
    }

    /**
     * Delete project by ID.
     * @param int $id of project to delete.
     * @return void
     * @throws \Exception an exception if the project is not found.
     */
    public function deleteproject(Request $request,int $id): void
    {
        // Find the project by ID
        $project = Project::findOrFail($id);

    // delete the user from the project
    $project->users()->detach($request->user_id);

        // If no project is found, throw an exception
        if (!$project) {
            throw new \Exception('project not found.');
        }
    }
    // to get the highest task based on title
    public function getHighestPriorityTaskWithCondition($projectId, $titleCondition)
    {
        // get the project 
        $project = Project::findOrFail($projectId);

        // get the highest task 
        $task = $project->highestPriorityTaskWithTitle($titleCondition)->first();
              // If task is not found, throw an exception
              if (!$task) {
                throw new \Exception('taskt not found.');
            }
    
            // Return the found tast
            return ProjectResource::make($task)->toArray(request());
        
}
}