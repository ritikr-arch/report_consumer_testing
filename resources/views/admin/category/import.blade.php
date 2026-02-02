@extends('admin.layouts.app') 
@section('title', @$title) 
@section('content') 
<div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-12">
                <h4 class="header-title mb-0 font-weight-bold" style="font-size:24px;"> Import Category </h4>
              </div>
            </div>
            <form action="{{ route('admin.category.import.post') }}" method="POST" enctype="multipart/form-data"> @csrf <div class="row mt-3">
                <div class="col-md-5">
                  <div class="form-group">
                    <div class="upload-box mt-0">
                      <span id="fileName">
                        <i class="fa-solid fa-upload"></i>&nbsp;Upload Excel </span>
                      	<input type="file" name="file" accept=".csv, .xls, .xlsx">
                    </div>
                    <div class="text text-danger text-center">@error('file') <p>{{$message}}</p>@enderror </div>
                     <p style="font-size: 11px; font-weight: 700;">Recommended Format: .csv, .xls, .xlsx, Max size: 2MB</p>
                  </div>
                  <div class="col-md-12 mt-3 text-center">
                    <button type="submit" class="searc-btn">Import</button>
                  </div>
                </div>
                <div class="col-md-2 mt-3">
                  <a href="{{ asset('admin/sample/category-sample.xlsx') }}" class="import-btn" download>Download Sample</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- end row-->
  </div>
  <!-- container -->
</div>
<!-- content -->
</div>
</div> 
@endsection