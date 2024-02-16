@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>{{isset($task)?'Edit':'Add'}} Task</div>
                    <a class="btn btn-sm btn-link" href="{{ route('home') }}">Back</a>
                </div>
                <div class="card-body">
                    <form id="task-form" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{isset($task)?$task->name:''}}" required>
                                    <input type="hidden" name="id" value="{{isset($task)?$task->id:''}}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label>Details:</label>
                                    <textarea class="form-control" rows="5" name="details" placeholder="Details" required>{{isset($task)?$task->details:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).on('submit','#task-form',function(e){
            e.preventDefault();
            var formData = $('#task-form').serialize();
            $('.btn').attr('disabled',true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url:  '{{route('task_store')}}',
                data: formData,
                success: function (data) {
                    if(data.status == 'Success'){
                        Swal.fire({  
                            title: data.msg, 
                            icon: 'success',
                            confirmButtonText:'OK',
                        }).then((result) => { 
                            if (result.isConfirmed) {  
                                window.location.href = "{{ route('home') }}";
                            }
                        });
                    }
                    if(data.status == 'Error'){
                        let errorMessages = '';
                        $.each(data.msg, function (key, value) {
                            errorMessages += value + '\n';
                        });
                        Swal.fire({  
                            title: 'Error',
                            text: errorMessages,
                            icon: 'error',
                            confirmButtonText: 'OK',
                        }).then((result) => { 
                            if (result.isConfirmed) {  
                                $('.btn').attr('disabled',false);
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
