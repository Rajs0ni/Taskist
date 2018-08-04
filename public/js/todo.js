(function ( $ ) {
    var id,title; 
$('document').ready(function(){
    $('[data-toggle="tooltip"]').tooltip();    
  $('.row').mouseover(function(){
    $(this).find(".outersubmenu").css({'display':'block'});  
  });
  $('.row').mouseout(function(){
    $(this).find(".outersubmenu").css({'display':'none'});
});

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
        $('#addreminder').fadeIn(200);
        $("#datepicker").datetimepicker({
            minDate:new Date(),
            altField:'#timepicker',
            dateFormat: 'dd-mm-yy'
           });
     id=$(this).parent().find('#task_id').val();           
     title=$(this).parent().find('#task_title').val();       
    })
          
    $(".close").click(function(){      
      $('#addreminder').fadeOut(200);
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
$('#addreminder').fadeOut(200);
$('#datepicker').val('');
$('#timepicker').val('');


  });

  
});


}( jQuery ));
