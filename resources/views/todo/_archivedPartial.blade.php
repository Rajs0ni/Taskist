<div class="row">
    <div class='panel container' style="background:{{$todo->taskColor}};"> 
        <div class="dropdown" >
                <div class="btn-group">
                        <button type="button" id="ellipsis" class="btn vanishOutline" data-toggle="dropdown" ><i class="fa fa-ellipsis-v"></i>
                        </button>

                        <div class="dropdown-menu">
                                <input type='hidden' value='{{$todo->id}}' id='task_id'>
                                <input type='hidden' value='{{$todo->title}}' id='task_title'>

                            @if($todo->pin==0)  
                                <a class="dropdown-item" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin"></i> Pin</a>
                            @else
                                <a class="dropdown-item" id="pin"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-thumb-tack"  id="pin" style="color:red"></i> Unpin</a>
                            @endif
                                <a class="dropdown-item" href="{{ action('TodosController@edit', $todo->id ) }}" id="edit"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-edit"  id="edit"></i> Edit</a>
                            @if($todo->reminder==1)
                                <a class="dropdown-item" id="reminder" data-toggle="modal" data-target="#addreminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock" style="color:rgb(244, 152, 66)"></i> Snooze</a>   
                            @else  
                                <a class="dropdown-item" id="reminder" data-toggle="modal" data-target="#addreminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock" ></i> Snooze</a>
                            @endif  
                            <a class="dropdown-item" id="tasklabel" data-toggle="modal" data-target="#tasklab"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fas fa-tags"></i> Labels</a>
                            @if($todo->archive == 0)
                                <a class="dropdown-item" class="arc" id="archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i> Archive</a>
                            @else   
                                <a class="dropdown-item" id="unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i> Unarchive</a>
                            @endif 

                                <a class="dropdown-item addcollab" href="#" data-toggle="modal" data-target="#myModal"   id="addcollab" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i>Collaborators</a>
                                <a class="dropdown-item"><div hidden style="display:inline-block">{{$todo->id}}</div>
                                <button id="list_btn" ><i class="fa fa-palette"></i>&ensp;Change Color</button>        
                                <input type="color"  id="colorpicker">
                                </a>
                                <a class="dropdown-item" id="tasklabel" data-toggle="modal" data-target="#tasklab"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fas fa-tags"></i> Labels</a>
                                <a class="dropdown-item" href="#"  id="trash"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i> Delete</a>


                        </div>
                </div>       
        </div>  


        <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count; else echo $count++;?></span>
        <div class="wrapper">
                <h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3> 
                <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
        </div>
                    
    </div><!-- End of Panel -->
            
</div><!-- End of Row  -->

@include('todo._collaboration')