@extends('todo.app')

@section('content')
<span id="mainHeading">Todo App</span>
@if($todo)
<div class="row">
<div class="ml-5 mt-5 text-justify col-offset-3" style="width:100%">
        <!-- Content Panel -->
        <div class="col">
            <div class="row">
                <div class="col text-left">
                    <h3 style="color:#F76266;font-weight:bold; text-transform:capitalize;">{{$todo->title}}</a></h3>
                </div>
             
              
                <div class="col text-right">             
                   @if(isset($rem))
                   <span id="remclock"><i class="fa fa-clock clock"  style="font-size:20px;" ></i></span>
                    <div class="dropdown mr-3 ml-1" style="display:inline-block;">
                    <span ><i class="fas fa-caret-down" id="clk"></i></span>
                        <ul class="dropdown-menu">
                        <li id="editreminder">Edit</li>
                        <li id="removereminder">Remove</li>
                        </ul>
                    </div>
                     <script>
            (function ( $ ) {
                    var event1="";
                     $('[data-toggle="tooltip"]').tooltip();
                      $('.fa-caret-down').click(function(){
                        $(".dropdown-menu").toggle();
                          event1='ready';
                        })
                        
                        $('html').on('click',function(evt){
                            if(event1=='ready'){    
                            if(evt.target.id == "clk")
                                return;
                            
                            if($(".dropdown-menu").css('display')=='block')
                                $(".dropdown-menu").css('display','none');
                            
                                event1="";
                            }
                        });

                        @include('todo.reminder')
                            
                            $('.clock').hover(function(){
                               $.ajaxSetup({
                                    headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({                       
                                url: '/getremtime',
                                method:'get',
                                data:{
                                    id:{{$todo->id}}
                                },
                                success(response){       
                             $('.clock').prop({'title':response});                            
                                }
                                });
                            });

                            $("#removereminder").click(function(){
                                 if(confirm('Remove reminder')){
                                  $(this).parent().parent().parent().find('#remclock').remove();
                                  $(this).parents('.dropdown').remove();
                                   $.ajax({                       
                                    url: '/removereminder',
                                    method:'get',
                                    data:{
                                        id:{{$todo->id}}
                                    }
                                   });
                                
                                 }
                            });
                        }( jQuery ));
                    </script>
                   
                    @endif
                    <strong><span class="date">{{$todo->completion_date}}</span></strong>
                    
                </div> 
            </div>
            <hr>
        </div>
        <div class="col-12 text-justify">
            <p class="show_content">{{$todo->task}}</p>
            <hr>
        </div>  
      <!-- Action Panel -->
        <div class="col">
         <div class="row">
           <div class="col-4">
               <a href="{{ action('TodosController@index') }}">
                   <button id="goBack" type="button" class="btn btn-defeault myButton" >
                       <i class="fa fa-arrow-circle-left"></i> Go Back
                    </button>
                </a>
           </div>
           <div class="col-4">
               <a href="{{ action('TodosController@edit', $todo->id ) }}">
                   <button id="edit"  type="button" class="btn btn-info myButton" >
                    <li class="fa fa-edit"></li> Edit
                    </button>
                </a>
           </div>
          <div class="col-4">
            <form action="{{ action('TodosController@deleteTask', $todo->id )}}" onSubmit="if(!confirm('Are you sure you want to permanently delete this task?')){return false;}" > 
                <button id="delete" type="submit" class="btn btn-danger myButton">
                        <i class="fa fa-trash"></i> Delete
                 </button>
            </form>
          </div>
         
         </div>
        </div>        
   </div>
        
</div>
   
@endif
        @include('todo.remindermodalbox')
                         <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="addrem">DONE</button>
                        </div>
                        
                    </div>
                    </div>
                </div>


@endsection