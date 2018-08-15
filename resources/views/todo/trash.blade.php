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
@if(!count($todoview))  <!-- for list trash-->
        @if(count($todos))
                <h4>Trash</h4><hr>
                <?php $count = 1; ?> 
                @foreach($todos as $todo) 
                        <div class="row">
                                <div class='panel container' style="background:linear-gradient(90deg,rgba(0,0,0,0.5)10%,rgba(255,255,255,0.5));"> 
                                        <div class="dropdown" >
                                                <div class="btn-group">
                                                        <button type="button" class="btn" data-toggle="dropdown" style="background:none;border:none; outline:none"><i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        
                                                        <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="#" id="restore"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-undo" id="restore"></i> Restore</a>
                                                                <a class="dropdown-item" href="#" id="delete"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  id="delete"></i> Delete</a>    
                                                        </div>
                                                </div>       
                                        </div>  

                                        <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                                        <div class="wrapper">
                                                <div class="todo-title-in-panel"><h3>{{$todo->title}}</h3></div> 
                                                <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                                        </div>
                                        
                                </div><!-- End of Panel -->
                        </div> <!-- End of Row -->
                @endforeach
        @else
                <h4 id="notFoundAlert">{{ $message }}</h4>
        @endif
@else    <!-- for grid trash-->
<div class="container gridContainer"  >
        @if(count($todos)) 
                <?php $count = 1; ?>
                @foreach($todos as $todo) 
                        <div class="grid">
                                <div class="grid_count_title">
                                        <div class="count"></div>
                                        <span id="gridnum"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                                        <div class="gridtitle"><input type="text" value='{{ $todo->title}}'></div>
                                </div>
                                <div class="gridbtn">
                                        <input type='hidden' value={{$todo->id}} id='task_id'>
                                        <input type='hidden' value={{$todo->title}} id='task_title'>
                                        <a  href="#" id="restore"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-undo" id="restore"></i> Restore</a>
                                        <a href="#" id="delete"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  id="delete"></i> Delete</a>    
                                </div>
                        </div>
                @endforeach
        @else
                <h4 id="notFoundAlert">{{ $message }}</h4>
        @endif   
        
</div>
@endif   <!-- end grid trash-->   

@include('todo._sideBar')
@endsection

