(function ( $ ) {
    var id,title,oldval,editevent="",thishtml,canedit="",addlab=""; 
$('document').ready(function(){
    $('[data-toggle="tooltip"]').tooltip();    
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
        x = $(this).parents('.panel');
        x.css('background',color);

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
                alert('ERROR');
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
        x = $(this).parents('.grid');
        x.css('background',color);
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
                alert('ERROR');
            }
        });    

   });
   
});
//color picker for grid end
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


$(".addcollab").click(function(){
val = $(this).children().text();
$(".modal-body #val").val( val );
});

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
        $.ajax({
            url: "/setsession",
            data: { 
                _token : $('meta[name="csrf-token"]').attr('content'), 
                type : "success",
             message : "Invitation sent successfully"
            }
       }); 
    }
    else if(data[1]=='duplicate'){
        $.ajax({
            url: "/setsession",
            data: { 
                _token : $('meta[name="csrf-token"]').attr('content'), 
                type : "duplicate",
             message : "Collaborator already added!!"
            }
       }); 
    }
    else{
        $.ajax({
            url: "/setsession",
            data: { 
                _token : $('meta[name="csrf-token"]').attr('content'), 
                type : "yourself",
             message : "You are the owner!!Can't collaborate yourself!!"
            }
       });
    }
    //     $(this).parent().prev().append("Collaborator added successfully");
    //     console.log(data);
        location.reload(true);
    },
    error:function(){
        
        // $(this).parent().prev().append("Unable to add");
        $.ajax({
            _token : $('meta[name="csrf-token"]').attr('content'), 
            url: "/setsession",
            data: { 
                type :"alert",
                message : "Unable to add"
             }
            
       }); 
       location.reload(true);
        // alert("An error has occured !");
    } 

});
})
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

    $("body").on('click','#delete',function(){
        todo = $(this).parent().text();
        if(confirm("Are you sure you want to delete the task permanently?"))
            window.location.replace("/todo/"+todo+"/deleteTask");
        else
            window.reload(true);
    })

    $("#clearall").click(function(){
        val="";
        while(1){
            val=prompt(" Enter\n 1 : simply trash all the tasks \n 2 : permanently delete all the tasks ");
                if(val==1||val==2||val==null)
                    break;
        }
        if (val!=null)
           window.location.assign('/todo/clearall?val='+val);

    });

    $('body').on('click','#reminder',function(){
        $("#datepicker").datetimepicker({
            minDate:new Date(),
            altField:'#timepicker',
            dateFormat: 'dd-mm-yy'
           });
     id=$(this).parent().find('#task_id').val();           
     title=$(this).parent().find('#task_title').val();       
    })

    $('body').on('click','#snooze',function(){
        $("#datepicker").datetimepicker({
            minDate:new Date(),
            altField:'#timepicker',
            dateFormat: 'dd-mm-yy'
           });
     id=$(this).parent().find('#task_id').val();           
     title=$(this).parent().find('#task_title').val();
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
            var div=$("<div class='newlabel pl-3'></div>").css({'border':'1px solid lavender','margin':'3px','padding':'3px'});
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
              $("#addlabels").attr("placeholder",'ALREADY EXISTS');
          }
          else{
              $("#addlabels").attr("placeholder",'create new label');
              var span=$('<span class="dellabel"></span>').css({'display':'none','float':'right'});
              var span1=$('<span class="labelvalue"></span>').text(value);
              var i=$('<i class="fa fa-trash pr-3" ></i>');
              span.append(i);
              var div=$("<div class='newlabel pl-3'></div>").css({'border':'1px solid lavender','margin':'3px','padding':'3px'}); 
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
    var ip=$('<input type="text" class="newlabelval">').val(oldval).css({"border":"none","border-bottom":"1px solid"});
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
});



}( jQuery ));
