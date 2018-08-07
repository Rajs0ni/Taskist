<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Label;
class LabelController extends Controller
{
    public function addnewlabel(Request $request){
        $lab = Label::where('name',$request->val)->get();
        if(sizeof($lab)>0){
            return "exists";
        }
        else{
        $lab = new Label;
        $lab->name=$request->val;
        $lab->save();
        }
    }  
    public function getlabels(){
        return Label::all();
    }

    public function dellabel(Request $request){
        $lab = Label::where('name',$request->val)->get()[0];
        $lab->delete();
    }
    public function updatelabel(Request $request){
        $lab = Label::where('name',$request->newval)->get();
        if(sizeof($lab)>0){
            return "exists";
        }
        else{
        $lab = Label::where('name',$request->oldval)->get()[0];
        $lab->name=$request->newval;
        $lab->save();
        }
    }  
    
}
