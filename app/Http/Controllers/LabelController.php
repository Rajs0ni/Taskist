<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Label;
use App\Todo;
use Illuminate\Support\Facades\Auth;
class LabelController extends Controller
{
    public function addnewlabel(Request $request){
        $lab = Label::where('name',$request->val)->where('user_id',Auth::id())->get();
        if(sizeof($lab)>0){
            return "exists";
        }
        else{
        $lab = new Label;
        $lab->name=$request->val;
        $lab->user_id=Auth::id();
        $lab->save();
        }
    }  
    public function getlabels(){
        return Label::where('user_id',Auth::id())->get();
    }

    public function dellabel(Request $request){
        $lab = Label::where('name',$request->val)->get()[0];
        $lab->delete();
    }
    public function updatelabel(Request $request){
        $lab = Label::where('name',$request->newval)->where('user_id',Auth::id())->get();
        if(sizeof($lab)>0){
            return "exists";
        }
        else{
        $lab = Label::where('name',$request->oldval)->where('user_id',Auth::id())->get()[0];
        $lab->name=$request->newval;
        $lab->save();
        }
    }  
   
    public function searchlabels(Request $request){
            $lab=Label::where('user_id',Auth::id())->where('name','like',$request->val.'%')->get();
            if(sizeof($lab)>0){
                return $lab;
            }
            else{
                return "notexists";
            }
    }
    
    public function addlabelrel(Request $request){
       $task = Todo::find($request->taskid);
       $label = Label::find($request->labid);
       $task->labels()->attach($label);   
    }
    
    public function dellabelrel(Request $request){
        $task = Todo::find($request->taskid);
        $label = Label::find($request->labid);
        $task->labels()->detach($label); 
    
    }
   
    public function getlabelstask(Request $request){
        $alllab =  Label::where('user_id',Auth::id())->get();
        $tasklab = Todo::find($request->taskid)->labels;
        if(sizeof($tasklab)>0 && sizeof($alllab)>0){
            $alllab=$alllab->all();
            $tasklab=$tasklab->all();
            $difflab = array_udiff($alllab, $tasklab,
                     function ($a, $b) {
                        return $a->id - $b->id;
                    }
                    );        
            return [$difflab,$tasklab];        
        }
        else if(sizeof($alllab)>0){
                return $alllab;
        }
        else
         return [];
        
    }
    public function addnewsearch(Request $request){
        $lab = new Label;
        $lab->name=$request->val;
        $lab->user_id=Auth::id();
        $lab->save();

        $task = Todo::find($request->taskid);
        $task->labels()->attach($lab);  

    }
    
    public function relexists(Request $request){
        $task = Todo::find($request->taskid);
        if($task->hasLabel($request->labid))
          return 'yes';
        }

    public function getlabelstasks($labelid){
        $label = Label::find($labelid);
        $todos = $label->todos;
        $pinned = $todos->where('pin',1);
        $unpinned =  $todos->where('pin',0);
        $todoview = Todo::where('view',0)->get();
       

     if(count($todoview))
        {
            return view('todo.gridview',compact('todos','pinned','unpinned'));
            
        }
        else
        {
                $message = "NO TASKS ON THIS LABEL!!!"; 
                return view('todo.index',compact('todos','pinned','unpinned','message'));
            
        }     
            
    }
    
}


