@extends('todo.app')

@section('content')
    @include('todo._viewstyle')
            <span id="mainHeading">Todo App</span>
                @if (Session::has('flash_message'))
                  <div class="alert alert-success ml-5 {{ Session::has('flash_message_important')? 'alert-important' : ''}}">
                        {{ Session::get('flash_message')}}
                   </div>
                @endif
                @if (session('alert'))
                 <div class="alert alert-success">
                         {{ session('alert') }}
                 </div>
                @endif
        <div class="container gridContainer">
            @if(count($todos))
            <?php $count = 1; ?>
            @foreach($todos as $todo) 
            <div class="grid">
                <div class="grid_count_title">
                    <div class="count"></div>
                    <span id="gridnum"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                    <div class="gridtitle"><input type="text" value='{{ $todo->title}}'></div>
                </div>
                <div class="gridtask"><textarea >{{ $todo->task}}</textarea></div>
                <div class="griddate"><input type="text" value='{{ $todo->completion_date}}'></div>
                <div class="gridbtn"><button><a href="{{action('TodosController@gridshow',$todo->id)}}"><i class="fa fa-eye"></i></a></button></div>
            </div>
            @endforeach
            @else
            <h4 id="notFoundAlert">!! Record Not Found !!</h4>
            @endif
        </div>
      @include('todo._sideBar')
@endsection

@section('footer')
 
@endsection