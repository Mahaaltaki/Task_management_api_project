<?php
namespace App\Services;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;

use App\Models\Project;
use App\Models\Task;
class taskService
{
    /*
     * @param Request $request 
     * @return array containing paginated task resources.
     */
    public function getAllTasks(): array
    {
        // query builder instance for the task model
        $query = Task::with('projects');
        // Paginate the results
        $tasks = $query->paginate(10);

        // Return the paginated tasks wrapped in a taskResource collection
        return TaskResource::collection($tasks)->toArray(request());
    }

    /**
     * Store a new task.
     * @param array $data array containing 'title','description','status', 'priority',
      *  'due_date','project_id'
     * @return array array containing the created task resource.
     * @throws \Exception
     * Throws an exception if the task creation fails */
    public function storeTask(TaskRequest $request,array $data ): array
    {//$data = $request->validated();

        // Create a new task
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'priority' => $data['priority'],
            'due_date' => $data['due_date'],
            'project_id' => $data['project_id'],
            
        ]);
      

            $task->save();
        // if the task was created successfully
        if (!$task) {
            throw new \Exception('Failed to create task.');
        }

        // Return the created task as a resource
        return taskResource::make($task)->toArray(request());
    }

    /*Retrieve a specific task by its ID.
     * @param int $id of the task.
     * @return array containing the task resource.
     * @throws \Exception exception if the task is not found.*/
    public function showtask(int $id): array
    {
        // Find task by ID
        $task = Task::find($id);
        // If task is not found, throw an exception
        if (!$task) {
            throw new \Exception('task not found.');
        }

        // Return the found task
        return taskResource::make($task)->toArray(request());
    }

    /**
     * Update an task.
     * @param Task $task
     * update The task model.
     * @param array $data array containing the fields to update ('title','description','status', 'priority',
      *  'due_date','project_id').
     * @return array containing the updated task resource.
     */
    public function updateTask(Task $task,  $data): array
    {
        // Update only the fields that are provided in the data array
        $task->update(array_filter([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status' => $data['status'] ?? $task->status,
            'priority' => $data['priority'] ?? $task->priority,
            'due_date' => $data['due_date'] ?? $task->due_date,
            'project_id' => $data['project_id'] ?? $task->project_id,
        
        ]));
        $task->save();
        // Return the updated task as a resource
        return taskResource::make($task)->toArray(request());
    }

    /**
     * Delete task by ID.
     * @param int $id of task to delete.
     * @return void
     * @throws \Exception an exception if the task is not found.
     */
    public function deletetask(int $id): void
    {
        // Find the task by ID
        $task = Task::find($id);

        // If no task is found, throw an exception
        if (!$task) {
            throw new \Exception('task not found.');
        }

        // Delete task
        $task->delete();
    }
    public function getUsersWithTasks(){
        $users=Project::with('getTheTasksThroughProject')->get();
    }
}
