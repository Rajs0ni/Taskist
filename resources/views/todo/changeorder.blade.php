@extends('todo.app')

@section('styles')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <style>
  #sortable { 
      list-style-type: none; 
      margin: 0; 
      padding: 0; 
      width: 60%; 
      
    }
  #sortable li { 
      margin: 0 3px 3px 3px; 
      padding: 0.4em; 
      padding-left: 1.5em; 
      font-size: 1.4em; 
      height: 40px; 
    }
  #sortable li span { 
      position: absolute; 
      margin-left: -1.3em; 
    }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    
    var getXsrfToken = function () {
        var cookies = document.cookie.split(';');
        var token = '';

        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].split('=');
            if (cookie[0] == 'XSRF-TOKEN') {
                token = decodeURIComponent(cookie[1]);
            }
        }

        return token;
    };
    $.ajaxSetup({
        headers: {
            'X-XSRF-TOKEN': getXsrfToken()
        }
    });
    $( "#sortable" ).sortable({
    axis: 'y',
    update: function (event, ui) {
        var data = $(this).sortable('serialize');
        
        // POST to server using $.post or $.ajax
        $.ajax({
            data: data,
            type: 'POST',
            url: '/todo/changeorder'
        });
    }
});
    $( "#sortable" ).disableSelection();
  } );
  </script>
@endsection
 
@section('content')
<ul id="sortable">
@foreach ($todos as $todo)
    <li class="ui-state-default" id="item-{{$todo->id}}"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>{{$todo->title}}</li>
@endforeach
</ul>
<hr>
<a href="/todo/myorder">Done</a>
@endsection

