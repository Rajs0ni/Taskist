<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Label;
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
            $lab=Label::where('name','like',$request->val.'%')->get();
            if(sizeof($lab)>0){
                return $lab;
            }
            else{
                return "notexists";
            }
    }
}
