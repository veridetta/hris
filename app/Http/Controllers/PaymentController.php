<?php

namespace App\Http\Controllers;

use App\DataTables\PaymentsDataTable;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Jabatan;
use App\Models\Salary;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        $employee=Employee::where('id','=',$id)->first();
        $server=request()->server('HTTP_HOST').'/get_pdf/'.$month.'/'.$id.'/'.$year;
        $name='Gaji-'.$employee->name.'-'.$month.'-'.$year.'.pdf';
        $pdf = SnappyPdf::loadView('payment_download', [
            'title' => '',
            'description' => '',
            'footer' => ''
        ]);
        //return SnappyPdf::loadFile($server)->inline($name);
        return $pdf->download($name);
    }
    public function view(Request $request)
    {
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        return view('payment_download');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $employee=Employee::select('employees.id','employees.name','jabatans.jabatan','salaries.salary','salaries.insentif')->join('jabatans', '.jabatans.id', '=', 'employees.jabatans_id')->join('salaries','salaries.jabatan_id','=','jabatans.id')->get();
        $bulan=$request->month;
        $tahun=$request->year;
        return view('layouts.payments.index',['employees'=>$employee,'bulan'=>$bulan,'tahun'=>$tahun]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePaymentRequest  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
    public function report($month,$year){

    }
    public function detail(PaymentsDataTable $dataTable, Request $request){
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        return $dataTable->with('id',$id)->with('month',$month)->with('year',$year)->render('layouts.payments.detail');
    }
    public function generate_payments(Request $request){
        $id=$request->id;
        $month=$request->month;
        $year=$request->year;
        $employee=Employee::where('id','=',$id)->first();
        $total_lembur=0;
        $total_telat=0;
        $total_absen=0;
        $total_masuk=0;
        $attendance = Attendance::whereMonth('created_at',$month)->whereYear('created_at',$year)->where('employees_id','=',$id)->get();
            foreach($attendance as $attendances){
                switch ($attendances->status){
                    case "Belum Masuk":
                        $total_absen++;
                        break;
                    case "Masuk":
                        $total_masuk++;
                        break;
                    case "Pulang":
                        $total_masuk++;
                        break;
                    case "Terlambat":
                        $total_masuk++;
                        $total_telat++;
                        break;
                    case "Lembur":
                        $total_masuk++;
                        $total_lembur=+$attendances->lembur;
                        break;
                }
            }
            $jabatan=Salary::where('jabatan_id','=',$employee->jabatans_id)->first();
            $gaji=$jabatan->salary;
            $fee_lembur=$total_lembur*$jabatan->lembur;
            $fee_telat=$total_telat*$jabatan->potongan;
            $total_gaji=$gaji+$fee_lembur+$jabatan->insentif-$fee_telat;
            //Create or update database
            $employee=Payment::updateOrCreate([
                'employees_id' => $id,
                'month' => $month,
                'year' => $year
               ],[
                'employees_id' => $id,
                'month' => $month,
                'year' => $year,
                'lembur' => $fee_lembur,
                'telat' => $total_telat,
                'tidak_masuk' => $total_absen,
                'potongan' => $fee_telat,
                'payment' => $total_gaji,
            ]);
            return response()->json(['status'=>'success','message'=>'Berhasil melakukan validasi','title'=>'Berhasil']);
            //lalu return ke gaji
    }
    
}
