<div class="viewWrapper">

  @foreach($user as $u)
           <?php $color = $u->themeColor;?>
  @endforeach
<a href="/todo/view/{{0}}">
                <button type="button" class="btn viewtype list vanishOutline" title="List View" style="background:<?php echo $color; ?>;">
                  <i class="fa fa-list-ul"></i> 
                </button>
        </a>
        <a href="/todo/view/{{1}}">
                <button type="button" class="btn viewtype vanishOutline" title="Grid View" style="background:<?php echo $color; ?>;"><i class="fa fa-th-large"></i> </button>
        </a> 
     
        <button type="button" class="btn viewtype vanishOutline" title="Collaboration Request"  id="collabrequest" style="background:<?php echo $color; ?>;">
       <i class="fa fa-user-friends" ></i></button><div class="CRCount">{{session('hasRequests')?session('hasRequests'):0}}</div>
                 

        <ul class="navbar-nav" >
    <button type="button" class="btn viewtype vanishOutline" title="Notifications" id="shownoti" style="background:<?php echo $color; ?>;"><i class="fa fa-bell notibell"  id="shownoti" ></i></button> 
     <div class="rmCount"></div> 
       
          <ul class="navbar-nav ml-2"  >
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
                  
                  
                            @foreach($user as $u)
                              <?php $color = $u->themeColor;?>
                            @endforeach
                                <a style="color:<?php echo $color; ?>;font-weight:bold;" id="navbarDropdown" class="nav-link dropdown-toggle name" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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

 
<div class=" NotiPanelCSS" id="collabnotific">
    <div class="Notification-header">
      Collaboration Requests
      <button type="button" class="close mr-2 mt-1" data-dismiss="modal" >&times;</button>
    </div>
    <div id="notify" ></div>
</div>

<div class="notis NotiPanelCSS" id="notific">
    <div class="Notification-header">
      Notifications
      <button type="button" class="close mr-2 mt-1 closenotific" data-dismiss="modal" >&times;</button>
    </div>
    <div class="Notification-content"></div>
      <div id='clearallnoti'>Clear All</div>
</div>
 <script>
$('.rem').mouseenter(function(){
 $('.delrem').css('display','block');
 });

 var event2="",notievent='',notifications,bellswing,newnoti='';
 window.onload = function() { 
    $('.Notification-content').empty();
    bellswing = setInterval(function(){ 
            notii()  ;
   
           if(newnoti=='yes'){
                   $('.rmCount').css('display','inline-block');
                   $('.notibell').addClass('swingimage');
   
           }
            }, 1000);
};

$('.closenotific').click(function(){
    $('.notis').slideUp();
             notii()  ;
           if(newnoti=='yes'){
                  $('.notibell').addClass('swingimage');      
           }
           else{
            $('.notibell').removeClass('swingimage');            
            $('.rmCount').css('display','none');
           }
            
      $('#shownoti').off('click');              
   $('#shownoti').click(down);
        
 })

