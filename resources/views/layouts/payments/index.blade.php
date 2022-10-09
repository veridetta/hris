@extends('layouts.app')

@section('content')
    @include('layouts.headers.cardsno')
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-12 mb-xl-0">
                <div class="card bg-gradient-secondary shadow">
                    <div class="card-header bg-transparent">
                      <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Table Pembayaran Gaji</h3>
                            </div>
                            <div class="col-4 text-right">
                              <div class="d-flex flex-row">
                                <select name="month" id="month" class="form-control mr-2" value="{{$bulan}}">
                                  <option disabled>Bulan</option>
                                  @for ($o=1;$o<13;$o++)
                                    <option @if ($o==$bulan)
                                      selected
                                    @endif>{{str_pad($o, 2, '0', STR_PAD_LEFT) }}</option>
                                  @endfor
                                </select>
                                <select name="year" id="year" class="form-control mr-2" value="{{$tahun}}">
                                  <option disabled>Tahun</option>
                                  @for ($i=2022;$i<2035;$i++)
                                    <option @if ($i==$tahun)
                                    selected
                                  @endif>{{$i}}</option>
                                  @endfor
                                </select>
                                <a class="btn btn-sm btn-primary" onClick="ubah()" href="javascript:void(0)">Ubah</a>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                      <table class="table table-bordered table-striped table-responsive">
                        <thead class="table-primary">
                          <tr>
                            <td class="font-weight-bold">No</td>
                            <td class="font-weight-bold">Nama</td>
                            <td class="font-weight-bold">Jabatan</td>
                            <td class="font-weight-bold">Pokok</td>
                            <td class="font-weight-bold">Insentif</td>
                            <td class="font-weight-bold">Lembur</td>
                            <td class="font-weight-bold">Potongan</td>
                            <td class="font-weight-bold">Total</td>
                            <td class="font-weight-bold">Aksi</td>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            use App\Models\Payment;
                            $no=0;?>
                          @foreach ($employees as $employee)
                          <?php 
                          $payment=Payment::where('employees_id','=',$employee->id)->where('month','=',request()->month)->where('year','=',request()->year)->first();
                          $no++
                          ;?>
                          <tr>
                            <td>{{$no}}</td>
                            <td>{{$employee->name}}</td>
                            <td>{{$employee->jabatan}}</td>
                            <td>@currency($employee->salary)</td>
                            <td>@currency($employee->insentif)</td>
                            @if(empty($payment))
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            @else
                            <td>@currency($payment->lembur)</td>
                            <td>@currency($payment->potongan)</td>
                            <td>@currency($payment->payment)</td>
                            @endif
                            
                            <td><a onClick="validasi({{$employee->id}})" href="javascript:void(0)" class="btn btn-sm btn-success">Validasi</a></td>
                          </tr>
                          @endforeach
                      </table>
                    </div>
                  <div class="card-footer py-4">
                      <nav class="d-flex justify-content-end" aria-label="...">
                          
                      </nav>
                  </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection
@push('js')
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  function ubah(){
    window.location.href = "/report/"+$('#month').val()+"/"+$('#year').val();
  };
  function validasi(id){
    window.location.href = "../details/"+$('#month').val()+"/"+id+"/"+$('#year').val();
  };
</script>
@endpush