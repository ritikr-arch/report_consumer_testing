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

                          View

                        </h4>

                     </div>

                  </div>

                 <div class="row mt-4">

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>Name :</h5>

					        <h6> {{@$data->name}}</h6>

					    </div>

					</div>

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>Category Name :</h5>

					        <h6> {{(@$data->category)?ucfirst($data->category->name):''}}</h6>

					    </div>

					</div>

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>Brand Name :</h5>

					        <h6> {{(@$data->brand)?ucfirst($data->brand->name):''}}</h6>

					    </div>

					</div>

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>UOM Name :</h5>

					        <h6> {{(@$data->uom)?ucfirst($data->uom->name):''}}</h6>

					    </div>

					</div>

					<!-- <div class="col-md-6">

					    <div class="view-txt">

					        <h5>Unit Value :</h5>

					        <h6> {{ucfirst($data->unit_value)}}</h6>

					    </div>

					</div> -->

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>Status :</h5>

					        <h6> {{($data->status == '1')?'Active':'Deactive'}}</h6>

					    </div>    

					</div>

					<div class="col-md-6">

					    <div class="view-txt">

					        <h5>Image :</h5>

					        <img src="{{ asset('admin/images/commodity/'.$data->image) }}" width="100px" height="100px" alt="">

					    </div>    

					</div>

                </div>

               </div>

            </div>

         </div>

      </div>

   </div>

</div>

</div>

</div>

@endsection