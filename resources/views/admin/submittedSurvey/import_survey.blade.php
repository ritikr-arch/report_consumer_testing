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
                <h4 class="header-title mb-0 font-weight-bold" style="font-size:24px;"> Import Survey </h4>
              </div>
            </div>
            <form action="{{ route('admin.import.surveys') }}" method="POST" enctype="multipart/form-data"> @csrf <div class="row mt-3">
                <div class="col-md-7">
                  <div class="form-group">
                    <div class="upload-box mt-0">
                      <span id="fileName">
                        <i class="fa-solid fa-upload"></i>&nbsp;Upload Excel </span>
                      	<input type="file" name="file" id="file" accept=".csv, .xls, .xlsx" required>
                    </div>
                    <div class="text text-danger text-center">@error('file') <p>{{$message}}</p>@enderror </div>
                     <p style="font-size: 11px; font-weight: 700;">Recommended Format: .csv, .xls, .xlsx, Max size: 2MB</p>
                  </div>
                  <div class="col-md-12 mt-3 text-center">
                    <button type="submit" class="searc-btn">Import</button>
                    {{-- <a class="searc-btn" href="{{ route('admin.submitted.survey.list') }}" title="">Import</a> --}} 
                  </div>
                </div>

                  <div class="col-md-2 mt-3">
                    <a href="{{ asset('admin/sample/import-survey.xlsx') }}" class="import-btn" download>Download Sample</a>
                  </div>
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

@push('scripts')
<script>
  const fileInput = document.getElementById("file");
  const fileNameDisplay = document.getElementById("fileName");

  fileInput.addEventListener("change", (event) =>{
    const file = event.target.files[0];
    if(file){
        fileNameDisplay.textContent = file.name; 
    }else{
        fileNameDisplay.textContent = "Upload Image"; 
    }

  });
</script>
@endpush