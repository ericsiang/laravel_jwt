<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        
        $this->user = JWTAuth::parseToken()->authenticate();
        //dd($this->user);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $tasks = $this->user->tasks()->get(['id','title', 'description'])->toArray();

        if (!$tasks) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, you don\'t have any task be found.'
            ], 400);
        }
        return $tasks;
    }


     /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        return $task;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
        ]);

        $input=$request->only('title','description'); 
        $task=$this->user->tasks()->create($input);

        /*
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $this->user->tasks()->save($task)
        */
        if ($task)
            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be added.'
            ], 500);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

       // $updated = $task->fill($request->all())->save();
       //dd($request->only('title','description'));
       $updated =$task->fill($request->only('title','description'))->save();
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Update task with id '.$id.' success'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task could not be updated.'
            ], 500);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $task = $this->user->tasks()->find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, task with id ' . $id . ' cannot be found.'
            ], 400);
        }

        if ($task->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Delete task success',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Task could not be deleted.'
            ], 500);
        }
    }




}
