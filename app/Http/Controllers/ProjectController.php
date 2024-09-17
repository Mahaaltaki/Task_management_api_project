<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\projectService;
use Illuminate\Support\Facades\Log;
use App\Http\Trait\ApiResponceTrait;
use App\Http\Requests\ProjectRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProjectController extends Controller
{protected $projectService;
    use ApiResponceTrait;
    public function __construct(projectService $projectService)
    {
        $this->projectService = $projectService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return $this->successResponse($projects, 'bring all projects successfully.', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, ' error with bring all projects.');
        }
    }

    
     /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request )
{
    try {
        $project = $this->projectService->storeProject($request);
        return $this->successResponse($project, 'The project stored successfully', 201);
    } catch (\Exception $e) {
        return $this->handleException($e, 'Error storing the project');
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
        
            $project=$this->projectService->showProject($id);
            return $this->successResponse($project,'the project has been showing successfuly',200);
        }catch(\Exception $e){
         return $this->handleException($e,'error with showing the project');
        }
    }
   
        /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, $id)
    {
    try {
        $project = Project::findOrFail($id);
        $validated = $request->validated();
        $updatedproject = $this->projectService->updateproject($project, $validated);
        return $this->successResponse($updatedproject, 'The project updated successfully', 200);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $this->notFound('The project not found');
    } catch (\Exception $e) {
        return $this->handleException($e, 'Error updating the project');
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id):JsonResponse
    {
         try {
            $this->projectService->deleteproject( $request
             ,$id);
            return $this->successResponse([], 'the book deleted successfully.', 200);
        } catch (\Exception $e) {
            return $this->handleException($e, 'error with deleting the book');
        }
    }
    /**
     * Handle exceptions and show a response.
     */
    protected function handleException(\Exception $e, string $message): JsonResponse
    {
        // Log the error with additional context if needed
        Log::error($message, ['exception' => $e->getMessage(), 'request' => request()->all()]);

        return $this->errorResponse($message, [$e->getMessage()], 500);
    }
}
