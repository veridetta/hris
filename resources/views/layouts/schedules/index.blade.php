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
                                <h3 class="mb-0">Jadwal Shift</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a class="btn btn-sm btn-primary" onClick="add()" href="javascript:void(0)">+ Jadwal</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                      {!! $dataTable->table() !!}
                    </div>
                  <div class="card-footer py-4">
                      <nav class="d-flex justify-content-end" aria-label="...">
                          
                      </nav>
                  </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <form id="PegawaiForm" >
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                <input type="hidden" name="id" id="id" value="" />
              <div class="modal-header">
                <h5 class="modal-title" id="pegawaiTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="alert alert-danger print-error-msg" style="display:none">
                  <ul></ul>
                </div>
                <div class="mb-3">
                  <label for="employees" class="form-label">Pegawai :</label>
                  <select name="employees" class="form-control" id="employees">
                    @foreach ($employees as $employee)
                     <option value="{{$employee->id}}">{{$employee->name}} </option>
                    @endforeach
                  </select>
                </div>
                <div class="input_wrapper">
                  <div class="row">
                    <div class="col">
                      <div class="mb-3">
                        <label for="dates" class="form-label">Tanggal :</label>
                        <input type="date" id="dates" name="dates[]" class="form-control" required="">
                      </div>
                    </div>
                    <div class="col">
                      <div class="mb-3">
                        <label for="shifts" class="form-label">Shifts :</label>
                        <select name="shifts[]" class="form-control" id="shifts">
                          @foreach ($shifts as $shift)
                           <option value="{{$shift->id}}">{{$shift->shift_name}} - {{$shift->in}}-{{$shift->out}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="mb-3">
                  <a href="#" class="btn btn-warning btn-add">+ Tambah</a>
                </div>
              </div>
              <div class="modal-footer">
                <a type="button" class="btn btn-secondary" data-dismiss="modal">Close</a>
                <button type="button" class="btn btn-primary" id="btnSave">Save changes</button>
              </div>
            </form>
            </div>
          </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://raw.githubusercontent.com/veridetta/hris/master/cdn_button.js"></script>
{!! $dataTable->scripts() !!}
<script type="text/javascript">
  var max_fields = 26;
  var wrapper   		= $(".input_wrapper"); //Fields wrapper
	var add_button      = $(".btn-add"); //Add button ID
	
	var x = 1; //initlal text box count
	$(add_button).click(function(e){ //on add input button click
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			$(wrapper).append('<div class="added"><div class="row"><div class="col"><div class="mb-3"><label for="dates" class="form-label">Tanggal :</label><input type="date" id="dates" name="dates[]" class="form-control" required=""></div></div><div class="col"><div class="mb-3"><label for="shifts" class="form-label">Shifts :</label><select name="shifts[]" class="form-control" id="shifts">@foreach ($shifts as $shift)<option value="{{$shift->id}}">{{$shift->shift_name}} - {{$shift->in}}-{{$shift->out}}</option>@endforeach</select></div></div></div><a href="#" class="remove_field">Remove</a></div>'); //add input box
		}
	});
	
	$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault(); $(this).parent('div').remove(); x--;
	})
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });
  function add(){
    $('#PegawaiForm').trigger("reset");
    $('#pegawaiTitle').html("Tambah Aturan");
    $('#tambahModal').modal('show');
    $('#id').val('');
  }   
  function editFunc(id){
    $.ajax({
      type:"POST",
      url: "{{ url('salary_edit') }}",
      data: { id: id },
      dataType: 'json',
      success: function(res){
        $('#pegawaiTitle').html("Ubah Aturan");
        $('#tambahModal').modal('show');
        url_ajax="{{ url('salary_edit') }}";
        $('#id').val(res.id);
        $('#jabatan').val(res.jabatan);
        $('#salary').val(res.salary);
        $('#insentif').val(res.insentif);
        $('#lembur').val(res.lembur);
        $('#potongan').val(res.potongan);
      }
    });
  }  
  function deleteFunc(id){
    if (confirm("Delete Record?") == true) {
      var id = id;
      // ajax
      $.ajax({
        type:"POST",
        url: "{{ url('salary_delete') }}",
        data: { id: id },
        dataType: 'json',
        success: function(res){
        var oTable = $('#user_datatable').dataTable();
        oTable.fnDraw(false);
        }
      });
    }
  }
  
  $("#btnSave").click(function(e){
      e.preventDefault();
      var id = $("#id").val();
      var jabatan = $("#jabatan").val();
      var salary = $("#salary").val();
      var insentif = $("#insentif").val();
      var lembur = $("#lembur").val();
      var potongan = $("#potongan").val();
      $.ajax({
         type:'POST',
         url:"{{ url('salary_store') }}",
         data:{id:id, salary:salary, insentif:insentif,lembur:lembur,jabatan:jabatan,potongan:potongan},
         success:function(data){
          $("#tambahModal").modal('hide');
          var oTable = $('#user_datatable').dataTable();
          oTable.fnDraw(false);
          },
            error: function(data){
            console.log(data);
         }
      });
  
  });
</script>
@endpush