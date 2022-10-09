<!DOCTYPE html>
<html>
<body style="padding-left:15px;" onLoad="javascript:print()">
    <?php
    use App\Models\Attendance;
    use App\Models\Payment;
    use App\Models\Schedule;
    use App\Models\Setting;
    use App\Models\Employee;
    use Carbon\Carbon;
    $payment=Payment::where('employees_id','=',request()->id)->where('month','=',request()->month)->where('year','=',request()->year)->first();
    $setting=Setting::first();
    $employee=Employee::select('employees.name','jabatans.jabatan','salaries.salary','salaries.insentif')->where('employees.id','=',request()->id)->join('jabatans','jabatans.id','=','employees.jabatans_id')->join('salaries','salaries.jabatan_id','=','jabatans.id')->first();
    ?>
    <table width="100%">
        <tr>
            <td style="width:100px;"><img src="{{ asset('images/'.$setting->logo) }}" style="max-width:100px;"/></td>
            <td style="padding-left:10px;"> <p style="text-transform:uppercase"><strong><span style="font-size:18px">{{$setting->company}}</span></strong></p>
                <p style="">{{$setting->address}}</p></td>
        </tr>
    </table>
<hr />
<p style="text-align:center"><strong><span style="font-size:16px">Slip Gaji Karyawan</span></strong></p>

<table border="0" cellpadding="1" cellspacing="1" style="width:50%">
	<tbody>
		<tr>
			<td style="width:151px">Nama</td>
			<td style="width:336px">: {{$employee->name}}</td>
		</tr>
		<tr>
			<td style="width:151px">Jabatan</td>
			<td style="width:336px">: {{$employee->jabatan}}</td>
		</tr>
		<tr>
			<td style="width:151px">Periode</td>
			<td style="width:336px">: {{request()->month.'-'.request()->year}}</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:125px"><strong>PENGHASILAN</strong></td>
			<td style="width:439px">&nbsp;</td>
			<td style="width:200px"><strong>POTONGAN</strong></td>
			<td style="width:254px">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:125px">Gaji Pokok</td>
			<td style="width:439px">@currency($employee->salary)</td>
			<td style="width:200px">Terlambat / Ketidakhadiran</td>
			<td style="width:254px">@currency($payment->potongan)</td>
		</tr>
		<tr>
			<td style="width:125px">Insentif</td>
			<td style="width:439px">@currency($employee->insentif)</td>
			<td style="width:200px">&nbsp;</td>
			<td style="width:254px">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:125px">Lembur</td>
			<td style="width:439px">@currency($payment->lembur)</td>
			<td style="width:200px">&nbsp;</td>
			<td style="width:254px">&nbsp;</td>
		</tr>
		<tr>
			<td style="width:125px"><strong>TOTAL A</strong></td>
			<td style="width:439px">@currency($payment->lembur+$employee->insentif+$employee->salary)</td>
			<td style="width:200px"><strong>TOTAL B</strong></td>
			<td style="width:254px">@currency($payment->potongan)</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td><strong>PENERIMAAN BERSIH = @currency($payment->payment)</strong></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table border="0" cellpadding="1" cellspacing="1" style="width:100%">
	<tbody>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">Mengetahui,</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px;"><img src="{{ asset('images/'.$setting->ttd) }}" style="max-width:200px;min-height:60px"/>
			</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">{{$setting->leader}}</td>
		</tr>
		<tr>
			<td style="width:740px">&nbsp;</td>
			<td style="width:412px">{{'Manajer '.$setting->company}}</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

    <br>
    <?php
    
    $data = Schedule::select('attendances.id','attendances.at_in','attendances.at_out','attendances.lembur','shifts.in','shifts.out','attendances.status','schedules.dates')->join('attendances','attendances.schedules_id','=','schedules.id')->join('shifts','shifts.id','=','schedules.shifts_id')->join('employees','employees.id','=','attendances.employees_id')->where('employees.id',request()->id)->whereMonth('schedules.dates',request()->month)->whereYear('schedules.dates',request()->year)->get();
    ?>
    <p style="text-align:center"><strong>LAMPIRAN KEHADIRAN KARYAWAN</strong></p>

    <p style="text-align:center"><strong>PERIODE {{request()->month.'-'.request()->year}}</strong></p>
    
    <table border="1" cellpadding="1" cellspacing="1" style="width:100%">
        <tbody>
            <tr>
                <td>No</td>
                <td>Tanggal</td>
                <td>In</td>
                <td>At In</td>
                <td>Out</td>
                <td>At Out</td>
                <td>Lembur</td>
                <td>Status</td>
            </tr>
            <?php $no=0;?>
            @foreach ($data as $datas)
            <?php $no++;?>
            <tr>
                <td>{{$no}}</td>
                <td>{{$datas->dates}}</td>
                <td>{{$datas->in}}</td>
                <td>{{$datas->at_in}}</td>
                <td>{{$datas->out}}</td>
                <td>{{$datas->at_out}}</td>
                <td>{{$datas->lembur}}</td>
                <td>{{$datas->status}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <br>
    <p style="text-align: center;">Generated at {{Carbon::now()}}</p>
</body>
</html>
