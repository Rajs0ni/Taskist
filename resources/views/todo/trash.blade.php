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
        @if(count($todos))
        <?php $count = 1; ?>
         @foreach($todos as $todo) 
        <div class="row">
        <div class='panel col-xs-6' style="background:linear-gradient(90deg,rgb(107, 107, 109)10%,rgb(191, 191, 191));">   
                <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                 <div class="wrapper">
                       <h3>{{$todo->title}}</h3> 
                        <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                 </div>
            </div>
            <div class="outersubmenu col-xs-1">
                    <div><div hidden>{{$todo->id}}</div><i class="fa fa-undo" id="restore"></i></div>
                    <div><div hidden>{{$todo->id}}</div><i class="fa fa-trash"  id="delete"></i></div>
                 </div>
      </div>
         @endforeach
         @else
        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
        @endif
      @include('todo._sideBar')
@endsection

@section('footer')
 
@endsection