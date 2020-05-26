<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskUpdateRequest;
use App\Http\Requests\TaskCreateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request){

        if($request->json){
            $tasks = $this->getTasks();
            return response()->json($tasks);
        }
        return view('welcome');
    }

    /**
     * @param TaskCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskCreateRequest $request)
    {
        $maxPriority = \App\Task::max('priority');

        $data = $request->all();
        $data['priority'] = ++$maxPriority;

        Task::create($data);

        $tasks = $this->getTasks();
        return response()->json($tasks);
    }

    /**
     * @param TaskUpdateRequest $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $task->update($request->all());

        $tasks = $this->getTasks();
        return response()->json($tasks);
    }

    /**
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        $task->delete();

        $tasks = $this->getTasks();
        return response()->json($tasks);
    }

    /**
     * TODO: Consider project id when finding the task with the
     *   next prio.
     *
     * @param Request $request
     */
    public function setPriority(Request $request){

        $taskId = $request->id;
        $direction = $request->direction;

        $task1 = Task::find($taskId);
        $prio1 = $task1->priority;

        $task2 = Task::where('priority', ($prio1 + $direction))->first();
        $prio2 = $task2->priority;

        $task1->update(['priority'=>$prio2]);
        $task2->update(['priority'=>$prio1]);

        $tasks = $this->getTasks();
        return response()->json($tasks);
    }

    private function getTasks(){

        $tasks = Task::all()->load('project')
            ->map(function ($task){
                return [
                    'name' => $task->name,
                    'project' => $task->project->name,
                    'created' => Carbon::now()->format('m-d-Y'),
                    'priority' => $task->priority,
                    'project_id' => $task->project_id,
                    'id' => $task->id
                ];
            })->sortBy('priority', true);

        return array_values($tasks->toArray());
    }
}
