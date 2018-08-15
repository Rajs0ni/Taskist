@extends('todo.app')

@section('content')
   
        @include('todo._viewstyle')
     
        <span id="mainHeading">TaskIST</span>
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
        <div class='panel col-xs-6'>   
                <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                 <div class="wrapper">
                       <h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3> 
                        <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                 </div>
            </div>
            <div class="outersubmenu col-xs-1">
                        @if($todo->pin==0)
                            <div><div hidden>{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i></div>
                        @else
                            <div><div hidden>{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i></div>
                        @endif

                        @if($todo->archive==0)
                            <div><div hidden>{{$todo->id}}</div><i class="fa fa-archive"  id="archive"></i></div>
                        @else
                            <div><div hidden>{{$todo->id}}</div><i class="fa fa-archive" style="color:#594F4B" id="unarchive"></i></div>
                        @endif

                         <div><div hidden>{{$todo->id}}</div><i class="fa fa-trash"  id="trash"></i></div>
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