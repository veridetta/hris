@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    <div class="header bg-gradient-primary py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mt-2 mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-6">
                        <h2 class="display-1"><i class="fas fa-clock"></i></h2>
                        <h1 class="text-white">{{ __('Welcome to '.$company->company) }}</h1>
                        <h1 class="display-3"><span class="badge badge-primary font-weight-bold mb-0 text-uppercase mb-0" id="date">Time</span></h1>
                        <div class="row">
                            <div class="col my-auto">
                                <h1 class="display-1"><span class="badge badge-secondary font-weight-bold mb-0 text-uppercase mb-0" id="time">Time</span></h1>
                            </div>
                        </div>
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <form id="att">
                                    <div class="">
                                        <div class="mb-3">
                                            <input type="password" autofocus id="rfid" name="rfid" class="form-control" placeholder="Scan" required="">
                                        </div>
                                        <a type="button" class="btn btn-primary" id="btnSave" href="#">Absen</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--10 pb-5"></div>
@endsection
@push('js')
    <script type="text/javascript">
        setInterval(function(){
        var currentTime = new Date();
        var hours = currentTime.getHours();
        var minutes = currentTime.getMinutes();
        var seconds = currentTime.getSeconds();

        // Add leading zeros
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;
        hours = (hours < 10 ? "0" : "") + hours;

        // Compose the string for display
        var currentTimeString = hours + ":" + minutes + ":" + seconds;
        $("#time").html(currentTimeString);
        $('#date').html('{{$now}} ')
        },1000);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#att").submit(function(e){
            e.preventDefault();
            var rfid = $("#rfid").val();
            $.ajax({
                type:'POST',
                url:"{{ url('start_attendance') }}",
                data:{rfid:rfid},
                success:function(data){
                    Swal.fire({
                        icon: data.status,
                        title: data.title,
                        text: data.message
                    });
                },
                    error: function(data){
                    console.log(data);
                }
            });
        });
        $("#btnSave").click(function(e){
            e.preventDefault();
            var rfid = $("#rfid").val();
            $.ajax({
                type:'POST',
                url:"{{ url('start_attendance') }}",
                data:{rfid:rfid},
                success:function(data){
                    Swal.fire({
                        icon: data.status,
                        title: data.title,
                        text: data.message
                    });
                },
                    error: function(data){
                    console.log(data);
                }
            });
        });
        $(document).ready(function(){
            $("#rfid").focus();
        })
    </script>
@endpush
