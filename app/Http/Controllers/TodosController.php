<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Requests;
use Carbon\Carbon;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Reminder;
use Illuminate\Support\Facades\DB;
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
        return view('todo.index',compact('todos'));
    }
    public function myorder(){
        $todos = Todo::where('user_id','=',auth()->user()->id)
                    ->where('trashed','=','0')
                    ->where('archive','=','0')
                    ->orderBy('order','asc')
                        ->get();
        return view('todo.myorder',compact('todos'));
    }
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
        return view('todo.index',compact('todos'));
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
        $todo = Todo::findOrFail($id);
        $rem = Reminder::where('taskid',$id)->get();
        if(sizeof($rem)>0){
            $rem = Reminder::where('taskid',$id)->get()[0];
            return view('todo.show',compact('todo','rem'));    
        }
        else
        return view('todo.show',compact('todo'));
    }
    // Grid Show
    public function gridshow($id)
    {
        $todo = Todo::findOrFail($id);
        return view('todo.gridshow',compact('todo'));
    }
    // Edit task
    public function edit($id)
    {
        $todo = Todo::findOrFail($id);
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

        $todo = Todo::findOrFail($id);
        $todo->update($request->all());
        $rem = Reminder::where('taskid',$id)->get()->all()[0];
        $rem->title=$request->title;
        $rem->save();
        return redirect()->action('TodosController@show',$todo->id);;
    }
    // Delete a particular task
    public function deleteTask($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();
        Reminder::where('taskid',$id)->delete();
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
        $todos = Todo::search($keyword)->get();
        return view('todo.index',compact('todos'));
    }
    // Delete all tasks
    public function clearall(Request $request)
    {
        $val=request('val');
        if($val==1){
            $todos = Todo::all();
            foreach($todos as $todo){
                $todo->trashed=1;
                $todo->save();
            }
            return redirect('/')->with('alert','All the tasks have been trashed!');
        }
       
        $todos = Todo::truncate();
        return redirect('/')->with('alert','All the tasks have been deleted!');
    }
    // Get completed tasks
    public function getCompleted()
    {
        $todos = Todo::getCompleted()->get();
        return view('todo.index',compact('todos'));
    }
    // Get In Process tasks
    public function getProcessing()
    {
        $todos = Todo::getProcessing()->get();
        return view('todo.index',compact('todos'));
    }
    // Get pending tasks
    public function getPending()
    {
        $todos = Todo::getPending()->get();
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
        $todos = Todo::orderBy('created_at','desc')->get();
        return view('todo.gridview',compact('todos'));
    }
    // Sort by Title
    public function sortByTitle()
    {
        $todos = Todo::orderBy('title')->get();
        return view('todo.index',compact('todos'));
    }
    // Sort by Date
    public function sortByDate()
    {
        $todos = Todo::orderBy('date_created')->get();
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
        $todos = Todo::where('trashed','=','1')
                        ->orderBy('created_at','desc')
                        ->get();
        return view('todo.trash',compact('todos'));
    }

    public function archived(){
        $todos = Todo::where('archive','=','1')
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

    public function addreminder(Request $request){
        date_default_timezone_set("Asia/Kolkata");
          $find=sizeof(Reminder::where('user_id',Auth::id())->where('taskid',$request->id)->get());
          $d=strtotime($request->date);
          $d=date("d-m-Y",$d);
          $t=strtotime($request->time);
          $t=date("h:i:sa",$t);
          if($find == 0){
                
                $rem = new Reminder;
                $rem->taskid =  $request->id;
                $rem->user_id =  Auth::id(); 
                $rem->remdate = $d;
                $rem->remtime = $t;
                $rem->title=  $request->title;
                $rem->save();
         }
           else{
                $id = Reminder::where('user_id',Auth::id())->where('taskid',$request->id)->get()[0]->id;
                $find = Reminder::findOrFail($id);
                $find->remdate = $d;
                $find->remtime = $t;
                $find->readed =0;
                $find->noti=1;
                $find->save();
           }

    }

    public function getreminder(){
     date_default_timezone_set("Asia/Kolkata");
     $notification =DB::table('reminders')->where('user_id',Auth::id())->where('remdate','<=',date('d-m-Y'))->where('remtime','<=',date('h:i:sa'))->where('noti',1)->get();
     echo $notification;
      
 }
  
    public function removeremindernoti(Request $request){
        $rem = Reminder::findOrFail($request->id);
        $rem->noti=0;
        $rem->save();      
 
    
    }
    public function  getremtime(Request $request){
          $rem = Reminder::where('taskid',$request->id)->get()[0];
          echo $rem->remdate . " on " .$rem->remtime;
    }

    public function  removereminder(Request $request){
        $rem = Reminder::where('taskid',$request->id)->get()[0];
        $rem->delete();
  }      
}