function notii(){
  $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/hasnewnoti',
      method:'get',
      success(response){    
             if(response>0){
               newnoti='yes';
               $('.rmCount').css('display','inline-block');
               $('.rmCount').text(response);
             }
           else{
               newnoti='';
               $('.rmCount').css('display','none');
           }            
      }
      }); 
    
}



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
        $('#clearallnoti').css('display','block');
        notifications=response;
                  $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({             
            url: "/makeread",
            method:'post',
            dataType: "json",
            data:{
              noti:notifications,
            }
              
            });
  
      for(var i=0;i<response.length;i++){
             if(response[i].readed == 0 && response[i].noti == 1){
               var div=$("<div class='rem' id='remm'></div>");
              var ip=$('<input type="hidden" class="taskid">').val(response[i].id );
              var divv=$("<div class='nooo'></div>");
              var a=$('<a class="Noti-title"></a>').text('Title:- '+response[i].title).attr({'href':"/todo/"+response[i].taskid+"/show"});
              var div1 = $("<div class='no'></div>").css({'display':'inline-block','max-width':'50%','overflow':'hidden','text-overflow':'ellipsis','white-space':'nowrap'}).append(a);
              var span=$("<span class='delrem' id='dell'></span>").css({'float':'right','cursor':'pointer','font-weight':'bold','margin-right':'2.5%'}).html('&times;');
              divv.append(div1).append(span);
              var span2 =$("<span class='remdatetime'></span>").text('Reminder Date:-'+response[i].remdate + " " + response[i].remtime);
              div.append(ip);
              div.append(divv);
              div.append(span2);
              var span3 =$("<span class='newreminder'></span>").text('NEW').css('float','right');
              div.append(span3); 
               $(".Notification-content").prepend(div);
            }
           else{
            var div=$("<div class='rem' id='remm'></div>").css('background','rgba(239,239,240)');
            var ip=$('<input type="hidden" class="taskid">').val(response[i].id );
            var divv=$("<div class='nooo'></div>");
            var a=$('<a class="Noti-title"></a>').text('Title:-'+response[i].title).attr({'href':"/todo/"+response[i].taskid+"/show"});
            var div1 = $("<div class='no'></div>").css({'display':'inline-block','max-width':'50%','overflow':'hidden','text-overflow':'ellipsis','white-space':'nowrap'}).append(a);
            var span=$("<span class='delrem' id='dell'></span>").css({'float':'right','cursor':'pointer','font-weight':'bold','margin-right':'2.5%'}).html('&times;');
            divv.append(div1).append(span);
            var span2 =$("<span class='remdatetime'></span>").text('Reminder Date:-'+response[i].remdate + " " + response[i].remtime);
            div.append(ip);
            div.append(divv);
            div.append(span2);
            $(".Notification-content").append(div);
           }
      }
    }
    else{
             var span=$('<span class="no-notifications-msg"></span>').text('No Notifications');
           $(".Notification-content").append('<i class="fa fa-bell no-notifications-bell"></i>').append(span).append('<p class="noti_MSG">You have no new Notifications.</p>');
            $('#clearallnoti').css('display','none');
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

$('body').on('click','.nooo',function(evt){
       if(evt.target.id == "dell" )
          return;
  
   var a=$(this).find('.Noti-title').attr('href');
   window.location.assign(a);
})

function down(){
   $('#shownoti').off('click');             
   $('.Notification-content').empty();
   noti();
   $('.rmCount').css('display','none');
   newnoti='';             
   $('.notibell').removeClass('swingimage');
   $('.notis').slideDown();
   $('#shownoti').click(up);
   
}

function up(){
     notii()  ;
           if(newnoti=='yes'){
                  $('.rmCount').css('display','inline-block');
                  $('.notibell').addClass('swingimage');      
           }
           else{
            $('.notibell').removeClass('swingimage');            
            $('.rmCount').css('display','none');
           }
 
   $('.notis').slideUp();
   $('#shownoti').off('click');      
   $('#shownoti').click(down);
}

$('#shownoti').one('click',down);

$('body').click(function(evt){   
       if(evt.target.id == "notific" || evt.target.id =='shownoti' || evt.target.id =='dell' )
          return;
   
       if($(evt.target).closest('#notific').length)
            return;             

            
          if($(".notis").css('display')=='block'){
                 
              $('.notis').css('display','none');
              notii()  ;
           if(newnoti=='yes'){
                  $('.rmCount').css('display','inline-block');
                  $('.notibell').addClass('swingimage');      
           }
           else{
            $('.notibell').removeClass('swingimage');            
            $('.rmCount').css('display','none');
           }
               $('#shownoti').off('click');             
                  $('#shownoti').click(down);
          } 
     
});


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
      $(this).parents('.rem').find('.remdatetime').css('display','none');
       $(this).parents('.rem').find('.newreminder').css('display','none');
    $(this).parents('.rem').animate({width: "0px"},function(){
        $(this).remove();
        if($(".Notification-content").html() == ""){
             $('#shownoti').off('click');      
             $('#shownoti').click(down);
             $('.notis').slideUp();
        }
 
    });
})

 $('#clearallnoti').click(function(){
     $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/removeremindernotiall',
      method:'get'
      }); 
      $('.rem').find('.remdatetime').css('display','none');
       $('.rem').find('.newreminder').css('display','none');
      
    $('.rem').animate({width: "0px"},function(){
        $('.rem').remove();
        if($(".Notification-content").html() == ""){
             $('#shownoti').off('click');      
             $('#shownoti').click(down);
             $('.notis').slideUp();

        }
 
    });
 })
 </script>
