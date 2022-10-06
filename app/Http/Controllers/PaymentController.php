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

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function generate_payments($month,$id,$year){
        $employee=Employee::all();
        foreach($employee as $employees){
            $attendance = Attendance::whereMonth('created_at',$month)->whereYear('created_at',$year)->where('employees_id','=',$employees->id);
            foreach($attendance as $attendances){
                $total_lembur=0;
                $total_telat=0;
                $total_absen=0;
                $total_masuk=0;
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
                        $total_lembur+=$attendances->lembur;
                        break;
                }
            }
            $jabatan=Salary::where('jabatan_id','=',$employees->jabatans_id)->first();
            $gaji=$jabatan->salary;
            $fee_lembur=$total_lembur*$jabatan->lembur;
            $fee_telat=$total_telat*$jabatan->telat;
            $total_gaji=$gaji+$fee_lembur-$fee_telat;
            //Create or update database

            //lalu return ke gaji
        }
    }
}
