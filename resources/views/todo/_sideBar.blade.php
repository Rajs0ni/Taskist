@foreach($user as $u)

   <?php $color = $u->themeColor; ?>

@endforeach
<div class="sideWrapper">
       <aside class="aside">
                <div class="sideBarHeader" style="background:<?php echo $color; ?>;">
                   <span>Quick Bar</span>
                </div>
                <div class="sideBarContainer" >
                <a href="{{ action('TodosController@create') }}"><i class="fa fa-plus"></i> Quick Add</a>
                <a href="{{ action('TodosController@search') }}"><i class="fa fa-search "></i> Quick Find</a>
                <!-- <a href="#" id="clearall"><i class="fa fa-trash" ></i> Quick Clear</a> -->

                <!-- dropdown  -->
                </div>
                     <div class="dropdown">
                               <div class="btn-group" >
                                   <button type="button"  class="dropdown-toggle vanishOutline" data-toggle="dropdown"  id="theme_btn" style="font-size:19px;">
                                       <i class="fa fa-trash" style="font-size:18px;"></i> Quick Clear
                                   </button>
                                   <div class="dropdown-menu">
                                   <a href="/todo/clearall?val=1" class="dropdown-item" >Trash</a>
                                   <a href="/todo/clearall?val=2" class="dropdown-item">Clear All</a>
                                   </div>
     
                               </div>       
                       </div>
                    
                <!-- end of dropdown -->
                <div class="sideBarContainer">
                    <a href="#" id='labels_add' class="vanishOutline" data-toggle="modal" data-target="#addlabelsmodal">
                       <i class="fa fa-tags" style="font-size:17px;"></i> Labels
                    </a>
                    <span data-toggle="dropdown" id='labelsavail'>
                       <i class="fa fa-caret-down" style="font-size:20px;cursor:pointer;"></i>
                    </span>
                    <div class="dropdown-menu" id='alllabelsavail' style='width:100%;max-height:350px;z-index:1;overflow-y:scroll;'>             
                    </div>
                </div>
                 <hr>
                 <div class="sideBarHeader" style="background:<?php echo $color; ?>;">
                  <span>Sort By</span>
                 </div>
                 <div class="sideBarContainer">
                 <a href="#" class="sort" id="sort"><i class="fa fa-sort-alpha-asc"></i> Title</a><br>
                 <a href="#" id="date"><i class="fa fa-sort-amount-desc"></i> Date</a> 
                 </div>
                 <hr>
                 <div class="sideBarHeader" style="background:<?php echo $color; ?>;">
                  <span>Task Categorization</span>
                 </div>
                 <div class="sideBarContainer"><a href="{{ action('TodosController@getProcessing') }}"><i class="fa fa-sun "></i> Today's Tasks</a>
                 <a href="{{ action('TodosController@collab') }}"><i class="fa fa-users"></i> Collaboration</a>
                 <a href="{{ action('TodosController@getPending') }}"><i class="fa fa-hourglass-end "></i> Pending Tasks</a>
                 <a href="{{ action('TodosController@getCompleted')}}"><i class="fa fa-check-square "></i> Completed Tasks</a>
                 <a href="{{ action('TodosController@all') }}"><i class="fa fa-tasks"></i> All Task</a>
                 <hr>
                 <div class="sideBarContainer">
                        <div class="dropdown">
                                <div class="btn-group">
                                        <button type="button" class="dropdown-toggle vanishOutline" data-toggle="dropdown"  id="theme_btn">
                                        <i class="fa fa-palette"> Theme</i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <a href="#">
                                          <button type="button" style="background:transparent;border:solid transparent;text-align:left;">Change Theme</button>
                                          <input type="color" id="themecolor" style="width:150px;height:32px;position:absolute;top:6px;opacity:0;">
                                        </a>
                                        <a href="/todo/reset">
                                          <button style="background:transparent;border:solid transparent;text-align:left;">Reset Theme</button> 
                                        </a>
                                        </div>
                                </div>       
                        </div>
                 </div>
                 <div class="sideBarContainer">
                 <a href="{{ action('TodosController@help') }}"><i class="fa fa-question-circle text-primary"></i> Help</a><br></div>

                 </div>
                 
        </aside>
</div>
@include('todo.labels')











