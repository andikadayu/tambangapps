@extends('master')
@section('title','Detail Schedule')
@section('content')
<div class="card">
    <form id="formSchedule">
        <div class="card-body">
            <input type="hidden" name="scheduleid" value="{{$scheduleid}}">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" id="title" value="{{$schedule->title}}" class="form-control"
                            required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" name="description" id="description" value="{{$schedule->description}}"
                            class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Location From</label>
                        <input type="text" name="location_from" id="location_from" value="{{$schedule->location_from}}"
                            class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Location To</label>
                        <input type="text" name="location_to" id="location_to" value="{{$schedule->location_to}}"
                            class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Distance (KM)</label>
                        <input type="number" name="distance" id="distance" value="{{$schedule->distance}}"
                            class="form-control" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Vehicle</label>
                        <select name="vehicleid" id="vehicleid" class="form-select form-control" required>
                            <option value="" selected disabled>Select Vehicle</option>
                            @foreach ($vehicle as $v)
                            <option value="{{$v->id}}" @if($v->id == $schedule->vehicleid) selected @endif>{{$v->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Driver</label>
                        <select name="driverid" id="driverid" class="form-select form-control" required>
                            <option value="" selected disabled>Select Driver</option>
                            @foreach ($driver as $v)
                            <option value="{{$v->id}}" @if($v->id == $schedule->driverid) selected @endif>{{$v->name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Date Picks</label>
                        <input type="datetime-local" name="date_picks" id="date_picks" value="{{$schedule->date_picks}}"
                            class="form-control" required>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <a href="{{route('schedule')}}" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-success float-right" @if(session('roles') !='admin' ) disabled
                @endif>Save</button>
        </div>
    </form>
</div>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Approval</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name Approver</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($approval as $item)
                    <tr>
                        <td>{{$item->approval_name}}</td>
                        <td>@if ($item->status == 1)
                            <span class="badge badge-success">Confirmed</span>
                            @elseif($item->status == 0)
                            <span class="badge badge-info">Waiting</span>
                            @else
                            <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if (session('userid') == $item->approvalid)
                            <button class="btn btn-sm btn-success" onclick="confirm({{$item->id}})"><i
                                    class="fa fa-check"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="reject({{$item->id}})"><i
                                    class="fa fa-ban"></i></button>
                            @else
                            N/A
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $('#formSchedule').on('submit',function (e) {
        e.preventDefault();
        $.ajax({
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{route('update_schedule_process')}}",
            method:'POST',
            processData:false,
            contentType:false,
            type:'post',
            data: new FormData(this),
            dataType:'json',
            success:function (response){
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    location.reload();
                }else{
                    Swal.fire(
                        'Error',
                        response.msg,
                        'error'
                    );
                }
            }
        });
    });

    function confirm(id) {
    Swal.fire({
        title: 'Do you want to confirm?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Save',
        denyButtonText: `Don't save`,
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
        $.ajax({
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{route('confirm_reject')}}",
            method:'POST',
            data:{id:id,status:1},
            dataType:'json',
            success:function (response){
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    location.reload();
                }else{
                    Swal.fire(
                        'Error',
                        response.msg,
                        'error'
                    );
                }
            }
        })
    }
    })
    }

    function reject(id) {
    Swal.fire({
        title: 'Do you want to reject?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Save',
        denyButtonText: `Don't save`,
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
        $.ajax({
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{route('confirm_reject')}}",
            method:'POST',
            data:{id:id,status:2},
            dataType:'json',
            success:function (response){
                if(response.status){
                    Swal.fire('Success',response.msg,'success');
                    location.reload();
                }else{
                    Swal.fire(
                        'Error',
                        response.msg,
                        'error'
                    );
                }
            }
        })
    }
    })
    }
</script>
@endsection