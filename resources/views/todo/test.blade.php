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
 <!-- condition for pinned tasks -->
@if(isset($pinned)) 
@if(count($todos)) 
        @if(count($pinned))
        <h4>Pinned</h4><hr>      
                <?php $count = 1; ?>
                @foreach($todos as $todo) 
                        @if($todo->pin==1)   
                                @if($todo->archive ==1)
                                        @include('todo._archivedPartial') 
                                        <?php $count++; ?>
                                @else
                                        @include('todo._archivePartial')  
                                        <?php $count++; ?>
                                @endif
                        @endif    
                @endforeach
        @else        
        @endif         
<!--condition for others tasks -->
        @if(count($unpinned))
                @if(!count($pinned))
                @else
                        <h4>Others</h4><hr> 
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
        @else        
        @endif    
@else
        <h4 id="notFoundAlert">!! Record Not Found !!</h4>
@endif
<!--if variable are unset -->
@else 

<!-- if unset then for pinned-->
        @if(count($todos)) 
                
                <h4>Pinned</h4><hr>      
                        <?php $count = 1; ?>
                        @foreach($todos as $todo) 
                                @if($todo->pin==1)   
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
        <!--end of unset pinned -->
        <!--unset others -->
                        <h4>Others</h4><hr> 
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
        <!--unset others end-->
@endif      
<!-- end others task-->
@include('todo._sideBar')

@endsection
