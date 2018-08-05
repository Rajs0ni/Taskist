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
        <h4>Trash</h4><hr>
        <?php $count = 1; ?> 
        @foreach($todos as $todo) 
                <div class="row">
                        <div class='panel container'  style="background:linear-gradient(90deg,rgb(165, 165, 165)10%,rgb(225, 222, 222))"> 
                                <div class="dropdown" >
                                        <div class="btn-group">
                                                <button type="button" id="ellipsis" class="btn vanishOutline" data-toggle="dropdown" style="background:none;border:none; outline:none"><i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                
                                                <div class="dropdown-menu  ">
                                                        <a class="dropdown-item   " href="#" id="restore"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-redo-alt" id="restore"></i> Restore</a>
                                                        <a class="dropdown-item   " href="#" id="delete"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  id="delete"></i> Delete</a>    
                                                </div>
                                        </div>       
                                </div>  

                                <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                                <div class="wrapper">
                                        <h3>{{$todo->title}}</h3> 
                                        <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                                </div>
                                
                        </div><!-- End of Panel -->
                </div> <!-- End of Row -->
        @endforeach
@else
         <h4 id="notFoundAlert">!! Not Found !!</h4>
@endif

@include('todo._sideBar')
@endsection

