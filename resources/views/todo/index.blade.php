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
 @if(isset($pinned))
    @if(!count($pinned))
    @else
            @if(count($todos)) <!--Filter Pinned Tasks-->
                  @if(count($unpinned))
                    <h4>Pinned</h4><hr>
                  @else  
                  @endif        
                        <?php $count = 1; ?>
                        @foreach($todos as $todo) 
                                @if($todo->pin==1)   
                                @if($todo->archive ==1)
                                        @include('todo._archivedPartial') <!--include all pinned and archived tasks-->
                                        <?php $count++; ?>
                                @else
                                        @include('todo._archivePartial')  <!--include all pinned and unarchived tasks-->
                                        <?php $count++; ?>
                                @endif
                                @endif    
                        @endforeach      
           @else
                        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
           @endif
    @endif
@endif                
@if(isset($unpinned))
        @if(!count($unpinned))
        @else
                @if(count($todos)) <!--Filter Other Tasks-->
                     @if(count($pinned))
                        <h4>Others</h4><hr>
                     @else
                     @endif   
                        <?php $count = 1; ?>
                        @foreach($todos as $todo) 
                                @if($todo->pin == 0) 
                                  @if($todo->archive == 0)  
                                        @include('todo._archivePartial') <!--include all unpinned and unarchived tasks-->
                                        <?php $count++; ?>
                                  @else
                                        @include('todo._archivedPartial') <!--include all unpinned and archived tasks-->
                                        <?php $count++; ?>
                                  @endif
                                @endif  
                        @endforeach
                @else
                        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
                @endif
        @endif       
@endif         

@include('todo._sideBar')

@endsection
