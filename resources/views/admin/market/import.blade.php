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

	                        <h4 class="header-title mb-0 font-weight-bold" style="font-size:24px;">

	                          Import Store

	                        </h4>

	                     </div>

	                   

	                  </div>

	                  <form action="{{ route('admin.market.import.post') }}" method="POST" enctype="multipart/form-data">

	                  	@csrf

		                 <div class="row d-flex align-items-end">

		                 <div class="col-md-7 mt-3 ">

		                     <div class="form-group">

		                        <label for="exampleFormControlInput1">Store</label>

		                        <div class="upload-box mt-15" id="uploadBox">

		                           <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Excel</span>

		                           <input type="file" id="fileInput" name="file" accept=".csv, .xls, .xlsx">

		                        </div>
		                        <div class="text text-danger text-center">@error('file')<p>{{$message}}</p>@enderror</div>

		                     </div>

		                  </div>

		                  	<div class="col-md-5 mb-3">

		                  	<a href="{{ asset('admin/sample/store-sample.xlsx') }}" class="import-btn" download>Download Sample</a>



		                  </div>

		                  <div class="col-md-12 mt-40">

		                  <button type="submit" class="searc-btn">Import</button>

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

	<!-- ============================================================== -->

	<!-- End Page content -->

	<!-- ============================================================== -->

	</div>

@endsection