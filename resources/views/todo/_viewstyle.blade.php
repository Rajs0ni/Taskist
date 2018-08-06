
<div class="viewWrapper">
        
        <a href="/todo/view/{{0}}">
        <!-- <a href="/"> -->
                <button type="button" class="btn viewtype list vanishOutline" title="List View"><i class="fa fa-list-ul"></i> </button>
        </a>
        <a href="/todo/view/{{1}}">
        <!-- <a href="{{action('TodosController@gridview')}}"> -->
                <button type="button" class="btn viewtype vanishOutline" title="Grid View" ><i class="fa fa-th-large"></i> </button>
        </a> 
        <button type="button" class="btn viewtype vanishOutline" title="Notifications" id="shownoti">
                 <i class="fa fa-bell"></i></button>

                  <ul class="navbar-nav ml-3 " >
                <!-- Authentication Links -->
                @guest
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="color:white; font-weight:bold;">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}" style="color:white; font-weight:bold;">{{ __('Register') }}</a>
                        </li>
                @else
                        <li class="nav-item dropdown">
                        <a style="color:rgb(243, 114, 114);font-weight:bold;" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                                </form>
                        </div>
                        </li>
                @endguest
        </ul>        
       
    
 </div>
  <div class="notis" id="notific">
  <div class="Notification-header">
    Notifications
    <button type="button" class="close mr-2 mt-1" data-dismiss="modal" >&times;</button>
  </div>
  <div class="Notification-content"></div>
</div>

 <script>
$('.rem').mouseenter(function(){
 $('.delrem').css('display','block');
 });

 var event2="";
 window.onload = function() { 
    $('.Notification-content').empty();
    noti();
};

function noti(){
  $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/getreminder',
      method:'get',
      success(response){
       response=JSON.parse(response);
      if(response.length>0){
      for(var i=0;i<response.length;i++){
            var div=$("<div class='rem' id='remm'></div>");
            var ip=$('<input type="hidden" class="taskid">').val(response[i].id );
            var divv=$("<div></div>");
            var a=$('<a class="Noti-title"></a>').text(response[i].title).attr({'href':"/todo/"+response[i].taskid+"/show"});
            var div1 = $("<div></div>").css({'display':'inline-block','max-width':'50%','overflow':'hidden','text-overflow':'ellipsis','white-space':'nowrap'}).append(a);
            var span=$("<span class='delrem' id='dell'></span>").css({'float':'right','cursor':'pointer','font-weight':'bold','margin-right':'2.5%'}).html('&times;');
            divv.append(div1).append(span);
            var span2 =$("<span></span>").text(response[i].remdate + " " + response[i].remtime);
            div.append(ip);
            div.append(divv);
            div.append(span2);
            $(".Notification-content").append(div);

      }
    }
    else{
           var span=$('<span class="no-notifications-msg"></span>').text('No Notifications');
           $(".Notification-content").append('<i class="fa fa-bell no-notifications-bell"></i>').append(span).append('<p class="noti_MSG">You have no new Notifications.</p>');
    }
          }
      }); 
    
}

$('body').on('mouseenter','.rem',function(){
   $(this).find('.delrem').css('display','inline');
})


$('body').on('mouseleave','.rem',function(){
   $(this).find('.delrem').css('display','none');
})

$('#shownoti').click(function(){
 $('.notis').toggle(); 
})
//HOVER 
 {{--  $('#shownoti').mouseenter(function(){
 $('.notis').css('display','block');
 })   


 $('#shownoti').mouseleave(function(){
 setTimeout(function () {
        if(event2="")
        $('.notis').css('display','none');
    }, 1000);

 }); 
    $('.notis').mouseenter(function(){
      event2="ready";
    $('.notis').css('display','block');
    })

    $('.notis').mouseleave(function(){
      event2="";
    $('.notis').css('display','none');
    })  --}}

  
  {{--  $('html').on('click',function(evt){
            if(evt.target.id == "notific" || evt.target.id =="shownoti" || evt.target.id =="remm" || evt.target.id =="dell")
                  return;
            if($(evt.target).closest('#notific').length)
                 return;
            if($(".notis").css('display')=='block')
                $(".notis").css('display','none');
        });    --}}

$('body').on('click','.delrem',function(){
    var id=$(this).parents('.rem').find('.taskid').val();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/removeremindernoti',
      method:'get',
      data:{
          id:id
      }
      });
    $(this).parents('.rem').remove();
   
})
 </script>
