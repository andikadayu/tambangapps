@extends('master')
@section('title','Dashboard')
@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Vehicle Stats</div>
            </div>
            <div class="card-body">
                <canvas id="myCharts"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-tite">History Log</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatables">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Roles</th>
                                <th>Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $item)
                            <tr>
                                <td>{{$item->name}}</td>
                                <td>{{$item->roles}}</td>
                                <td>{{$item->activity}}</td>
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
@section('js')
<script src="{{asset('assets\plugins\datatables\jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $('.datatables').DataTable({
            "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
        });

        $(document).ready(function () {
            $.ajax({
                url:"{{route('get_data_dashboard')}}",
                method:"GET",
                dataType:'json',
                success:function(response){
                    stats(response.data);
                }
            })
        });

        function stats(dataset) {

            const label= ['Jan','Feb','Mar','Apr','May','Jun','Jul','Agu','Sep','Oct','Nov','Dec'];
            const data = {
                labels:label,
                datasets:dataset
                
            }

            const config = {
                type: 'line',
                data: data,
                options: {}
            };

            initialize(config);
            
        }
        
        function initialize(config) {
            const myCharts = new Chart(
                document.getElementById('myCharts'),
                config
                
                );
                myCharts.update('active');

        }
</script>
<script>

</script>
@endsection