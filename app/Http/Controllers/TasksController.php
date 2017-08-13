<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;    // 追加
use App\User;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tasks = Task::all();

        //return view('tasks.index', [
        //    'tasks' => $tasks,
        //]);
        
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        return view('welcome', $data);
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        return view('tasks.create', [
            'task' => $task,
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
        //$this->validate($request, [
        //    'status' => 'required|max:10',   // 追加
        //]);
        
        //$task = new Task;
        //$task->status = $request->status;    // 追加
        //$task->content = $request->content;
        //$task->save();
        
        $this->validate($request, [
            'content' => 'required|max:255',
        ]);
        
        $tasks = new Task;
        //dd($request->status);
        $tasks = $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,    
        ]);
        $tasks->save();

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);

        return view('tasks.show', [
            'task' => $task,
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
        
        $task = Task::find($id);
        
        if (\Auth::user()->id !== $task->user_id) {
            return redirect('/');
        }

        return view('tasks.edit', [
            'task' => $task,
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
        
        $this->validate($request, [
            'status' => 'required|max:10',   // 追加
        ]);
        
        $task = Task::find($id);
        
        if (\Auth::user()->id !== $task->user_id) {
            return redirect('/');
        }
     
     
        $task->status = $request->status;    // 追加
        $task->content = $request->content;
        $task->save();

        return redirect('/');
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
        
        if (\Auth::user()->id !== $task->user_id) {
            return redirect('/');
        }
        
        $task->delete();

        return redirect('/');
    }
}
