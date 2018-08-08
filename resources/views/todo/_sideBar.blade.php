<div class="sideWrapper">
        <aside class="aside">
                 <div class="sideBarHeader"><span>Quick Bar</span></div>
                 <div class="sideBarContainer">
                 <a href="{{ action('TodosController@create') }}"><i class="fa fa-plus"></i> Quick Add</a>
                 <a href="{{ action('TodosController@search') }}"><i class="fa fa-search "></i> Quick Find</a>
                 <a href="#" id="clearall"><i class="fa fa-trash" ></i> Quick Clear</a>
                 <a href="#" id='labels_add' data-toggle="modal" data-target="#addlabelsmodal"><i class="fas fa-tags"></i> Add labels</a><span data-toggle="dropdown" id='labelsavail'>..</span>
                  <div class="dropdown-menu" id='alllabelsavail' style='max-height:500px;overflow:auto'>
                                
                  </div>
                </div>
                 <hr>
                 <div class="sideBarHeader"><span>Sort By</span></div>
                 <div class="sideBarContainer"><a href="{{ action('TodosController@sortByTitle') }}" class="sort"><i class="fa fa-sort-alpha-asc"></i> Title</a><br>
                 <a href="{{ action('TodosController@sortByDate') }}"><i class="fa fa-sort-amount-desc"></i> Date</a> </div>
                 <hr>
                 <div class="sideBarHeader"><span>Task Categorization</span></div>
                 <div class="sideBarContainer"><a href="{{ action('TodosController@getProcessing') }}"><i class="fa fa-sun "></i> Today's Tasks</a>
                 <a href="{{ action('TodosController@collab') }}"><i class="fa fa-users"></i> Collaboration</a>
                 <a href="{{ action('TodosController@getPending') }}"><i class="fa fa-hourglass-end "></i> Pending Tasks</a>
                 <a href="{{ action('TodosController@getCompleted')}}"><i class="fa fa-check-square "></i> Completed Tasks</a>
                 <a href="{{ action('TodosController@all') }}"><i class="fa fa-tasks"></i> All Task</a>
                 <hr>
                 <a href="{{ action('TodosController@archived') }}"><i class="fa fa-archive"></i> Archive</a><br>
                 <a href="{{ action('TodosController@trash') }}"><i class="fa fa-trash"></i> Trash</a><br>
                 <a href="{{ action('TodosController@help') }}"><i class="fa fa-question-circle text-primary"></i> Help</a><br></div>
                 
        </aside>
</div>

@include('todo.labels')
