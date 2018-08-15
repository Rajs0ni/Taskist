@extends('todo.app')

@section('content')

<span id="mainHeading">TaskIST</span>
<div class="form-group">
    <div class="flag">   
        @foreach($user as $u)
          <?php $color = $u->themeColor;?>
        @endforeach
          <span class="flagStyle" id="search" style="color:<?php echo $color; ?>;">Search</span>
   

    </div>
   {!! Form::open(array('action' => 'TodosController@find')) !!}
        <div class='form-group'>
            {!! Form::text('keyword',null, ['class'=>'form-control hover','placeholder'=>'Type your keyword']) !!}
        </div>
        <div class='form-group'>
            {!! Form::submit('Task Search',['class'=>'btn btn-default searchButton form-control']) !!}
        </div>
  {!! Form::close() !!}
@endsection