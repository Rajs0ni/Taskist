<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use App\User;
use App\Collaborator;
use Requests;
use Session;
use Carbon\Carbon;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Reminder;
use Illuminate\Support\Facades\DB;
class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index1($x)
    {
        $todo = Todo::get();
        if($x == 1)
        {  
             foreach($todo as $t)
             {
                 $t->view = 0;
                 $t->save();
             }
             return redirect('/'); 
        }
        else
        {
            foreach($todo as $t)
             {
                 $t->view = 1;
                 $t->save();
             }
             return redirect('/'); 
        }
    }
    public function index()
    {
        
        $todos = Todo::where('user_id','=',auth()->user()->id)
                    ->where('trashed','=','0')
                    ->where('archive',0)
                    ->orderBy('pin','desc')
                    ->orderBy('created_at','desc')
                    ->get();
        $todoview = Todo::where('view',0)->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('archive',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('archive',0)->where('pin',0)->get();
        $message = "!!  Tasks Not Found !!";
       
        if(!count($todoview))
        {
            return view('todo.index',compact('todos','pinned','unpinned','message'));
        }
        else
        {
            return view('todo.gridview',compact('todos','pinned','unpinned'));

        }        
    }
    public function all()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
        ->where('trashed','=','0')
        ->orderBy('pin','desc')
        ->orderBy('created_at','desc')
        ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
        $message = "!!Tasks Not Found !!";
        $accepted = auth()->user()->todos()->where('status','A')->get();
        return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message'));
    }


    public function acceptcollab(Request $request)
    {
        $id=request('id');
        DB::table('todo_user')->where('user_id','=',auth()->user()->id)->where('todo_id','=',$id)->update([ 'status' => 'A' ]);
        return response()->json(array("msg","Accepted"),200);
    }
    public function rejectcollab(Request $request)
    {
        $id=request('id');
        DB::table('todo_user')->where('user_id','=',auth()->user()->id)->where('todo_id','=',$id)->delete();
        return response()->json(array("msg","Rejected"),200);
    }

    public function collab()
    {
        $accepted = auth()->user()->todos()->where('status','A')->get();
        $unaccepted = auth()->user()->todos()->where('status','I')->get();
        return view('todo.collab',compact('accepted','unaccepted'));
    }
    // Create New Task
    public function create()
    {
        return view('todo.create');
    }
    //create after search
    public function create1($search)
    {
        return view('todo.create',compact('search'));
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
        $todo = Todo::where('user_id','=',auth()->user()->id)->findOrFail($id);
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
        $rem = Reminder::where('taskid',$id)->get()->all()[0];
        $rem->title=$request->title;
        $rem->save();
        return redirect()->action('TodosController@show',$todo->id);;
    }
    // Delete a particular task
    public function deleteTask($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                        ->findOrFail($id);
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

    public function setsession(Request $request)
    {
        $type = request('type');
        if($type=='success'){
            Session::put('flash',request('message'));
        }
        elseif($type=='duplicate'){
            Session::put('duplicate',request('message'));
        }
        elseif($type=='yourself'){
            Session::put('duplicate',request('message'));
        }
        elseif($type=='error'){
            Session::put('alert',request('message'));
        }
        elseif($type=='Accepted'){
            Session::put('flash',request('message'));
        }
        elseif($type=='rejected'){
            Session::put('alert',request('message'));
        }
        else{
            Session::put('alert',request('message'));
        }
       
    }

    public function find(Request $request)
    {
        $this->validate($request,[

            'keyword' => 'required'
        ]);
        $keyword = $request->input('keyword');
        $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->search($keyword)->orderBy('pin','DESC')->get();
        $search = Input::get('keyword');               
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
        $message = "!! Not Exist !!";                
        return view('todo.index',compact('todos','pinned','unpinned','message','search'));
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
        foreach($todos as $todo)
        {
            $todo->delete();
        }
        return redirect('/')->with('alert','All the tasks have been deleted!');
    }
    // Get completed tasks
    public function getCompleted()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                       where('trashed',0)->where('archive',0)->orderBy('pin','desc')
                        ->getCompleted()->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',0)->get();
        $message = "!! Not Found !!";
        return view('todo.index',compact('todos','pinned','unpinned','message'));
    }
    // Get In Process tasks
    public function getProcessing()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->where('archive',0)
                        ->getProcessing()->orderBy('pin','desc')
                        ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',0)->get();
        $message = "!! Not Found !!";           
        return view('todo.index',compact('todos','pinned','unpinned','message'));
    }
    // Get pending tasks
    public function getPending()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->where('trashed',0)->where('archive',0)
                       ->getPending()->orderBy('pin','desc')
                       ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',0)->get();
        $message = "!! Not Found !!";
        return view('todo.index',compact('todos','pinned','unpinned','message'));
    }
    // Help User
    public function help()
    {
        return view('todo.help');
    }
    // Grid View
    public function gridview()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->
                        where('archive',0)->orderBy('pin','desc')->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
                                
        return view('todo.gridview',compact('todos','pinned','unpinned'));
    }
    // Sort by Title
    public function sortByTitle()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->orderBy('title')->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
        $message = "!! Not Found !!";
        return view('todo.index',compact('todos','pinned','unpinned','message'));
    }
    // Sort by Date
    public function sortByDate()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->orderBy('date_created')->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
        $message = "!! Not Found !!";
        return view('todo.index',compact('todos','pinned','unpinned','message'));
    }
    //Trash A Particular Task
    public function trashTask(Todo $todo){
        $todo->trashed=1;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been trashed!'
        ]);
    }
    //Pin task
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
    //Tasks In Trash
    public function trash(){
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('trashed','=','1')
                        ->orderBy('created_at','desc')
                        ->get();
        return view('todo.trash',compact('todos'));
    }

    public function addcollab(Request $request)
    {
        $user2 = User::where('email','=',request('email'))->first();
        $task = Todo::where('id','=',request('id'))->first();
        $user1 = auth()->user()->id;
        if( $task->user_id == $user2->id){
            return response()->json(array("msg","yourself"),200);
        }
        else if(! $task->users()->where('id',$user2->id)->exists()){
            $task->users()->save($user2);
            return response()->json(array("msg","success"),200);
        }
        else{
            return response()->json(array("msg","duplicate"),200);
        }
    }

    public function archived(){
        $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('archive','=','1')
                        ->where('trashed','=','0')
                        ->orderBy('pin','desc')
                        ->orderBy('created_at','desc')
                        ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('pin',0)->get();
        $message = "!! Not Found !!";     
        return view('todo.archive',compact('todos','pinned','unpinned','message'));
    }
    //Unarchive task
    public function unarchive(Todo $todo){
        $todo->archive=0;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been unarchived!'
        ]);
    }
    //Archive task
    public function archiveTask(Todo $todo){
        $todo->archive=1;
        $todo->save();
        return back()->with([
            'flash_message' => 'Task has been archived!'
        ]);
    }
    //Restore Task
    public function restore(Todo $todo)
    {
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