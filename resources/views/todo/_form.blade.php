        <div class='form-group'>
        {!! Form::label('title', 'Title:',['class' => 'formLabel']) !!}
        @if(!isset($search))
          {!! Form::text('title',null, ['class'=>'form-control show_content hover','placeholder'=>'Enter Title']) !!}
        @else
          {!! Form::text('title',$search, ['class'=>'form-control show_content hover','placeholder'=>'Enter Title']) !!}
        @endif
        </div>
        <div class='form-group'>
        {!! Form::label('task', 'Task:',['class' => 'formLabel']) !!}
        {!! Form::textarea('task', null, ['class'=>'form-control textarea show_content hover', 'placeholder'=>'Enter Task']) !!}
        </div>
        <div class='form-group'>
        {!! Form::label('completion_date', 'Completion Date:',['class' => 'formLabel']) !!}
        {!! Form::input('text', 'completion_date', $todo->completion_date, ['class'=>'form-control show_content hover','placeholder'=>'In Any Format']) !!}
        </div>
        <div class='form-group'>
        {!! Form::submit($submitButtonText,['class'=>'btn btn-danger formButton form-control','onclick'=>'test(this)']) !!}
        </div>