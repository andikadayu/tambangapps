<?php

namespace App\Http\Controllers;

use App\Models\history_log;
use App\Models\schedule_vehicle;
use App\Models\vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data['history'] = history_log::select('users.name', 'users.roles', 'history_logs.activity')
            ->join('users', 'users.id', '=', 'history_logs.userid')
            ->get();
        return view('pages.dashboard.dashboard', $data);
    }

    public function getdata(Request $request)
    {
        $resp = [];
        $listvehicle = [];
        $vehicle = vehicle::all();
        $listData = [];

        foreach ($vehicle as $key => $value) {
            for ($i = 1; $i < 13; $i++) {
                $fakeColor = "rgb(" . fake()->rgbColor . ")";
                $listvehicle[$value->name]['label'] = $value->name;
                $listvehicle[$value->name]['data'][] = schedule_vehicle::whereMonth('date_picks', '=', $i)->whereYear('date_picks', '=', date('Y'))->where('vehicleid', $value->id)->count();
                $listvehicle[$value->name]['backgroundColor'] = $fakeColor;
                $listvehicle[$value->name]['borderColor'] = $fakeColor;
            }
        }

        foreach ($listvehicle as $key => $value) {
            array_push($listData, $value);
        }

        $resp['data'] = $listData;

        return response($resp);
    }
}
