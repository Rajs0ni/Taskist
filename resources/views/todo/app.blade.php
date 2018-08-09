<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RTWT</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" >
     <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" ></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  
  
     <link rel="stylesheet" href="/css/datetime.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" type="text/css">   
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
  <script src="/js/datetime.js"></script>

  
    <link rel="stylesheet" type="text/css" href=/css/mystyle.css >
    <link rel="stylesheet" type="text/css" href=/css/offcanvas.css >
    <link rel="stylesheet" type="text/css" href=/css/deleteModal.css >
    <link rel="stylesheet" type="text/scss" href=/css/offcanvas.scss >
   
    <script src="/js/todo.js"></script>
  
 </head>
    @yield('styles')
<body>
    <div class="page">
      
        @foreach($user as $u)
           <?php $color = $u->themeColor;?>
        @endforeach
              <span class="menu_toggle" style="background:<?php echo $color; ?>;">
    
        <i class="menu_open fa fa-bars fa-lg"></i>
        <i class="menu_close fa fa-times fa-lg"></i>
    </span>
    <ul class="menu_items">
        <li><a href="/todo"><i class="icon fa fa-home fa-2x"></i>Home</a></li>
        <li><a href={{action('TodosController@archived') }}><i class="icon fa fa-archive fa-2x"></i>Archive</a></li>
        <li><a href={{action('TodosController@trash') }}><i class="icon fa fa-trash fa-2x"></i>Trash</a></li>
    </ul>
    <main class="content">
        <div class="content_inner">
        @yield('content')
        </div>
        @yield('footer')
    </main>
    <div class="container">@yield('theme')</section>
</div>

<script>
	// elements
var $page = $('.page');

$('.menu_toggle').on('click', function(){
  $page.toggleClass('myclass');
});
$('.content').on('click', function(){
  $page.removeClass('myclass');
});
</script>

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script> -->

    <script>
    $('div.alert').not('.alert-important').delay(2000).slideUp(300);
 </script>

@include('todo.remindermodalbox')
                <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="add_reminder" data-dismiss="modal">DONE</button>
                        </div>
                        
                    </div>
                    </div>
                </div>


@include('todo.tasklabels')

</body>
</html>