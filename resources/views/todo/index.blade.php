@extends('todo.app')

@section('content')

        @include('todo._viewstyle')
        <span id="mainHeading">Todo App</span>
        @if (Session::has('flash_message'))
                <div class="alert alert-success ml-5 {{ Session::has('flash_message_important')? 'alert-important' : ''}}">
                {{ Session::get('flash_message')}}
                {{ session()->forget('flash_message')}}
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

 
@if(count($todos)) 
<!-- Filter pinned tasks --> 
        @if(count($pinned))
          <h4>Pinned</h4><hr>      
                <?php $count = 1; ?>
                @foreach($pinned as $todo) 
                        @if($todo->pin == 1)
                                @if($todo->archive ==1)
                                        @include('todo._archivedPartial') 
                                        <?php $count++; ?>
                                @else
                                        @include('todo._archivePartial')  
                                        <?php $count++; ?>
                                @endif
                        @endif        
                @endforeach       
        @endif         
<!--Filter others tasks -->
@if(count($unpinned))
                @if(count($pinned))
                  <h4>Others</h4><hr> 
                @else
                @endif    
                <?php $count = 1; ?>
                @foreach($todos as $todo) 
                        @if($todo->pin == 0) 
                               @if($todo->archive == 0)   
                                        @include('todo._archivePartial')
                                        <?php $count++; ?>
                                @else
                                        @include('todo._archivedPartial') 
                                        <?php $count++; ?>
                                @endif
                        @endif        
                @endforeach      
        @endif  
<!-- end others task-->          
@else
    <!-- check if search variable is set -->
    @if(isset($search)) 
        <h4 id="notFoundAlert">"<i><b>{{$search}}</b></i>"&ensp;{{$message}}
           <a href="/create/{{$search}}">Create it</a></h4>
    <!-- if search var is not set -->
    @else
        <h4 id="notFoundAlert">{{$message}}</h4>  
    @endif    
@endif     


@include('todo._sideBar')

@endsection
