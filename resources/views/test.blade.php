
@extends('todo.app')

@section('content')
        @include('todo._viewstyle')
<div class="container">
  <h2>Dropdown Split Buttons</h2>
  <div class="btn-group">
    
    <button type="button" class="" data-toggle="dropdown" style="background:none;border:none; outline:none">...
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="#">Link 1</a>
      <a class="dropdown-item" href="#">Link 2</a>
    </div>
  </div>
  
 
</div>
      @include('todo._sideBar')
@endsection

@section('footer')
 
@endsection