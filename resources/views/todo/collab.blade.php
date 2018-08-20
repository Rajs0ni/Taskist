@extends('todo.app')
@section('content')
    @include('todo._viewstyle')
        <span id="mainHeading">TaskIST</span>
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
@if(count($accepted))
    <?php $count = 1; ?>
        @foreach($accepted as $todo) 
                
            <div class="row">
                <div class='panel container' style="background:linear-gradient(90deg,{{$todo->taskColor}} 10%,rgb(239, 240, 240));"> 
                    <div class="dropdown" >
                        <div class="btn-group">
                            <button type="button" class="btn vanishOutline" data-toggle="dropdown" style="background:none;border:none; outline:none">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                                                
                            <div class="dropdown-menu">
                                @if($todo->pin==0)  
                                    <a class="dropdown-item" href="#" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i> Pin</a>
                                @else
                                    <a class="dropdown-item" href="#" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i> Unpin</a>
                                @endif
                                    <a class="dropdown-item " href="{{ action('TodosController@edit', $todo->id ) }}" id="edit"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-edit"  id="edit"></i> Edit</a>
                                @if($todo->reminder==1)
                                    <a class="dropdown-item" id="reminder" data-toggle="modal" data-target="#addreminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock" style="color:rgb(244, 152, 66)"></i> Snooze</a>   
                                @else  
                                    <a class="dropdown-item" id="reminder" data-toggle="modal" data-target="#addreminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock" ></i> Snooze</a>
                                @endif  
                                <a class="dropdown-item" id="tasklabel" data-toggle="modal" data-target="#tasklab"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fas fa-tags"></i> Labels</a>
   
                                @if($todo->archive == 0)
                                    <a class="dropdown-item" href="#" id="archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i> Archive</a>
                                @else   
                                    <a class="dropdown-item" href="#" id="unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i> Unarchive</a>
                                @endif 
                                <a class="dropdown-item addcollab" href="#"   id="addcollab" data-toggle="modal" data-target="#myModal" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i> Collaborators</a>
                                <a class="dropdown-item color">
                                    <div hidden style="display:inline-block">{{$todo->id}}</div>
                                    <button id="list_btn" ><i class="fa fa-palette"></i>&ensp;Change Color</button>        
                                    <input type="color" id="colorpicker"> 
                               </a>
                              <a class="dropdown-item" href="#"  id="uncollab"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i> Uncollaborate</a>

                            </div>
                        </div>       
                    </div>  


                    <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count++;else echo $count++; ?></span>
                        <div class="wrapper">
                            <div class="todo-title-in-panel"><h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3></div>
                            <span id="span2" >&#x25cf; Owned by {{$todo->user->name}}</span>
                        </div>
                                
                    </div><!-- End of Panel -->
                        
                </div><!-- End of Row  -->
                
        @endforeach    
@else
    <h4 id="notFoundAlert">{{$message}}</h4>    
@endif
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Collaborators</h4>
          <button type="button" class="close modalclose" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
           
          <hr>
<br>
          <h5 >Add Collaborators</h5>
          <label for="" id="collabLabel">Email:</label>
          <input type="email" class="email" id="collab">
          <button type="button" class="btn" id="addCollaborator">Add</button>
          </p>
          <input  id="val" value="" hidden>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn"  data-dismiss="modal" id="modaldone">Done</button>
        </div>
      </div>
      
    </div>
  </div>
@include('todo._sideBar')
@endsection
