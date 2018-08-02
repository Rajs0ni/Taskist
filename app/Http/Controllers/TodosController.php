<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Requests;
use Carbon\Carbon;
use Illuminate\Html\HtmlServiceProvider;
<<<<<<< HEAD
use Illuminate\Support\Facades\Input;
=======
>>>>>>> f3f843ddd072743de97f769763d172ad8034713c
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // Home Page
    public function index()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                    ->where('trashed','=','0')
                    ->where('archive','=','0')
                    ->orderBy('pin','desc')
                    ->orderBy('created_at','desc')
                        ->get();
                        $pinned = DB::table('todos')->where('pin',1)->get();
                        $unpinned = DB::table('todos')->where('pin',0)->get();
        return view('todo.index',compact('todos','pinned','unpinned'));
    }
    // public function myorder(){
    //     $todos = Todo::where('user_id','=',auth()->user()->id)
    //                 ->where('trashed','=','0')
    //                 ->where('archive','=','0')
    //                 ->orderBy('order','asc')
    //                     ->get();
    //     return view('todo.myorder',compact('todos'));
    // }
    public function order(){
        $i = 0;

        foreach ($_POST['item'] as $value) {
            // Execute statement:
            // UPDATE [Table] SET [Position] = $i WHERE [EntityId] = $value
            $i++;
            DB::table('todos')->where('id', '=', $value)->update([ 'order' => $i ]);
        }   
    }
    public function all()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                    ->where('trashed','=','0')
                    ->orderBy('pin','desc')
                    ->orderBy('created_at','desc')
                    ->get();
                    $pinned = DB::table('todos')->where('pin',1)->get();
                    $unpinned = DB::table('todos')->where('pin',0)->get();
        return view('todo.index',compact('todos','pinned','unpinned'));
    }
    // Create New Task
    public function create()
    {
        return view('todo.create');
    }
    // Store Task
    public function store(Request $request)
    {
        $this->validate($request,[

            'title' => 'required',
            'task' => 'required',
            'completion_date' => 'required|date|after_or_equal:today'
        ]);

        $todo = new Todo;
        $todo->title = $request->input('title');
        $todo->task = $request->input('task');
        $todo->date_created = Carbon::now();
        $todo->completion_date = $request->input('completion_date');
        $todo->user_id=Auth::id();
        $todo->save();
        return redirect('/')->with([
            'flash_message' => 'Task has been created!'
        ]);

    }
    // Show a particular task
    public function show($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                      ->findOrFail($id);
        return view('todo.show',compact('todo'));
    }
    // Grid Show
    public function gridshow($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                      ->findOrFail($id);
        return view('todo.gridshow',compact('todo'));
    }
    // Edit task
    public function edit($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                        ->findOrFail($id);
        return view('todo.edit',compact('todo')); 
    }
    // Update task
    public function update($id, Request $request)
    {
        $this->validate($request,[

            'title' => 'required',
            'task' => 'required',
            'completion_date' => 'required|date|after_or_equal:today'
        ]);

        $todo = Todo::where('user_id','=',auth()->user()->id)
                        ->findOrFail($id);
        $todo->update($request->all());
        return redirect()->action('TodosController@show',$todo->id);;
    }
    // Delete a particular task
    public function deleteTask($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                        ->findOrFail($id);
        $todo->delete();
        return redirect('/')->with('alert','Task Deleted!');
    }
    // Search task
    public function search()
    {
        return view('todo.search');
    }
    // Find the task
    public function find(Request $request)
    {
        $this->validate($request,[

            'keyword' => 'required'
        ]);
        $keyword = $request->input('keyword');
        $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->search($keyword)->get();
        return view('todo.index',compact('todos'));
    }
    // Delete all tasks
    public function clearall(Request $request)
    {
        $val=request('val');
        if($val==1){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                           ->get();
            foreach($todos as $todo){
                $todo->trashed=1;
                $todo->save();
            }
            return redirect('/')->with('alert','All the tasks have been trashed!');
        }
       
        $todos = Todo::where('user_id','=',auth()->user()->id)->get();

        foreach($todos as $todo){
            $todo->delete();
        }

        return redirect('/')->with('alert','All the tasks have been deleted!');
    }
    // Get completed tasks
    public function getCompleted()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->getCompleted()->get();
        return view('todo.index',compact('todos'));
    }
    // Get In Process tasks
    public function getProcessing()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->getProcessing()->get();
        return view('todo.index',compact('todos'));
    }
    // Get pending tasks
    public function getPending()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->getPending()->get();
        return view('todo.index',compact('todos'));
    }
    // Help User
    public function help()
    {
        return view('todo.help');
    }
    // Grid View
    public function gridview()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->orderBy('created_at','desc')->get();
        return view('todo.gridview',compact('todos'));
    }
    // Sort by Title
    public function sortByTitle()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->orderBy('title')->get();
        return view('todo.index',compact('todos'));
    }
    // Sort by Date
    public function sortByDate()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                    ->orderBy('date_created')->get();
        return view('todo.index',compact('todos'));
    }

    public function trashTask(Todo $todo){
        $todo->trashed=1;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been trashed!'
        ]);
    }

    public function pinTask(Todo $todo){
        $todo->pin=!$todo->pin;
        $todo->save();
        if($todo->pin==1)
        return back()->with([
            'flash_message' => 'Task has been pinned!'
        ]);
        if($todo->pin==0)
        return back()->with([
            'flash_message' => 'Task has been unpinned!'
        ]);
    }

    public function trash(){
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('trashed','=','1')
                        ->orderBy('created_at','desc')
                        ->get();
        return view('todo.trash',compact('todos'));
    }

    public function archived(){
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('archive','=','1')
                        ->where('trashed','=','0')
                        ->orderBy('pin','desc')
                        ->orderBy('created_at','desc')
                        ->get();
        return view('todo.archive',compact('todos'));
    }

    public function unarchive(Todo $todo){
        $todo->archive=0;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been unarchived!'
        ]);
    }

    public function archiveTask(Todo $todo){
        $todo->archive=1;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been archived!'
        ]);
    }

    public function restore(Todo $todo){
        $todo->trashed=0;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been restored!'
        ]);
    }
//     public function color($id)
//     {
//       $todos = Todo::findOrFail($id);
//       $todos->taskColor = Input::get('color');
//       $todos->save();
//       return back()->with('color',$color);  
//     }
}