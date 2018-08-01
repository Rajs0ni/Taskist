@extends('todo.app')

@section('content')
<span id="mainHeading">Todo App</span>
@include('errors.list')
<h1 class="heading">Edit Task</h1>
<br>
{!! Form::model($todo, ['method' => 'PATCH', 'action'=>['TodosController@update',$todo->id]]) !!}
   @include('todo._form',['submitButtonText' => 'Update Task'])
 {!! Form::close() !!}
@stop 