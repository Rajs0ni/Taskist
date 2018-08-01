@extends('todo.app')

@section('styles')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href=/css/mystyle.css >
    <link rel="stylesheet" type="text/css" href=/css/offcanvas.css >
    <link rel="stylesheet" type="text/scss" href=/css/offcanvas.scss >
@endsection
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
        <div class='panel '>   
                <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                 <div class="wrapper">
                       <h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3> 
                        <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                 </div>

  <button type="button" class="" data-toggle="dropdown" style="background:none;border:none; outline:none"><i class="fa fa-ellipsis-v"></i>
    </button>
   
  
 
</div>
            <!-- <div class="outersubmenu ">
                        @if($todo->pin==0)
                                <div><div hidden>{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i></div>
                        @else
                                <div><div hidden>{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i></div>
                        @endif
                         <div><div hidden>{{$todo->id}}</div><i class="fa fa-archive"  id="archive"></i></div>
                         <div><div hidden>{{$todo->id}}</div><i class="fa fa-trash"  id="trash"></i></div>
                 </div> -->
      </div>

       <div style="display:inline-block;position:relative; ">
    <a class="dropdown-item" href="#" id="pin" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i> Pin</a>
      <a class="dropdown-item" href="#"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive"  id="archive"></i> Archives</a>
      
    </div>
  
         @endforeach
         @else
        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
        @endif
      @include('todo._sideBar')
@endsection

@section('footer')
 
@endsection