@extends('todo.app')
@section('content')
    @include('todo._viewstyle')
        <span id="mainHeading">Todo App</span>
        @if (Session::has('flash_message'))
                <div class="alert alert-success ml-5 {{ Session::has('flash_message_important')? 'alert-important' : ''}}">
                {{ Session::get('flash_message')}}
                </div>
        @endif
        @if (Session::has('alert'))
                <div class="alert alert-danger">
                        {{ session('alert') }}
                        {{session()->forget('alert')}}
                </div>
        @endif
        @if (Session::has('duplicate'))
                <div class="alert alert-warning">
                        {{ session('duplicate') }}
                       {{ session()->forget('duplicate')}}
                </div>
        @endif
        @if (Session::has('flash'))
                <div class="alert alert-success">
                        {{ session('flash') }}
                       {{ session()->forget('flash')}}
                </div>
        @endif

@if(count($unaccepted))
    Unaccepted
    <?php $count = 1; ?>
        @foreach($unaccepted as $todo) 
                
            <div class="row">
                <div class='panel container'> 
                    <div class="dropdown" >
                        <div class="btn-group">
                            <button type="button" class="btn" data-toggle="dropdown" style="background:none;border:none; outline:none">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                                                
                            <div class="dropdown-menu">
                                    <a class="dropdown-item accept" href="#" id="accept" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-check-circle"></i> Accept</a>
                                    <a class="dropdown-item reject" href="#"  id="decline"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-times-circle"  ></i> Decline</a>

                            </div>
                        </div>       
                    </div>  


                    <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                        <div class="wrapper">
                            <h3>{{$todo->title}}</h3> 
                            <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                        </div>
                                
                    </div><!-- End of Panel -->
                        
                </div><!-- End of Row  -->
                
        @endforeach
@endif
@if(count($accepted))
    <?php $count = 1; ?>
    Accepted
        @foreach($accepted as $todo) 
                
            <div class="row">
                <div class='panel container'> 
                    <div class="dropdown" >
                        <div class="btn-group">
                            <button type="button" class="btn" data-toggle="dropdown" style="background:none;border:none; outline:none">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                                                
                            <div class="dropdown-menu">
                                @if($todo->pin==0)  
                                    <a class="dropdown-item" href="#" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i> Pin</a>
                                @else
                                    <a class="dropdown-item" href="#" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i> Unpin</a>
                                @endif
                                    <a class="dropdown-item " href="{{ action('TodosController@edit', $todo->id ) }}" id="edit"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-pencil"  id="edit"></i> Edit</a>
                                    <a class="dropdown-item" href="#" id="reminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-bell"  id="reminder"></i> Reminder</a>
                                @if($todo->archive == 0)
                                    <a class="dropdown-item" href="#" id="archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i> Archive</a>
                                @else   
                                    <a class="dropdown-item" href="#" id="unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i> Unarchive</a>
                                @endif 
                                <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
                                    <a class="dropdown-item addcollab" href="#" data-toggle="modal" data-target="#myModal" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i> Add Collaborator</a>
                                    <a class="dropdown-item" href="#"  id="trash"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i> Delete</a>

                            </div>
                        </div>       
                    </div>  


                    <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                        <div class="wrapper">
                            <h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3> 
                            <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
                        </div>
                                
                    </div><!-- End of Panel -->
                        
                </div><!-- End of Row  -->
                
        @endforeach    


@endif
@include('todo._sideBar')
@endsection
