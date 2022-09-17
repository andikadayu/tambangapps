@extends('master')
@section('title','Add Schedule')
@section('content')
<div class="card">
    <form id="formSchedule">
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Description</label>
                        <input type="text" name="description" id="description" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Location From</label>
                        <input type="text" name="location_from" id="location_from" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Location To</label>
                        <input type="text" name="location_to" id="location_to" class="form-control" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Distance (KM)</label>
                        <input type="number" name="distance" id="distance" class="form-control" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Vehicle</label>
                        <select name="vehicleid" id="vehicleid" class="form-select form-control" required>
                            <option value="" selected disabled>Select Vehicle</option>
                            @foreach ($vehicle as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
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
                            <option value="{{$v->id}}">{{$v->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Date Picks</label>
                        <input type="datetime-local" name="date_picks" id="date_picks" class="form-control" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Aprroval</label>
                        <select name="approvalid[]" id="approvalid" multiple class="form-select form-control select2"
                            required>
                            @foreach ($approver as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                            @endforeach
                        </select>
                        <small>* List from first is Position who aprove first</small>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <a href="{{route('schedule')}}" class="btn btn-danger">Back</a>
            <button type="submit" class="btn btn-success float-right">Save</button>
        </div>
    </form>
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
            url:"{{route('add_schedule_process')}}",
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
</script>
@endsection