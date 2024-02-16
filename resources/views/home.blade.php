@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>{{ __('Dashboard') }}</div>
                    <a class="btn btn-sm btn-success" href="{{ route('task') }}"> Create New Task</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                   
                    <table class="table table-bordered" id="task-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->details }}</td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="{{ route('task',$task->id) }}">Edit</a>
                                        <button class="btn btn-sm btn-danger delete" id="{{$task->id}}">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#task-table').DataTable();
    });
    $(document).on('click','.delete',function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        Swal.fire({  
            title: 'Delete Task?', 
            icon: 'warning',
            html:"Once Deleted, you will not be able to revert this!",
            showCancelButton: true,
            confirmButtonText:'Ok',
        }).then((result) => { 
        if (result.isConfirmed) {  
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url:  '{{route('task_delete')}}',
                data: {'id':id},
                success: function(data) {
                    var res = data;
                    if(data.status == 'Success'){
                        Swal.fire({  
                            title: data.msg, 
                            icon: 'success',
                            confirmButtonText:'OK',
                        }).then((result) => { 
                            if (result.isConfirmed) {  
                                location.reload();
                            }
                        });
                    }
                    if(data.status == 'Error'){
                        Swal.fire({  
                            title: 'Error',
                            text: errorMessages,
                            icon: 'error',
                            confirmButtonText: 'OK',
                        }).then((result) => { 
                            if (result.isConfirmed) {  
                            }
                        });
                    }
                }
            });
        }
        });
    });
</script>
@endsection
