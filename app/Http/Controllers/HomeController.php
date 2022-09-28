<?php

namespace App\Http\Controllers;

use App\DataTables\AttendanceTodayDataTable;
use App\Models\Setting;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [ 'start'] ] );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(AttendanceTodayDataTable $dataTable)
    {
        $companies = Setting::first();
        $now = Carbon::now()->isoFormat ('dddd, D MMM Y');
        return $dataTable->render('dashboard',['now'=>$now,'company'=>$companies]);
    }
}
