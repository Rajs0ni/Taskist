@extends('todo.app')

@section('content')
    <span id="mainHeading">Todo App</span>
    @include('errors.list') 
    <h1 class="heading">Create A New Task</h1>
    <br>
    {!! Form::model($todo = new \App\Todo, ['url'=>'todo']) !!}
         @include('todo._form',['submitButtonText' => 'Add Task'])
    {!! Form::close() !!}
@stop