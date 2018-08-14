
(function ( $ ) {
    var taskid,id,title,oldval,editevent="",thishtml,canedit="",addlab=""; 

$('document').ready(function(){
   $('.row').mouseover(function(){
    $(this).find(".outersubmenu").css({'display':'block'});  
  });
  $('.row').mouseout(function(){
    $(this).find(".outersubmenu").css({'display':'none'});
});
// color picker for list

$('body').on('click',"#colorpicker",function()
{
    $('body').on('input',"#colorpicker",function()
    {
        color = $(this).val();
        id = $(this).parents('.color').children().text();
        $(this).parents('.panel').css('background','linear-gradient(90deg,'+color+',rgb(239, 240, 240)') ;

        //gr = linear-gradient(color,rgb(239, 240, 240));
        //x.css('background',color);

        $.ajax
        ({
            type: "GET",
            url: "/todo/color",
            data: { 
            _token : $('meta[name="csrf-token"]').attr('content'), 
            'color': color,
            'id' : id 
            }, 
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }, 
            success:function(response)
            {
            },
            error:function(response)
            {
            // alert('ERROR');
            }
        });
    });
});
// end color picker for list

//color picker for grid

$('body').on('click',"#grid_color",function()
{
    $('body').on('input',"#grid_color",function()
    {
        color = $(this).val();
        x = $(this).parents('.gridbtn').prev().children();
        y = $(this).parents('.gridbtn').prev().prev().children();
        $(x,y).each(function(id,element)
        {
            switch(id)
            {
               case 0: x.css('background',color);
               case 1: y.css('borderColor', color);
            }
        });
        id = $(this).parents('.gridbtn').children().val();
        $.ajax
        ({
            type: "GET",
            url: "/todo/color",
            data: { 
            _token : $('meta[name="csrf-token"]').attr('content'), 
            'color': color,
            'id' : id 
            }, 
            headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }, 
            success:function(response)
            {
            },
            error:function(response)
            {
            // alert('ERROR');
            }
        }); 

    });
});
//color picker for grid end
// color picker for theme

$("body").on('click',"#themecolor",function()
{
    $('body').on('input',"#themecolor",function()
    {
       color  = $(this).val();
       p = $('.menu_toggle');
       q  = $('.sideBarHeader');
       r = $('.viewtype');
       s = $('.panel');
       t = $('.text');
       u = $('.name');
       v = $('.count');
       $(p,q,r,s,t,u).each(function(id,element)
       {
          switch(id)
          {
            case 0:p.css('background',color);
            case 1:q.css('background',color);
            case 2:r.css('background',color);
            case 3:s.css('background',color);
            case 4:t.css('background',color);
            case 5:u.css('color',color);
            case 6:v.css('borderColor',color);
          }
       });
       $.ajax
        ({
            type: "GET",
            url: "/todo/themecolor",
            data:
            { 
              _token : $('meta[name="csrf-token"]').attr('content'), 
              'color': color
            }, 
            headers: 
            {
               'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }, 
            success:function(response)
            {
            },
            error:function(response)
            {
            // alert('ERROR');
            }
        }); 
    });

});
//color picker for theme end
$("body").on('click',".accept",function(){
    id = $(this).children().text();
    $.ajax({
        type:'GET',
        url:"/acceptcollab",
        data: { 
            _token : $('meta[name="csrf-token"]').attr('content'), 
            'id': id 
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        
        success:function(data){
            if(data[1]=='Accepted'){
                $.ajax({
                    url: "/setsession",
                    data: { 
                        _token : $('meta[name="csrf-token"]').attr('content'), 
                        type : "Accepted",
                     message : "Collaboration Accepted successfully"
                    }
               }); 
            location.reload(true);
            }
        },
        error:function(){
            $.ajax({
                url: "/setsession",
                data: { 
                    _token : $('meta[name="csrf-token"]').attr('content'), 
                    type : "Error",
                 message : "Please try later!!"
                }
           }); 
        location.reload(true);
        } 
    
    });
 });

$("#modaldone,.modalclose").click(function(){
    location.reload(true);
});
 $("body").on('click',".reject",function(){
    id = $(this).children().text();

    $.ajax({
        type:'GET',
        url:"/rejectcollab",
        data: { 
            _token : $('meta[name="csrf-token"]').attr('content'), 
            'id': id 
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        
        success:function(data){
            if(data[1]=='Rejected'){
                $.ajax({
                    url: "/setsession",
                    data: { 
                        _token : $('meta[name="csrf-token"]').attr('content'), 
                        type : "Rejected",
                     message : "Collaboration Rejected"
                    }
               }); 
            location.reload(true);
            }
        },
        error:function(){
            $.ajax({
                url: "/setsession",
                data: { 
                    _token : $('meta[name="csrf-token"]').attr('content'), 
                    type : "Error",
                 message : "Please try later!!"
                }
           }); 
        location.reload(true);
        } 
    
    });
 });

$("#sort").click(function(){

    $var = window.location.pathname;
    
    if($var=='/todo/getProcessing')
        location.assign('/sort/by/title/'+0);
    else if($var=='/collab')
        location.assign('/sort/by/title/'+1);
    else if($var=='/todo/getPending')
        location.assign('/sort/by/title/'+2);
    else if($var=='/todo/getcompleted')
        location.assign('/sort/by/title/'+3);
    else if($var=='/todo/all')
        location.assign('/sort/by/title/'+4);
    else if($var=='/todo/archive')
        location.replace('/sort/by/title/'+5);
    else if($var=='/todo/trash')
        location.replace('/sort/by/title/'+6);
    else if($var=='/sort/by/title/0'||$var=='/sort/by/title/1'||$var=='/sort/by/title/2'||$var=='/sort/by/title/3'||$var=='/sort/by/title/4'||$var=='/sort/by/title/5'||$var=='/sort/by/title/6')
        location.reload(true);
    else
        location.replace('/sort/by/title/'+7);
      
    

});

$("#date").click(function(){
    $var = window.location.pathname;
    
    if($var=='/todo/getProcessing')
        location.assign('/sort/by/date/'+0);
    else if($var=='/collab')
        location.assign('/sort/by/date/'+1);
    else if($var=='/todo/getPending')
        location.assign('/sort/by/date/'+2);
    else if($var=='/todo/getcompleted')
        location.assign('/sort/by/date/'+3);
    else if($var=='/todo/all')
        location.assign('/sort/by/date/'+4);
    else if($var=='/todo/archive')
        location.replace('/sort/by/date/'+5);
    else if($var=='/todo/trash')
        location.replace('/sort/by/date/'+6);
    else if($var=='/sort/by/date/0'||$var=='/sort/by/date/1'||$var=='/sort/by/date/2'||$var=='/sort/by/date/3'||$var=='/sort/by/date/4'||$var=='/sort/by/date/5'||$var=='/sort/by/date/6')
        location.reload(true);
    else
        location.replace('/sort/by/date/'+7);
      
    

})

$(".addcollab").click(function(){
val = $(this).children().text();
$(".modal-body #val").val( val );

$.ajax({
    type:'GET',
    url:"/getcollaborator",
    data: { 
        _token : $('meta[name="csrf-token"]').attr('content'), 
        'id': val
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    
    success:function(data){
        $(".umm").text("");
        res='<div class="umm">';
        if(data[1]=="no"){
            res+="No Collaborators yet!! Add some!</div>";
            $(".modal-body").prepend(res);
        }
        else{
            for (i in data["msg"]){
                res += '<div class="collabusers" >'+data.msg[i].name+'<span hidden>'+data.msg[i].id+'</span><span class="remove hidden"> &times;</span>&nbsp;&nbsp;</div>';
            }
            res+='</div>';
            $(".modal-body").prepend(res);
        }
    }

});
});

$("body").on('click',"#uncollab",function(){
    if(confirm("Are you sure you want to uncollaborate?")){
        task=$(this).children().text();
        $.ajax({
            type:'GET',
            url:"/uncollab",
            data: { 
                _token : $('meta[name="csrf-token"]').attr('content'), 
                'task': task
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            
            success:function(data){
                window.location.assign("/collab");
            }
        }); 
    }
});

$("body").on('click',".remove",function(){
    user=$(this).prev().text();
    task=$(this).parents(".modal-body").children().last().val();
    $.ajax({
        type:'GET',
        url:"/removecollaborator",
        data: { 
            _token : $('meta[name="csrf-token"]').attr('content'), 
            'task': task,
            'user':user
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        
        success:function(data){
           if(data[1]=="no"){
               alert("You are not the owner! Hece can't remove collaborations!!")
           }
           else{
            $(".umm").html("");
            $.ajax({
                type:'GET',
                url:"/getcollaborator",
                data: { 
                    _token : $('meta[name="csrf-token"]').attr('content'), 
                    'id': val
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                
                success:function(data){
                    $(".umm").text("");
                    res="<div class='umm'>";
                    if(data[1]=="no"){
                        res+="No Collaborators yet!! Add some!</div>"
                        $(".modal-body").prepend(res);
                }
                    else{
                        for (i in data["msg"]){
                            res += '<div class="collabusers" >'+data.msg[i].name+'<span hidden>'+data.msg[i].id+'</span><span class="remove hidden"> &times;</span>&nbsp;&nbsp;</div>';
                        }
                        res+="</div>";
                        $(".modal-body").prepend(res);
                    }
                }
            
            });
           }
        }
    
    });
});

// $("body").on('keyup','.email',function(){
//     str=$(this).val();
//     $.ajax({
//         type:'GET',
//         url:"/suggestcollab",
//         data: { 
//             _token : $('meta[name="csrf-token"]').attr('content'), 
//             'q': str 
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//         },
        
//         success:function(data){

//         }
    
//     });
// });

$("body").on('mouseenter',".collabusers",function(){
    $(this).children().last().removeClass("hidden");
})
$("body").on('mouseleave',".collabusers",function(){
    $(this).children().last().addClass("hidden");
})


$("#addCollaborator").click(function(){
   id = $("#val").val();
  
   email = $("#collab").val();
   $.ajax({
    type:'GET',
    url:"/addcollaborator",
    data: { 
        _token : $('meta[name="csrf-token"]').attr('content'), 
        'id': id,
        'email': email 
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    },
    
    success:function(data){
        if(data[1]=='success'){
            $(".umm").html("");
            $.ajax({
                type:'GET',
                url:"/getcollaborator",
                data: { 
                    _token : $('meta[name="csrf-token"]').attr('content'), 
                    'id': val
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                
                success:function(data){
                    $(".umm").text("");
                    res="<div class='umm'>";
                    if(data[1]=="no"){
                        res+="No Collaborators yet!! Add some!</div>"
                        $(".modal-body").prepend(res);}
                    else{
                        for (i in data["msg"]){
                            res += '<div class="collabusers" >'+data.msg[i].name+'<span hidden>'+data.msg[i].id+'</span><span class="remove hidden"> &times;</span>&nbsp;&nbsp;</div>';
                        }
                        res+="</div>";
                        $(".modal-body").prepend(res);
                        $("#collab").val("");
                    }
                }
            
            });
    }
    else if(data[1]=='duplicate'){
    //     $.ajax({
    //         url: "/setsession",
    //         data: { 
    //             _token : $('meta[name="csrf-token"]').attr('content'), 
    //             type : "duplicate",
    //          message : "Collaborator already added!!"
    //         }
    //    }); 
    alert("Already added");
    $("#collab").val("");
    }
    else{
    //     $.ajax({
    //         url: "/setsession",
    //         data: { 
    //             _token : $('meta[name="csrf-token"]').attr('content'), 
    //             type : "yourself",
    //          message : "You are the owner!!Can't collaborate yourself!!"
    //         }
    //    });
    alert("You are the owner yourself!");
    $("#collab").val("");
    }
    // $(this).parent().prev().append("Collaborator added successfully");
    //     console.log(data);
        //location.reload(true);
    },
    error:function(){
        
        // $(this).parent().prev().append("Unable to add");
    //     $.ajax({
    //         _token : $('meta[name="csrf-token"]').attr('content'), 
    //         url: "/setsession",
    //         data: { 
    //             type :"alert",
    //             message : "Unable to add"
    //          }
            
    //    }); 
    alert("No such user!! Please check credentials!!");
    //    location.reload(true);
        // alert("An error has occured !");
    } 

});
})
v=$(".CRCount").text();
if(v=='0'){
    $(".CRCount").hide();
}
if(v!='0'){
    $(".CRCount").show();
}

    $("body").on('click','#pin',function(){
        todo = $(this).parent().text();
        
            window.location.replace("/todo/pin/"+todo);
        
    })
    $("body").on('click','#trash',function(){
        todo = $(this).parent().text();
        if(confirm("Are you sure you want to trash the task?"))
            window.location.replace("/todo/trash/"+todo);
        else
            window.reload(true);
    })

    $("body").on('click','#archive',function(){
        todo = $(this).parent().text();
            window.location.replace("/todo/archive/"+todo);

    })

    $("body").on('click','#unarchive',function(){
        todo = $(this).parent().text();

            window.location.replace("/todo/unarchive/"+todo);
       
    })

    $("body").on('click','#restore',function(){
        todo = $(this).parent().text();

            window.location.replace("/todo/restore/"+todo);
       
    })

    $("#collabrequest").click(function(){
        $("#collabnotific").toggle();
    });
    $("body").on('click','#delete',function(){
        todo = $(this).children().text();
      
        if(confirm("Are you sure you want to delete the task permanently?"))
            window.location.replace("/todo/"+todo+"/deleteTask");
        else
            window.reload(true);
    })
    // $("#notify").hide();
    $("body").on('click','#collabrequest',function(){
        $("#collabnotific").css({'display':'block'});
      
        $("#notify").html("");
        // var divNotificationHeader = '<div class="Notification-header"></div>';
        // divNotificationHeader.append('Collaboration Requests').append('<button type="button" class="close mr-2 mt-1" data-dismiss="modal" >&times;</button>');
        // $("#notify").append(divNotificationHeader);
        // var divNotificationContainer = '<div class="Notification-content"></div>';
        // $("#notify").append(divNotificationContainer);
       
            $.ajax({
                type:'GET',
                url:"/getrequest",
                data: { 
                    _token : $('meta[name="csrf-token"]').attr('content')
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                
                success:function(data){
                    if(data[1]==""){
                        var span=$('<span class="no-notifications-msg"></span>').text('No Notifications');
                            $("#notify").append('<i class="fa fa-bell no-notifications-bell"></i>').append(span).append('<p class="noti_MSG">You have no new Notifications.</p>');
                    }
                    else 
                    $("#notify").prepend(data[1]);
                    // console.log(data[1])
                },
                error:function(){
                    
                } 
            
            });
        
        // else{
        //     var span=$('<span class="no-notifications-msg"></span>').text('No Notifications');
        //     $("#notify").append('<i class="fa fa-bell no-notifications-bell"></i>').append(span).append('<p class="noti_MSG">You have no new Notifications.</p>');
        // }
    });

    // $("#clearall").click(function(){
    //     val="";
    //     while(1){
    //         val=prompt(" Enter\n 1 : simply trash all the tasks \n 2 : permanently delete all the tasks ");
    //             if(val==1||val==2||val==null)
    //                 break;
    //     }
    //     if (val!=null)
    //        window.location.assign('/todo/clearall?val='+val);

    // });

    $('body').on('click','#reminder',function(){
        $("#datepicker").datetimepicker({
            minDate:new Date(),
            altField:'#timepicker',
            dateFormat: 'dd-mm-yy'
           });
     id=$(this).parent().find('#task_id').val();           
     title=$(this).parent().find('#task_title').val();     
    
     $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({                       
    url: '/getremtime',
    method:'get',
    data:{
        id:id
    },
    success(response){       
        if(response !="no reminder"){
                response=response.split('on');                             
                $('#datepicker').val(response[0]);
                $('#timepicker').val(response[1]);
        }
    }
    });  
    })

    $('body').on('click','#snooze',function(){
        $("#datepicker").datetimepicker({
            minDate:new Date(),
            altField:'#timepicker',
            dateFormat: 'dd-mm-yy'
           });
     id=$(this).parent().find('#task_id').val();           
     title=$(this).parent().find('#task_title').val();

     $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({                       
    url: '/getremtime',
    method:'get',
    data:{
        id:id
    },
    success(response){       
        if(response !="no reminder"){
                response=response.split('on');                             
                $('#datepicker').val(response[0]);
                $('#timepicker').val(response[1]);
        }
    }
    });
        })

    $('#addremm').click(function(){
        var date=$('#datepicker').val();
        var time=$('#timepicker').val();
        if(date!="" && time!=""){
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          $.ajax({                       
          url: '/addreminder',
          method:'post',
          data:{
            id:id,
            title:title,
            date:date,
            time:time
          }
        });
    }
    $('#datepicker').val('');
    $('#timepicker').val('');
    
    
      });
    
              
    $(".close").click(function(){      
      $('#datepicker').val('');
      $('#timepicker').val('');
  });
  
  $('#add_reminder').click(function(){
    var date=$('#datepicker').val();
    var time=$('#timepicker').val();
    if(date!="" && time!=""){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/addreminder',
      method:'post',
      data:{
        id:id,
        title:title,
        date:date,
        time:time
      }
    });
}
$('#datepicker').val('');
$('#timepicker').val('');


  });

  $('#labels_add').click(function(){
    $("#addlabels").attr("placeholder",'create new label');

    $("#alllabels").empty();  
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/getlabels',
      method:'get',
      success(response){
        if(response.length>0){
        for(var i=0;i<response.length;i++){
            var span=$('<span class="dellabel"></span>').css({'display':'none','float':'right'});
            var span1=$('<span class="labelvalue"></span>').text(response[i].name);
            var ii=$('<i class="fa fa-trash pr-3" ></i>');
            span.append(ii);
            var div=$("<div class='newlabel'></div>");	
            div.append(span1).append(span);
            $("#alllabels").append(div);
        }
    }
    }
    });
 })
  $("#addlabels").on('keyup', function (e) {
    addlab="ready";
    if (e.keyCode == 13) {
        addlabels();
    }
});

$('html').click(function(event){
    if(addlab=="ready"){
         if(event.target.id =="addlabels")
             return;
        
        addlabels();
        addlab="";
    }
})
function addlabels(){
    if($("#addlabels").val()!=""){
        var value=$("#addlabels").val().toUpperCase();
      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({                       
        url: '/addnewlabel',
        method:'post',
        data:{
          val:value
        },success(response){
          if(response == 'exists'){
              $("#addlabels").val('');
              $("#addlabels").attr("placeholder",'Already Exists');
          }
          else{
              $("#addlabels").attr("placeholder",'create new label');
              var span=$('<span class="dellabel" ></span>').css({'display':'none','float':'right'});
              var span1=$('<span class="labelvalue" ></span>').text(value);
              var i=$('<i class="fa fa-trash pr-3 " ></i>');
              span.append(i);
              var div=$("<div class='newlabel'></div>"); 
              div.append(span1).append(span);
              $("#alllabels").append(div);
              $("#addlabels").val('');
          }
        }
      });
      
    }
}
$('body').on('mouseenter','.newlabel',function(){
    $(this).css('background','rgba(239,239,240,0.9)');  
  $(this).find('.dellabel').css('display','inline');
})

$('body').on('mouseleave','.newlabel',function(){
    $(this).css('background','#FFFFFF');
    $(this).find('.dellabel').css('display','none');
})

$('body').on('click','.dellabel',function(){
    var val = $(this).parent().find('.labelvalue').text();
    if(confirm('DELETE LABEL "'+ val +'"')){
      $(this).parent().remove();
     $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/dellabel',
      method:'get',
      data:{
        val:val
      }
    });
}
})

$('body').on('dblclick','.newlabel',function(event){
     editevent="ready";
    event.stopImmediatePropagation();
     oldval = $(this).find('.labelvalue').text();
    var ip=$('<input type="text" class="newlabelval">').val(oldval);
    $(this).html(ip);
});

$("body").on('keyup','.newlabelval' ,function (e) {
    thishtml=this;
    if (e.keyCode == 13) {
        edithandler1(thishtml);
    }
   else if( canedit=="ready"){
        return;
    }
    else{
        edithandler2(thishtml);
    }
});

$('html').on('click',function(e){
    if(editevent == 'ready'){
        if(e.target.nodeName =="INPUT"){
            canedit="ready";
            return;      
        }

        canedit="";
        edithandler();
        editevent="";
    }
})
function edithandler(){
 $('.newlabelval').keyup();
}
function edithandler1(thishtml){
    if($(".newlabelval").val()==""){
        $(thishtml).replaceWith('<span class="labelvalue">'+oldval+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');
    }  
    else{   
        var value=$(".newlabelval").val().toUpperCase();
      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({                       
        url: '/updatelabel',
        method:'post',
        data:{
          oldval:oldval,
          newval:value
        },success(response){
          if(response == 'exists'){
            $(thishtml).replaceWith('<span class="labelvalue">'+oldval+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');
             
          }
          else{
              $(".newlabelval").attr("placeholder",'edit label');
              $(thishtml).replaceWith('<span class="labelvalue">'+value+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');
              }
        }
      });
      
    }
}

function edithandler2(thishtml){
    if($(".newlabelval").val()==""){
        $(thishtml).replaceWith('<span class="labelvalue">'+oldval+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');
    }  
 else{
    var value=$(".newlabelval").val().toUpperCase();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({                       
        url: '/updatelabel',
        method:'post',
        data:{
          oldval:oldval,
          newval:value
        },success(response){
            if($(".newlabelval").val()== ""){
           $(thishtml).replaceWith('<span class="labelvalue">'+oldval+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');             
        
         }
          else if(response == 'exists'){
              $(thishtml).replaceWith('<span class="labelvalue">'+oldval+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');             
          }
          else{
              $(".newlabelval").attr("placeholder",'edit label');
              $(thishtml).replaceWith('<span class="labelvalue">'+value+'</span><span class="dellabel" style="display: none; float: right;"><i class="fa fa-trash pr-3"></i></span>');
              }
        }
      });
      
    }   
}

$('body').on('click','#tasklabel',function(){
    $("#alllabelstask").empty();  
    $('#searchlabels').val('');
    taskid=$(this).find('div').text(); 
    labelsontask();
 })

 function labelsontask(){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/getlabelstask',
      method:'get',
      data:{
        taskid:taskid
      },
      success(response){
         if(response.length>0){
         
            $("#alllabelstask").css({'display':'block','max-height':'300px','overflow':'auto'});
              if(typeof response[0] == 'object' &&   response[1]  instanceof Array){
              
                   
                for(var i=0;i<response[1].length;i++){
                    var ip=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'}).prop('checked',true);
                    var ipp=$("<input type='hidden' class='labelid'>").val(response[1][i].id);
                    var span=$('<span></span>').text(response[1][i].name);
                    var div=$('<div class="labelcheck"></div>').append(ipp).append(ip).append(span);
                    $("#alllabelstask").append(div);
                }
             
                for(var prop in response[0]){
                    var ip=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'});
                    var ipp=$("<input type='hidden' class='labelid'>").val(response[0][prop].id);
                    var span=$('<span></span>').text(response[0][prop].name);
                    var div=$('<div class="labelcheck"></div>').append(ipp).append(ip).append(span);
                    $("#alllabelstask").append(div);
                } 
                         
            }
            else{
                           
        for(var i=0;i<response.length;i++){
            var ip=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'});
            var ipp=$("<input type='hidden' class='labelid'>").val(response[i].id);
            var span=$('<span></span>').text(response[i].name);
            var div=$('<div class="labelcheck"></div>').append(ipp).append(ip).append(span);
            $("#alllabelstask").append(div);
        }
       
    }
    }
    else{
        $("#alllabelstask").css({'display':'none'});
    }
    
}
    });
 }

 $('body').on('click',".labelcheck",function(event){
    var labid=$(this).find('.labelid').val();
 
    if(event.target.id == 'individuallab'){
            if($(this).find('.individuallab').prop('checked')) 
               addlabrel(labid);
            else
               dellabelrel(labid);
           return;     
        }
                
     $(this).find('.individuallab').prop('checked', !$(this).find('.individuallab').prop('checked'));
     if($(this).find('.individuallab').prop('checked')) 
        addlabrel(labid);
     else
       dellabelrel(labid);
 });

 function addlabrel(labid){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/addlabelrel',
      method:'post',
      data:{
        labid:labid,
        taskid:taskid
      }
    });
 }

 function dellabelrel(labid){

    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/dellabelrel',
      method:'post',
      data:{
        labid:labid,
        taskid:taskid
      }
    });

 }

 $('#searchlabels').keyup(function(e){
       value= $(this).val().toUpperCase();
       if (e.keyCode == 13) {
        if($('*').find('.createsearched').css('display')=='block'){
            $('.createsearched').click();
        }
       }
       if(value == ""){
        $("#alllabelstask").empty();
         labelsontask();
       }
    else{
       var search; 
       $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/searchlabels',
      method:'get',
      data:{
        val:$(this).val().toUpperCase()
      },
      success(response){
          search=response;
          $("#alllabelstask").css('display','block');
        if(response == 'notexists'){
            $("#alllabelstask").empty();   
            var div = $('<div class="createsearched"></div>').text('+ CREATE LABEL '+value).css({'border':'1px solid lavender','margin':'3px','padding':'3px','cursor':'pointer'});
            $("#alllabelstask").append(div);
     }
    else{
         $("#alllabelstask").empty();   
         
        for(var i=0;i<search.length;i++){
            searchrequest(search[i]);  
        }
      
    }
  }  
});
 }
 function searchrequest(search){
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/relexists',
      method:'get',
      data:{
        labid:search.id,
        taskid:taskid
      },
      success(res){
        if(res == 'yes')
            var ip=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'}).prop('checked',true);
                    
        else
            var ip=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'});
       
            var ipp=$("<input type='hidden' class='labelid'>").val(search.id);
            var span=$('<span></span>').text(search.name);
            var div=$('<div class="labelcheck"></div>').append(ipp).append(ip).append(span).css({'border':'1px solid lavender','margin':'3px','padding':'3px'});
            $("#alllabelstask").append(div);         

      }
    });

 }
    $('body').on('click','.createsearched',function(event){
            $("#alllabelstask").empty();
            var i=$("<input type='checkbox' class='individuallab' id='individuallab'>").css({'margin':'3px'}).prop('checked',true);
            var spann=$('<span></span>').text($('#searchlabels').val().toUpperCase());
            var divv=$('<div class="labelcheck"></div>').append(i).append(spann).css({'border':'1px solid lavender','margin':'3px','padding':'3px'});
            labelsontask();
            $("#alllabelstask").prepend(divv);
            
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
              $.ajax({                       
              url: '/addnewsearch',
              method:'get',
              data:{
                val:$('#searchlabels').val().toUpperCase(),
                taskid:taskid
              }
            });
            $('#searchlabels').val('');        
            event.stopImmediatePropagation();

    })
 })

 $('#labelsavail').click(function(){
    $('#alllabelsavail').empty();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({                       
      url: '/getlabels',
      method:'get',
      success(response){
        if(response.length>0){
            for(var i=0;i<response.length;i++){
            var div=$("<div class='dropdown-item labelsreltasks'></div>").text(response[i].name);  ;
            var ip=$('<input type="hidden" id="tasksrellab">').val(response[i].id);
            div.append(ip);
            $("#alllabelsavail").append(div);
        }
    }
    else{
        var div=$("<div class='dropdown-item'></div>").text('No Labels')  ;
        $("#alllabelsavail").append(div); 
       }
    }
    });
 })


 $('body').on('click','.labelsreltasks',function(){
    var labelid =$(this).find('#tasksrellab').val();
    window.location.assign('/getlabelstasks/'+labelid);
    
 })
});

}( jQuery ));
