<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Notifications\TaskAssigned;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TaskCompleted;

class TaskController extends Controller
{
    public function __construct()
    {

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=  Auth::user();

        return view('task.index', [
            'tasks' => Task::latest()->filter(['search', 'searchbody'])->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task.create', [
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        /* We can also implement this validation for validating the field and defined the rules in model (Task.php) and return the json response
                 $validator = Validator::make($request->all(), Task::$rules);

         if ($validator->fails()) {
             return response()->json(['errors' => $validator->errors()], 400);
         }
                */
                
        if (auth()->user()->hasPermission('create_task')) {
                $attributes = $this->validateTask($request);
                $attributes['task_created_by'] =  Auth::user()->id;
                $attributes['is_completed'] = 0;
                $attributes['slug'] = Str::slug($request->title);
                $task = Task::create($attributes);

        $this->notifyUser($task->assigned_user_id);

        return redirect('/')->with('success', 'Task updated and assigned user notified by email');}
    
        else{
            abort(403, 'Unauthorized action.');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('task.show', [
            'task' => Task::find($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('task.edit', [
            'task' => Task::find($id),
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->hasPermission('update_task')) {
            $attributes = $this->validateTask($request);
            $task =  Task::find($id);
            $attributes['task_created_by'] = Auth::user()->id;
            $attributes['is_completed'] = 0;
            $attributes['slug'] = Str::slug($request->title);
            $task->save($attributes);

            $this->notifyUser($task->assigned_user_id);

        return redirect('/task')->with('success', 'Task updated and assigned user notified by email');
        }
        else
        {
            abort(403, 'Unauthorized action.');   
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        return redirect('/task')->with('success', 'Task Deleted');
    }

    public function validateTask(Request $request)
    {
        $attributes = $request->validate([
            'title' => 'required',
            'deadline' => 'required',
            'description' => 'required',
            'assigned_user_id' => ['required', Rule::exists('users', 'id')]
        ]);

        return $attributes;
    }

    public function completed($id)
    {
       

        $task = Task::find($id);
        $task->is_completed = 1;
        $task->update();
        $users = User::where('id', $task->assigneduser_id )
                        ->orWhere('id',$task->task_created_by)
                        ->get();
        Notification::send($users, new TaskCompleted($task));
        return redirect('/task')->with('success', 'Task marked completed');
    }

    public function notifyUser($assignedUserId)
    {
        $task = Task::where('assigned_user_id',$assignedUserId)->first();
        $user = User::where('id', $assignedUserId)->first();
        $user->notify(new TaskAssigned($task));

        return back()->with('success', 'Task notification email has been sent to the assigned user');
    }

}
