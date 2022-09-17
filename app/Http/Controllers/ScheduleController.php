<?php

namespace App\Http\Controllers;

use App\Models\driver;
use App\Models\history_log;
use App\Models\schedule_approval;
use App\Models\schedule_vehicle;
use App\Models\User;
use App\Models\vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $data['schedule'] = schedule_vehicle::select('schedule_vehicles.*', 'vehicles.name as vehicle_name', 'drivers.name as driver_name')
            ->join('vehicles', 'vehicles.id', '=', 'schedule_vehicles.vehicleid')
            ->join('drivers', 'drivers.id', '=', 'schedule_vehicles.driverid')
            ->paginate(10);

        return view('pages.schedule.schedule', $data);
    }

    public function add(Request $request)
    {
        $data['driver'] = driver::all();
        $data['vehicle'] = vehicle::all();
        $data['approver'] = User::where('roles', 'approver')->get();

        return view('pages.schedule.add', $data);
    }

    public function add_process(Request $request)
    {
        $resp = [];

        $title = $request->input('title');
        $description = $request->input('description');
        $location_from = $request->input('location_from');
        $location_to = $request->input('location_to');
        $distance = $request->input('distance');
        $vehicleid = $request->input('vehicleid');
        $driverid = $request->input('driverid');
        $date_picks = $request->input('date_picks');

        $approvalid = $request->input('approvalid', []);

        if (count($approvalid) >= 2) {
            // First Insert
            $schedules = new schedule_vehicle();
            $schedules->title = $title;
            $schedules->description = $description;
            $schedules->location_from = $location_from;
            $schedules->location_to = $location_to;
            $schedules->distance = $distance;
            $schedules->vehicleid = $vehicleid;
            $schedules->driverid = $driverid;
            $schedules->date_picks = $date_picks;

            if ($schedules->save()) {
                $scheduleid = $schedules->id;

                foreach ($approvalid as $key => $value) {
                    $apr = new schedule_approval();
                    $apr->scheduleid = $scheduleid;
                    $apr->approvalid = $value;
                    $apr->position = $key;
                    $apr->status = 0;

                    $apr->save();
                }

                $resp['status'] = true;
                $resp['msg'] = 'Success Insert Data';

                $this->add_history('Add Schedule');
            } else {
                $resp['status'] = false;
                $resp['msg'] = 'Failed Insert Data';
            }
        } else {
            $resp['status'] = false;
            $resp['msg'] = 'Approval Must 2 or more';
        }


        return response($resp);
    }

    public function schedule_delete(Request $request)
    {
        $id = $request->input('id');
        $resp = [];
        $del1 = schedule_vehicle::where('id', $id)->delete();
        $del2 = schedule_approval::where('scheduleid', $id)->delete();
        if ($del1 && $del2) {
            $resp['status'] = true;
            $resp['msg'] = 'Success Delete Data';

            $this->add_history('Delete Schedule');
        } else {
            $resp['status'] = false;
            $resp['msg'] = 'Failed Delete Data';
        }

        return response($resp);
    }

    public function detail(Request $request, $id)
    {
        $data['schedule'] = schedule_vehicle::select('schedule_vehicles.*', 'vehicles.name as vehicle_name', 'drivers.name as driver_name')
            ->join('vehicles', 'vehicles.id', '=', 'schedule_vehicles.vehicleid')
            ->join('drivers', 'drivers.id', '=', 'schedule_vehicles.driverid')
            ->where('schedule_vehicles.id', $id)
            ->first();

        $data['approval'] = schedule_approval::select('schedule_approvals.*', 'users.name as approval_name')
            ->join('users', 'users.id', '=', 'schedule_approvals.approvalid')
            ->where('schedule_approvals.scheduleid', $id)
            ->get();

        $data['driver'] = driver::all();
        $data['vehicle'] = vehicle::all();
        $data['scheduleid'] = $id;

        return view('pages.schedule.detail', $data);
    }

    public function update(Request $request)
    {
        $resp = [];

        $title = $request->input('title');
        $description = $request->input('description');
        $location_from = $request->input('location_from');
        $location_to = $request->input('location_to');
        $distance = $request->input('distance');
        $vehicleid = $request->input('vehicleid');
        $driverid = $request->input('driverid');
        $date_picks = $request->input('date_picks');
        $id = $request->input('scheduleid');

        $schedules = schedule_vehicle::find($id);
        $schedules->title = $title;
        $schedules->description = $description;
        $schedules->location_from = $location_from;
        $schedules->location_to = $location_to;
        $schedules->distance = $distance;
        $schedules->vehicleid = $vehicleid;
        $schedules->driverid = $driverid;
        $schedules->date_picks = $date_picks;
        if ($schedules->save()) {
            $resp['status'] = true;
            $resp['msg'] = 'Success Update Data';
            $this->add_history('Update Schedule');
        } else {
            $resp['status'] = false;
            $resp['msg'] = 'Failed Update Data';
        }
        return response($resp);
    }

    public function confirm_reject(Request $request)
    {
        $resp = [];
        $id = $request->input('id');
        $status = $request->input('status');
        $purpose = "";
        if ($status == 1) {
            $purpose = "Confirm";
        } else {
            $purpose = "Reject";
        }

        $approval = schedule_approval::find($id);
        $approval->status = $status;
        if ($approval->save()) {
            $resp['status'] = true;
            $resp['msg'] = "Success $purpose Approval";
            $this->add_history("$purpose Schedule");
        } else {
            $resp['status'] = false;
            $resp['msg'] = "Faield $purpose Approval";
        }

        return response($resp);
    }


    public function add_history($msg = null)
    {
        $history = new history_log();
        $history->userid = Session::get('userid');
        $history->activity = $msg;
        $history->save();
    }
}
