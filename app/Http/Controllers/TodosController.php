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
        $this->middleware('auth',['except' => ['help']]);
       
    }
    public function index1($x)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)->get();
        if($x == 1)
        {  
             foreach($todo as $t)
             {
                 $t->view = 0;
                 $t->save();
             }
             return redirect('todo/gridview'); 
        }
        else
        {
            foreach($todo as $t)
             {
                 $t->view = 1;
                 $t->save();
             }
             return redirect('/todo'); 
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
        $user = User::where('id','=',auth()->user()->id)->get();
        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        
        if(count($todoview))
        {
            return view('todo.gridview',compact('todos','pinned','unpinned','user'));
        }
        else
        {
            return view('todo.index',compact('todos','pinned','unpinned','message','user'));
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
        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        $user = User::where('id','=',auth()->user()->id)->get();
        if(count($todoview))
        {
            return view('todo.gridAllTasks',compact('todos','pinned','unpinned','archive','user'));
        }
        else
        {
            return view('todo.alltasks',compact('todos','pinned','unpinned','accepted','message','archive','user'));

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
        $accepted = auth()->user()->todos()->where('status','A')->where('trashed',0)->get();
        $unaccepted = auth()->user()->todos()->where('status','I')->where('trashed',0)->get();
        $user = User::where('id','=',auth()->user()->id)->get();
        return view('todo.collab',compact('accepted','user'));
    }

    
    // Create New Task
    public function create()
    {
        $user = User::where('id','=',auth()->user()->id)->get();
        return view('todo.create',compact('user'));
    }
    //create after search
    public function create1($search)
    {
        $user = User::where('id','=',auth()->user()->id)->get();
        return view('todo.create',compact('search','user'));
    }
    // Store Task
    public function store(Request $request)
    {
        $user = User::where('id',auth()->user()->id)->get();
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
        foreach($user as $u)
        {
            $todo->taskColor = $u->themeColor;
        }
        $todo->save();
        return redirect('/todo')->with([
            'flash_message' => 'Task has been created!'
        ]);

    }
    // Show a particular task
    public function show($id)
    {
        
        $todo = Todo::find($id);
        if($todo->users()->where('id',auth()->user()->id)->exists()||$todo->user_id==auth()->user()->id)
      
      {  $rem = Reminder::where('taskid',$id)->get();
        $labels=$todo->labels;
        $user = User::where('id',auth()->user()->id)->get();

        if(sizeof($rem)>0 && sizeof($labels)>0){
            $rem = Reminder::where('taskid',$id)->get()[0];
            return view('todo.show',compact('todo','rem','labels','user'));    
        }
        if(sizeof($rem)>0 && sizeof($labels)==0){
            $rem = Reminder::where('taskid',$id)->get()[0];
            return view('todo.show',compact('todo','rem','user'));    
        }
        if(sizeof($rem) == 0 && sizeof($labels)>0){
            return view('todo.show',compact('todo','labels','user'));    
        }
        
        return view('todo.show',compact('todo','user'));
        }
        else
        {
            $todo=null;
            return view('todo.show',compact('todo','user'));
        }
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
        
    public function getrequest()
    {
        $unaccepted = auth()->user()->todos()->where('status','I')->get();
        $q="";
        foreach($unaccepted as $u){
        $owner = User::find($u->user_id);
        $q.='<div id="req">
                <p class="collabHeaderMSG">
                        <span class="collab-Notifi-Important">'.$owner->name.'</span>
                        has invited you to collaborate on the task
                        <span class="collab-Notifi-Important">'.$u->title.'.</span>
                </p><p class="collabFooterMSG">(You can accept or decline this invitation).</p><br />
                <a class="accept" href="#" id="accept" ><div hidden style="display:inline-block">'.$u->id.'</div>Accept</a>
                <a class="reject" href="#" id="decline"><div hidden style="display:inline-block">'.$u->id.'</div>Decline</a>
            </div>';
    }

 return response()->json(array("msg",$q),200);
 } 
    // Grid Show
    public function gridshow($id)
    {
        $todo = Todo::where('user_id','=',auth()->user()->id)
                      ->findOrFail($id);
                 $user = User::where('id',auth()->user()->id)->get();             
        return view('todo.gridshow',compact('todo','user'));
    }
    // Edit task
    public function edit($id)
    {
        $todo = Todo::find($id);
        $user = User::where('id',auth()->user()->id)->get();
        if($todo->users()->where('id',auth()->user()->id)->exists()||$todo->user_id==auth()->user()->id)
            return view('todo.edit',compact('todo','user'));
        else{
            $todo=null;
            return view('todo.edit',compact('todo','user'));
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
        {$todo->update($request->all());}
        $rem = Reminder::where('taskid',$id)->get();
        if(sizeof($rem)>0){
            $rem = Reminder::where('taskid',$id)->get()[0];
            $rem->title=$request->title;
            $rem->save();
                
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
        $user = User::where('id','=',auth()->user()->id)->get();
        return view('todo.search',compact('user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();
        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','search','user'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message','search','user'));
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
            return redirect('/todo')->with('alert','All the tasks have been trashed!');
        }
        $todos = Todo::where('user_id','=',auth()->user()->id)->get();
        foreach($todos as $todo)
        {
            $todo->delete();
        }
        return redirect('/todo')->with('alert','All the tasks have been deleted!');
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
        $user = User::where('id','=',auth()->user()->id)->get();
        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message','user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();
        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get(); 
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message','user'));
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
                $user = User::where('id','=',auth()->user()->id)->get();
        $todoview = Todo::where('id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
        }
        else
        {
        //return redirect('/');
        return view('todo.index',compact('todos','pinned','unpinned','message','theme','user'));
        }
    }
    // Help User
    public function help()
    {
        $user = User::where('id','=',auth()->user()->id)->get();
        return view('todo.help',compact('user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();                       
        return view('todo.gridview',compact('todos','pinned','unpinned','user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();

        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message','user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();

        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
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
        $user = User::where('id','=',auth()->user()->id)->get();
   
        $message = "!! Tasks Not Found !!";            
        return view('todo.trash',compact('todos','todoview','message','user'));
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
        $pinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                  where('trashed','=','0')->where('archive',1)->
                  where('pin',1)->get();
        $unpinned = DB::table('todos')->where('user_id','=',auth()->user()->id)->
                    where('trashed','=','0')->
                    where('archive',1)->
                    where('pin',0)->get();
        $message = "!! Not Found !!";
        $user = User::where('id','=',auth()->user()->id)->get();

        $todoview = Todo::where('user_id','=',auth()->user()->id)->where('view',0)->get();
        if(count($todoview))
        {
        return view('todo.gridview',compact('todos','pinned','unpinned','message','user'));
        }
        else
        {
        return view('todo.index',compact('todos','pinned','unpinned','message','user'));
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
  }  
  public function reset()
  {
      $todo = Todo::where('user_id','=',auth()->user()->id)->get();
      $user = User::where('id','=',auth()->user()->id)->get();
      foreach($todo as $t)
      {
          $t->taskColor = "#F37272";
          $t->save();
      }
      foreach($user as $u)
      {
          $u->themeColor = "#F37272";
          $u->save();
      }
      return back();
  } 
  public function ThemeColor(Request $request)
  {
      $themecolor = $request->color;
      $todo = Todo::where('user_id','=',auth()->user()->id)->get();
      $user = User::where('id','=',auth()->user()->id)->get();
      foreach($todo as $t)
      {
        $t->taskColor = $themecolor;
        $t->save();
      } 
      foreach($user as $u)
      {
        $u->themeColor = $themecolor;
        $u->save();
      } 
    }
      
  public function hasnewnoti(){
    date_default_timezone_set("Asia/Kolkata");
    $notification =   DB::table('reminders')->where('user_id',Auth::id())->where('remdate','<=',date('d-m-Y'))->where('remtime','<=',date('h:i:sa'))->where('readed',0)->where('noti',1)->get();
    $notification=$notification->count();
    echo $notification;
  }

    public function makeread(Request $request){
        $no_noti = $request->noti;  
    foreach($no_noti as $n){
        $t = Reminder::findOrFail($n['id']);
        $t->readed = 1;
        $t->save();
    }
    }
}