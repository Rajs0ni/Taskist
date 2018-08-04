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


$(".collab").hide();
$(".addcollab").click(function(){
// $("html,body").addClass("disabled");
// $(".collab").addClass("enabled");
$(".collab").show();
});
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