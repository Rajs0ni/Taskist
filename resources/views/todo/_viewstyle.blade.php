<div class="viewWrapper">
        <a href="/">
                <button type="button" class="btn viewtype" title="List View"><i class="fa fa-list-ul"></i> </button>
        </a>
        <a href="{{action('TodosController@gridview')}}">
                <button type="button" class="btn viewtype" title="Grid View" >
                 <i class="fa fa-th-large"></i> </button>
        </a> 
        <a href="{{action('TodosController@gridview')}}">
                <button type="button" class="btn viewtype" title="Grid View" >
                 <i class="fa fa-bell"></i></button>
        </a> 
    
 </div>