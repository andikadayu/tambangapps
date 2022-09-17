@extends('master')
@section('title','Schedule')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div class="row">
                <div class="col">
                    <input type="month" name="month" id="month" class="form-control">
                </div>
                <div class="col">
                    <button class="btn btn-info" onclick="exportxlsx()"><i class="fa fa-file-excel"></i> Export</button>
                </div>
            </div>
            <div>
                <a href="{{route('add_schedule')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></a>
            </div>
        </div>

        <div class="table-responsive mt-2">
            <table class="table table-hover table-stripped">
                <thead>
                    <th>Title</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Date</th>
                    <th>Driver/Vehicle</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($schedule as $item)
                    <tr>
                        <td>{{$item->title}}</td>
                        <td>{{$item->location_from}}</td>
                        <td>{{$item->location_to}}</td>
                        <td>{{$item->date_picks}}</td>
                        <td>{{$item->driver_name .' / '. $item->vehicle_name}}</td>
                        <td>
                            <a href="{{url()->current() . '/'. $item->id}}" class="btn btn-sm btn-info"><i
                                    class="fa fa-info"></i></a>
                            <button class="btn btn-sm btn-danger" onclick="deleteData({{$item->id}});"><i
                                    class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $schedule->links() }}
        </div>

    </div>
</div>
@endsection
@section('js')
<script>
    function deleteData(id) {
        Swal.fire({
            title: 'Do you want to delete?',
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
                url:"{{route('schedule_delete')}}",
                method:'POST',
                data:{id:id},
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

    function exportxlsx(){
        let monthyear = $('#month').val();
        let splitter = monthyear.split('-');
        let month = splitter[1];
        let year = splitter[0];
        
        window.open(`{{route('exportdata')}}?month=${month}&year=${year}`,'_blank');
    }
</script>
@endsection