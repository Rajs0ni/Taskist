@extends('todo.app')

@section('content')
        @foreach($user as $u)
           <?php $color = $u->themeColor;?>
        @endforeach
    <span id="mainHeading">Todo App</span>
    @include('errors.list') 
	    
    <h1 class="heading" style="text-shadow:0px 6px 8px <?php echo $color; ?>;">Create A New Task</h1>   
    <br>
    {!! Form::model($todo = new \App\Todo, ['url'=>'todo']) !!}
         @include('todo._form',['submitButtonText' => 'Add Task'])
    {!! Form::close() !!}
 
@stop