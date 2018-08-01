@extends('todo.app')

@section('content')
<span id="mainHeading">Todo App</span>
@if($todo)
<div class="row">
<div class="ml-5 mt-5 text-justify col-offset-3" style="width:100%">
        <!-- Content Panel -->
        <div class="col">
            <div class="row">
                <div class="col text-left">
                    <h3 style="color:#F76266;font-weight:bold; text-transform:capitalize;">{{$todo->title}}</a></h3>
                </div>
                <div class="col text-right">
                    <strong><span class="date">{{$todo->completion_date}}</span></strong>
                </div> 
            </div>
            <hr>
        </div>
        <div class="col-12 text-justify">
            <p class="show_content">{{$todo->task}}</p>
            <hr>
        </div>  
      <!-- Action Panel -->
        <div class="col">
         <div class="row">
           <div class="col-4">
               <a href="{{ action('TodosController@gridview') }}">
                   <button id="goBack" type="button" class="btn btn-defeault myButton" >
                       <i class="fa fa-arrow-circle-left"></i> Go Back
                    </button>
                </a>
           </div>
           <div class="col-4">
               <a href="{{ action('TodosController@edit', $todo->id ) }}">
                   <button id="edit"  type="button" class="btn btn-info myButton" >
                    <li class="fa fa-pencil"></li> Edit
                    </button>
                </a>
           </div>
          <div class="col-4">
            <form action="{{ action('TodosController@deleteTask', $todo->id )}}" onSubmit="if(!confirm('Are you sure you want to permanently delete this task?')){return false;}" > 
                <button id="delete" type="submit" class="btn btn-danger myButton">
                        <i class="fa fa-trash"></i> Delete
                 </button>
            </form>
          </div>
         
         </div>
        </div>        
   </div>
        
</div>
   
@endif

@endsection