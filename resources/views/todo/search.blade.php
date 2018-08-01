@extends('todo.app')

@section('content')
<span id="mainHeading">Todo App</span>
<div class="form-group">
    <div class="flag">
       <span class="flagStyle" id="search">Search</span>
    </div>
   {!! Form::open(array('action' => 'TodosController@find')) !!}
        <div class='form-group'>
            {!! Form::text('keyword',null, ['class'=>'form-control hover','placeholder'=>'Type your keyword']) !!}
        </div>
        <div class='form-group'>
            {!! Form::submit('Task Search',['class'=>'btn btn-default searchButton form-control']) !!}
        </div>
 {!! Form::close() !!}
@endsection