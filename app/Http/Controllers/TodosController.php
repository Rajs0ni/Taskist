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
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed','=','0')->
                  where('archive',0)->
                  where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',0)->where('pin',0)->get();
        $message = "!!  Tasks Not Found !!";
        $todoview = Todo::where('view',0)->get();
        
        if(count($todoview))
        {
            return view('todo.gridview',compact('todos','pinned','unpinned'));
        }
        else
        {
            return view('todo.index',compact('todos','pinned','unpinned','message'));
        }        
    }
    public function all()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
        ->where('trashed','=','0')
        ->orderBy('pin','desc')
        ->orderBy('created_at','desc')
        ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed','=','0')->
                  where('pin',1)->where('archive',0)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('pin',0)->where('archive',0)->get();
        $message = "!!Tasks Not Found !!";
        $accepted = auth()->user()->todos()->where('status','A')->get();
        $archive = DB::table('todos')->where('user_id','=',auth()->user()->id)->where('trashed','=','0')->where('archive',1)->get();
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
            return view('todo.gridAllTasks',compact('todos','pinned','unpinned','archive'));
        }
        else
        {
            return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message','archive'));

        }      
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
        return view('todo.collab',compact('accepted'));
    }

    public function getrequest()
    {
        $unaccepted = auth()->user()->todos()->where('status','I')->get();
        $q="";
        foreach($unaccepted as $u){
            $owner = User::find($u->user_id);
            $q.='<div id="req">'.$owner->name.' has invited you for '.$u->title.'<br><a class="accept" href="#" id="accept" ><div hidden style="display:inline-block">'.$u->id.'</div><i class="fa fa-check-circle"></i> Accept</a>
            <a class="reject" href="#"  id="decline"><div hidden style="display:inline-block">'.$u->id.'</div><i class="fa fa-times-circle"  ></i> Decline</a>
</div>';
        }
        return response()->json(array("msg",$q),200);
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
        // testing for value of new task's view
                $todoAllTask = Todo::get();
                if(count($todoAllTask))
                {
                    foreach($todoAllTask as $ta)
                    {
                        $todo->view = $ta->view;
                    }
                }
                else
                {
                    $todo->view = 0;
                }
        // testing end
        $todo->save();
        return redirect('/')->with([
            'flash_message' => 'Task has been created!'
        ]);

    }
    // Show a particular task
    public function show($id)
    {
       
        $todo = Todo::find($id);
        if($todo->users()->where('id',auth()->user()->id)->exists()||$todo->user_id==auth()->user()->id)
            {
                $rem = Reminder::where('taskid',$id)->get();
        if(sizeof($rem)>0){
            $rem = Reminder::where('taskid',$id)->get()[0];
            return view('todo.show',compact('todo','rem'));    
        }
        else
        return view('todo.show',compact('todo'));
            }
        else{
            $todo=null;
            return view('todo.show',compact('todo'));
    }}
    // Grid Show
    // public function suggest()
    // {
    //     auth()->user()->friends();    
    // }

    public function gridshow($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                      ->findOrFail($id);
        return view('todo.gridshow',compact('todo'));
    }
    // Edit task
    public function edit($id)
    {
        $todo = Todo::find($id);
        if($todo->users()->where('id',auth()->user()->id)->exists()||$todo->user_id==auth()->user()->id)
            return view('todo.edit',compact('todo'));
        else{
            $todo=null;
            return view('todo.edit',compact('todo'));
    }
    }
    // Update task
    public function update($id, Request $request)
    {
        $this->validate($request,[

            'title' => 'required',
            'task' => 'required',
            'completion_date' => 'required|date|after_or_equal:today'
        ]);

        $todo = Todo::find($id);
        if($todo->users()->where('id',auth()->user()->id)->exists()||$todo->user_id==auth()->user()->id)
        {$todo->update($request->all());
            $rem = Reminder::where('taskid',$id)->get()->all();
        $rem->title=$request->title;
        $rem->save();}
        else{
            $todo=null;
            return view('todo.show',compact('todo'));
    }
        
       
        return redirect()->action('TodosController@show',$todo->id);
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
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','search'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message','search'));
        }               
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
                       where('trashed',0)->where('archive',0)
                        ->getCompleted()->orderBy('pin','desc')->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
        where('trashed',0)->where('pin',0)->get();
        $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }
    }
    // Get In Process tasks
    public function getProcessing()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->where('archive',0)
                        ->getProcessing()->orderBy('pin','desc')
                        ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed',0)->where('archive',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',0)->get();
        $message = "!! Not Found !!"; 
        $todoview = Todo::where('view',0)->get(); 
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }         
    }
    // Get pending tasks
    public function getPending()
    {
        $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->where('trashed',0)->where('archive',0)
                       ->getPending()->orderBy('pin','desc')
                       ->get();
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed',0)->where('archive',0)->where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed',0)->where('archive',0)->where('pin',0)->get();
        $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }
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
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed','=','0')->
                  where('archive',0)->
                  where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',0)->
                    where('pin',0)->get();
                                
        return view('todo.gridview',compact('todos','pinned','unpinned'));
    }
    // Sort by Title
    public function sortByTitle()
    {
        $var = request('var');
        
        if($var==0){
            $todos = Todo::where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('archive',0)
            ->getProcessing()->orderBy('title')
            ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                where('trashed',0)->where('archive',0)->where('pin',1)->orderBy('title')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',0)->orderBy('title')->get();
        }
        else if($var==1){
            $accepted = auth()->user()->todos()->where('status','A')->orderBy('title')->get();
            return view('todo.collab',compact('accepted'));   
        }
        else if($var==2){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->where('trashed',0)->where('archive',0)
                       ->getPending()->orderBy('title')
                       ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',1)->orderBy('title')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',0)->orderBy('title')->get();
        }
        else if($var==3){
            $todos = Todo::where('user_id','=',auth()->user()->id)->
                       where('trashed',0)->where('archive',0)
                        ->getCompleted()->orderBy('title')->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('pin',1)->orderBy('title')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('pin',0)->orderBy('title')->get();
        }
        else if($var==4){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                ->where('trashed','=','0')
                ->orderBy('pin','desc')
                ->orderBy('title')
                ->get();
                $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('pin',1)->orderBy('title')->where('archive',0)->get();
                $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                            where('trashed','=','0')->
                            where('pin',0)->orderBy('title')->where('archive',0)->get();
                $accepted = auth()->user()->todos()->where('status','A')->orderBy('title')->get();
                $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        
        if(count($todoview))
        {
        return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message'));
        }
        else
        {
        return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message'));
        }
        }
        else if($var==5){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('archive','=','1')
                        ->where('trashed','=','0')
                        ->orderBy('title')
                        ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->where('archive',1)->
                    where('pin',1)->orderBy('title')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('archive',1)->
                        where('pin',0)->orderBy('title')->get();
        }
        else if($var==6){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('trashed','=','1')
                        ->orderBy('title')
                        ->get();
                        $message = "!! Not Found !!";
                        $todoview = Todo::where('view',0)->get();

                        return view('todo.trash',compact('todos','todoview','message'));
                      
                                
        }
        else{
            $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->where('archive',0)->orderBy('title')->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',0)->
                    where('pin',1)->orderBy('title')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('archive',0)->
                        where('pin',0)->orderBy('title')->get();
        }
        $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }
    }
    // Sort by Date
    public function sortByDate()
    {
        $var = request('var');
        
        if($var==0){
            $todos = Todo::where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('archive',0)
            ->getProcessing()->orderBy('completion_date')
            ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                where('trashed',0)->where('archive',0)->where('pin',1)->orderBy('completion_date')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',0)->orderBy('completion_date')->get();
        }
        else if($var==1){
            $accepted = auth()->user()->todos()->where('status','A')->orderBy('completion_date')->get();
            return view('todo.collab',compact('accepted'));   
        }
        else if($var==2){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                       ->where('trashed',0)->where('archive',0)
                       ->getPending()->orderBy('completion_date')
                       ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',1)->orderBy('completion_date')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed',0)->where('archive',0)->where('pin',0)->orderBy('completion_date')->get();
        }
        else if($var==3){
            $todos = Todo::where('user_id','=',auth()->user()->id)->
                       where('trashed',0)->where('archive',0)
                        ->getCompleted()->orderBy('completion_date')->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('pin',1)->orderBy('completion_date')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
            where('trashed',0)->where('pin',0)->orderBy('completion_date')->get();
        }
        else if($var==4){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                ->where('trashed','=','0')
                ->orderBy('completion_date')
                ->get();
                $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('pin',1)->orderBy('completion_date')->where('archive',0)->get();
                $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                            where('trashed','=','0')->
                            where('pin',0)->orderBy('completion_date')->where('archive',0)->get();
                $accepted = auth()->user()->todos()->where('status','A')->orderBy('date')->get();
                $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        
        if(count($todoview))
        {
        return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message'));
        }
        else
        {
        return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message'));
        }
        }
        else if($var==5){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('archive','=','1')
                        ->where('trashed','=','0')
                        ->orderBy('completion_date')
                        ->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->where('archive',1)->
                    where('pin',1)->orderBy('completion_date')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('archive',1)->
                        where('pin',0)->orderBy('completion_date')->get();
        }
        else if($var==6){
            $todos = Todo::where('user_id','=',auth()->user()->id)
                        ->where('trashed','=','1')
                        ->orderBy('completion_date')
                        ->get();
                        $message = "!! Not Found !!";
                        $todoview = Todo::where('view',0)->get();

                        return view('todo.trash',compact('todos','todoview','message'));
                      
                                
        }
        else{
            $todos = Todo::where('user_id','=',auth()->user()->id)->
                        where('trashed',0)->where('archive',0)->orderBy('completion_date')->get();
            $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',0)->
                    where('pin',1)->orderBy('completion_date')->get();
            $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                        where('trashed','=','0')->
                        where('archive',0)->
                        where('pin',0)->orderBy('completion_date')->get();
        }
        $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }
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
        $todoview = Todo::where('view',0)->get();    
        $message = "!! Tasks Not Found !!";            
        return view('todo.trash',compact('todos','todoview','message'));
    }

    public function getcollab(Request $request)
    {
        $todo = Todo::where('id','=',request('id'))->first();
        $users=$todo->users()->get();
        if(count($users)){
            return response()->json(array('msg'=> $users), 200);
        }
        else{
            return response()->json(array("msg","no"),200);
        }
    }

    public function removecollab(Request $request)
    {
        $task = request('task');
        $user = request('user');
        $todo=Todo::find($task);
        if(auth()->user()->id == $todo->user_id){
            $todo->users()->detach($user);
            return response()->json(array("msg","success"),200);
        }
        else{
            return response()->json(array("msg","no"),200);
        }
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
            //auth()->user()->friends()->attach($user2->id);
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
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed','=','0')->where('archive',1)->
                  where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',1)->
                    where('pin',0)->get();
        $message = "!! Not Found !!";
        $todoview = Todo::where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message'));
        }     
        //return view('todo.archive',compact('todos','pinned','unpinned','message'));
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
          $todo = Todo::where('user_id','=',auth()->user()->id)->findOrFail($request->id);
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
                $todo->reminder = 1;
                $todo->save();
         }
           else{
                $id = Reminder::where('user_id',Auth::id())->where('taskid',$request->id)->get()[0]->id;
                $find = Reminder::findOrFail($id);
                $find->remdate = $d;
                $find->remtime = $t;
                $find->readed =0;
                $find->noti=1;
                $find->save();
                $todo->reminder = 1;
                $todo->save();
           }

    }

    public function getreminder(){
        date_default_timezone_set("Asia/Kolkata");
        $notifications=[];
        $c=0;
     $notification =DB::table('reminders')->where('user_id',Auth::id())->where('remdate','<=',date('d-m-Y'))->where('remtime','<=',date('h:i:sa'))->where('noti',1)->get();
     
     if(sizeof($notification)>0){
         for($ct=0;$ct<sizeof($notification);$ct++){
                 if(Todo::find($notification[$ct]->taskid)->trashed ==0) 
                    $notifications[$c++]=$notification[$ct];
             
         }
     }
      echo json_encode($notifications);
      
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
  
  public function color(Request $request)
  {
      $color = $request->color;
      $id = $request->id;
      $todo = Todo::findOrFail($id);
      $todo->taskColor = $color;
      $todo->save();
      return $color;
  }   
      
}