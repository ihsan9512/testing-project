<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Validator;

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
        $tasks = Task::all();
        return view('home',compact('tasks'));
    }

    public function task($id = null)
    {
        $task = null;
        if (isset($id)) {
            $task = Task::find($id);
        }
        return view('taskAddEdit', compact('task', 'task'));
    }

    public function task_store(Request $request)
    {
        if(isset($request->id)){
            $validator = Validator::make($request->all(), [
                'name' =>'required|max:255',
                'details' => 'required|max:255',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'name' =>'required|unique:tasks,name|max:255',
                'details' => 'required|max:255',
            ]);
        }
        
        if ($validator->fails()) {
            $data= array(
                'status' => 'Error',
                'msg' =>  $validator->errors()
            );
            return response()->json($data, 200);
        }
        
        try{
            if(isset($request->id)){
                $task = Task::find($request->id);
            }else{
                $task = new Task();
            }
            $task->name = $request->name;
            $task->details = $request->details;
            $task->save();


            $status = 'Success';
            $msg = 'Task Saved Successfully';
            if(isset($request->id)){
                $msg = 'Task updated Successfully';
            }

        }catch(\Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();
        }
        $data= array(
            'status' => $status,
            'msg' => $msg
        );
        return response()->json($data, 200);
    }

    public function task_delete(Request $request)
    {
        Task::destroy([$request->id]);
        $data= array(
            'status' => 'Success',
            'msg' => 'Task Deleted Successfully'
        );
        return response()->json($data, 200);
    }
}
