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
       <!-- for pinned tasks -->
    @if(count($pinned))
        <h4>Pinned</h4><hr>
        <?php $count = 1; ?>
        @foreach($todos as $todo) 
          @if($todo->pin == 1)
            <div class="grid">
                <div class="grid_count_title">
                    <div class="count"></div>
                    <span id="gridnum"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                    <div class="gridtitle"><input type="text" value='{{ $todo->title}}'></div>
                </div>
                <div class="gridtask"><textarea >{{ $todo->task}}</textarea></div>
                <div class="gridbtn">
                    @if($todo->pin==0)    
                        <a href="#" id="pin" title="Pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i></a>
                    @else
                        <a href="#" id="pin" title="Unpin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i></a>
                    @endif
                        <a href="{{ action('TodosController@edit', $todo->id ) }}" id="edit" title="Edit"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-edit" id="edit"></i></a>
                        <a href="#" id="snooze" title="Snooze"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock"  id="snooze"></i></a>
                    @if($todo->archive == 0)
                        <a href="#" id="archive" title="Archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i></a>
                    @else   
                        <a href="#" id="unarchive" title="Unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i></a>
                    @endif 
                       <i class="fa fa-palette"></i>
                        <a href="#" id="addcollab" class="addcollab" title="Collaborator" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i></a>
                        <a  href="#"  id="trash"  title="Trash"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i></a>
                </div>
            </div><!-- End of Grid -->
          @endif
        @endforeach
    @else    
    @endif    
        <!-- for other tasks -->
        <br><br>
        @if(count($unpinned))
                @if(count($pinned))
                  <h4>Others</h4><hr> 
                @else
                @endif  
        <?php $count = 1; ?>
        @foreach($todos as $todo) 
          @if($todo->pin == 0)
            <div class="grid">
                <div class="grid_count_title">
                    <div class="count"></div>
                    <span id="gridnum"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                    <div class="gridtitle"><input type="text" value='{{ $todo->title}}'></div>
                </div>
                <div class="gridtask"><textarea >{{ $todo->task}}</textarea></div>
                <div class="gridbtn">
                    @if($todo->pin==0)    
                        <a href="#" id="pin" title="Pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack" id="pin"></i></a>
                    @else
                        <a href="#" id="Unpin" title="Unpin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack" id="pin" style="color:red"></i></a>
                    @endif
                        <a href="{{ action('TodosController@edit', $todo->id ) }}" id="edit" title="Edit"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-edit" id="edit"></i></a>
                        <a href="#" id="reminder" title="Reminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-bell" id="reminder"></i></a>
                    @if($todo->archive == 0)
                        <a href="#" id="archive" title="Archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i></a>
                    @else   
                        <a href="#" id="unarchive" title="Unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i></a>
                    @endif 
                    <i class="fa fa-palette"></i>
                        <a class="addcollab" href="#"   id="addcollab"  title="Collaborator" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i></a>
                        <a  href="#" id="trash" title="Trash"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i></a>
                </div>
            </div><!-- End of Grid -->
          @endif
        @endforeach
    @endif    
@else
        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
@endif

</div><!-- End of gridContainer -->

@include('todo._sideBar')
@endsection
