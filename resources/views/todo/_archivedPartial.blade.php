<div class="row">
    <div class='panel container' style="background:linear-gradient(90deg,rgb(19, 71, 71)10%,rgb(239, 240, 240))"> 
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
                                <a class="dropdown-item" id="reminder"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-clock"></i> Snooze</a>
                            @if($todo->archive == 0)
                                <a class="dropdown-item" class="arc" id="archive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="archive"></i> Archive</a>
                            @else   
                                <a class="dropdown-item" id="unarchive"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-archive" id="unarchive" style="color:rgb(244, 152, 66)"></i> Unarchive</a>
                            @endif 
                                <a class="dropdown-item"><div hidden style="display:inline-block">{{$todo->id}}</div>
                                <input type="color" style="width:20px;" id="colorpicker">
                                Change Color
                                </a>
                                <a class="dropdown-item addcollab" data-toggle="modal" data-target="#myModal"   id="addcollab" ><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-user-plus"></i> Add Collaborator</a>
                                <a class="dropdown-item" id="trash"><div hidden style="display:inline-block">{{$todo->id}}</div><i class="fa fa-trash"  ></i> Delete</a>

                        </div>
                </div>       
        </div>  


        <div class="circle"></div><span id="span1"><?php if($count<=9)echo "0".$count; ?></span>
        <div class="wrapper">
                <h3><a href="/todo/{{$todo->id}}/show">{{$todo->title}}</a></h3> 
                <span id="span2" >&#x25cf; {{$todo->completion_date}}</span>
        </div>
                    
    </div><!-- End of Panel -->
            
</div><!-- End of Row  -->

@include('todo._collaboration')