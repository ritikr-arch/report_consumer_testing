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

					        <h5>Title :</h5>

					        <h6> {{@$data->title}}</h6>

					    </div>

					</div>
               <div class="col-md-6">

                   <div class="view-txt">

                       <h5>Status :</h5>

                       <h6> {{($data->status == '1')?'Active':'Deactive'}}</h6>

                   </div>    

               </div>

               <div class="col-md-12">

                   <div class="view-txt">

                       <h5>Description :</h5>

                       <h6> {{strip_tags(@$data->content)}}</h6>

                   </div>

               </div>
               <div class="col-md-6">
                  <?php
                  $imageURL = '';
                  if($data->image){
                     $imageURL = 'admin/images/quck_link/'.$data->image;
                  }else{
                     $imageURL = 'admin/images/news/news.jpg';
                  }
                  ?>
                  <img src="{{asset($imageURL)}}" style="height: 120px; width:180px;" alt="">
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