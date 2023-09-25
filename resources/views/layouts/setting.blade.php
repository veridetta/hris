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
                                <h3 class="mb-0">Pengaturan</h3>
                            </div>
                        </div>
                    </div>
                   
                  <div class="card-footer py-4">
                      <nav class="col-12" aria-label="...">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        <form action="{{ route('setting_store') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <input type="hidden" name="id" value="{{$company->id}}">
                          <div class="row">
                            <div class="col-6">
                              <div class="mb-3 ">
                                <label for="company" class="form-label">Nama Perusahaan:</label>
                                <input type="text" id="company" name="company" class="form-control" placeholder="Nama Perusahaan" value="{{$company->company}}" required="">
                              </div>
                              <div class="mb-3">
                                <label for="address" class="form-label">Alamat:</label>
                                <textarea  id="address" name="address" class="form-control" placeholder="Alamat" required="">{{$company->address}}</textarea>
                              </div>
                              <div class="mb-3 ">
                                <label for="leader" class="form-label">Pimpinan:</label>
                                <input type="text" id="leader" name="leader" class="form-control" placeholder="Nama Pimpinan" value="{{$company->leader}}" required="">
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="mb-3 ">
                                <div class="col-12">
                                  <img src="//files.segarsehatgorontalo.com/public/images/{{$company->logo}}" alt="" title="" width="80" height="80"/>
                                </div>
                                <label for="logo" class="form-label">Logo Perusahaan:</label>
                                <input type="file" id="logo" name="logo" class="form-control" placeholder="Logo" required="">
                              </div>
                              <div class="mb-3 ">
                                <div class="col-12">
                                  <img src="//files.segarsehatgorontalo.com/public/images/{{ $company->ttd}}" alt="" title="" width="80" height="80"/>
                                </div>
                                <label for="ttd" class="form-label">Scan TTD:</label>
                                <input type="file" id="ttd" name="ttd" class="form-control" placeholder="TTD" required="">
                              </div>
                            </div>
                          </div>
                          <input type="submit" class="btn btn-primary btn-block" id="btnSave" value="Save changes">
                        </form>
                      </nav>
                  </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')

@endpush