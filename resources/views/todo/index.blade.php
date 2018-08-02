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
                <div class='panel container'> 
                                <div class="dropdown" >
                                        <div class="btn-group">
                                                <button type="button" class="btn" data-toggle="dropdown" style="background:none;border:none; outline:none"><i class="fa fa-ellipsis-v"></i>
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
                                                        <a class="dropdown-item addcollab" href="#"   id="addcollab" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i> Add Collaborator</a>
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

         
@else
        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
@endif
        <div class="collab">
                Add Collaborator
                <span class="close">&times;</span>
                <hr>
                <div class="body">
                        <label for="tags">Email</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="text" id="tag">
                        <br><br>
                        <button class="btn btn-primary" id="add">Add</button>
                </div>
        </div>
@include('todo._sideBar')
@endsection
