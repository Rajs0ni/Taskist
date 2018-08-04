
                $('#editreminder').click(function(){
                            $('#addreminder').fadeIn(200);
                              $.ajaxSetup({
                                    headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({                       
                                url: '/getremtime',
                                method:'get',
                                data:{
                                    id:{{$todo->id}}
                                },
                                success(response){       
                                    response=response.split('on');                             
                                    $('#datepicker').val(response[0]);
                                    $('#timepicker').val(response[1]);
                            
                                }
                                });
                               
                            $("#datepicker").datetimepicker({
                                    minDate:new Date(),
                                    altField:'#timepicker',
                                    dateFormat: 'dd-mm-yy'
                                });
                         })

                        $('body').on('click','#addrem',function(){
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
                                    id:{{$todo->id}},
                                    title:"{{$todo->title}}",
                                    date:date,
                                    time:time
                                }
                                });
                            }
                            $('#addreminder').fadeOut(200);
                            $('#datepicker').val('');
                            $('#timepicker').val('');

                            });


              

                  
                  