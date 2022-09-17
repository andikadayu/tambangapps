<?php

namespace App\Http\Controllers;

use App\Models\schedule_vehicle;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class ExportController extends Controller
{
    public function exportdata(Request $request)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $nm_file = "Schedule-" . $month . "-" . $year . '.xlsx';


        $this->create_excel($month, $year, $nm_file);

        $file = public_path('export/' . $nm_file);

        return response()->download($file)->deleteFileAfterSend();
    }

    public function create_excel($month, $year, $nm_file)
    {
        $exceldate = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE'];


        // create excel
        $reader = new Xlsx();
        $wb = $reader->load(public_path('templete.xlsx'));
        $sheet = $wb->getActiveSheet();




        for ($tgl = 1; $tgl < 32; $tgl++) {
            $i = 5;
            $schedule = schedule_vehicle::select('schedule_vehicles.*', 'vehicles.name as vehicle_name', 'drivers.name as driver_name')
                ->join('vehicles', 'vehicles.id', '=', 'schedule_vehicles.vehicleid')
                ->join('drivers', 'drivers.id', '=', 'schedule_vehicles.driverid')
                ->whereMonth('schedule_vehicles.date_picks', $month)
                ->whereDay('schedule_vehicles.date_picks', $tgl)
                ->whereYear('schedule_vehicles.date_picks', $year)
                ->get();
            foreach ($schedule as $key => $value) {
                $sheet->setCellValue("" . $exceldate[$tgl - 1] . "$i", "" . $value->driver_name . "\n(" . $value->vehicle_name . ")" . "\n[" . $value->location_from . ' - ' . $value->location_to . ']');

                $i++;
            }
        }

        $writer = new WriterXlsx($wb);
        $path = public_path("export/$nm_file");
        $writer->save($path);
    }
}
