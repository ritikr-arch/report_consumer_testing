@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

 <style>

	.select2-container {

  min-width: 400px;

}

th{
   font-size:16px !important;
}

.select2-results__option {

  padding-right: 20px;

  vertical-align: middle;

}

.select2-results__option:before {

  content: "";

  display: inline-block;

  position: relative;

  height: 20px;

  width: 20px;

  border: 2px solid #e9e9e9;

  border-radius: 4px;

  background-color: #fff;

  margin-right: 20px;

  vertical-align: middle;

}

.select2-results__option[aria-selected=true]:before {

  font-family:fontAwesome;

  content: "\f00c";

  color: #fff;

  background-color: #f77750;

  border: 0;

  display: inline-block;

  padding-left: 3px;

}

.select2-container--default .select2-results__option[aria-selected=true] {

	background-color: #fff;

}

.select2-container--default .select2-results__option--highlighted[aria-selected] {

	background-color: #eaeaeb;

	color: #272727;

}

.select2-container--default .select2-selection--multiple {

	margin-bottom: 10px;

}

.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {

	border-radius: 4px;

}

.select2-container--default.select2-container--focus .select2-selection--multiple {

	border-color: #f77750;

	border-width: 2px;

}

.select2-container--default .select2-selection--multiple {

	border-width: 2px;

}

.select2-container--open .select2-dropdown--below {

	

	border-radius: 6px;

	box-shadow: 0 0 10px rgba(0,0,0,0.5);



}

.select2-selection .select2-selection--multiple:after {

	content: 'hhghgh';

}

/* select with icons badges single*/

.select-icon .select2-selection__placeholder .badge {

	display: none;

}

.select-icon .placeholder {

/ 	display: none; /

}

.select-icon .select2-results__option:before,

.select-icon .select2-results__option[aria-selected=true]:before {

	display: none !important;

	/ content: "" !important; /

}

.select-icon  .select2-search--dropdown {

	display: none;

}

.dropdown-container-filter select {

    width: 16%;

}

.dropdown-container-filter .form-select{

       padding: 0.45rem 2.7rem 0.45rem 4px;

}

.moreButton{
  font-size: 12px;
  color: #3f87fd;
}
.modal-dialog.modal-lg.modal-dialog-centered.height-500 .modal-body {
    height: 440px;
    overflow: auto;
    text-align: justify;
}
</style>

<?php 
use App\Models\Market;
use App\Models\Category;
use App\Models\User;
?>

<div class="px-3">
   <!-- Start Content-->
   <div class="container-fluid">
      <div class="row mt-3">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <div class="row align-items-center d-flex mb-3">
                     <div class="col-xl-7">
                        <h4 class="header-title mb-0 font-weight-bold">
                           Survey Reports
                        </h4>
                     </div>

                     <div class="col-12 col-md-5 col-lg-5">
                        <div class="text-end">
                           <div>
                              <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                           </div>
                        </div>
                     </div>

                  </div>

                  <div class="row">

                     <form action="{{route('admin.report.filter')}}" method="get" class="mb-2">

                        <div id="dropdown" class="dropdown-container-filter" style="flex-wrap: nowrap;">

                           <div class="name-input">

                              <input type="text" class="form-control" name="survey_id" id="survey_id" placeholder="Survey Id" value="{{ request('survey_id') }}">

                           </div>

                           <div class="name-input">

                              <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Survey Title" value="{{ request('name') }}">

                           </div>

                           <select class="form-select" name="zone" id="filterZone" aria-label="Default select example">

                              <option value="">Select Zone</option>

                              @if(isset($zone) && count($zone)>0)

                              @foreach($zone as $zKey=>$zoneValue)

                                <option {{ request('zone') == $zoneValue->id ? 'selected' : '' }} value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option>

                              @endforeach

                              @endif

                           </select>

                           {{-- <select class="form-select" name="market" id="filterMarket" aria-label="Default select example">

                              <option value="">Select market</option>

                              @if(isset($marktes) && count($marktes)>0)

                              @foreach($marktes as $zKey=>$marketValues)

                                 <option {{ request('market') == $marketValues->id ? 'selected' : '' }} value="{{$marketValues->id}}">{{ucfirst($marketValues->name)}}</option>

                              @endforeach

                              @endif

                           </select>

                           <select class="form-select" name="category" aria-label="Default select example">

                              <option value="">Select Category</option>

                              @if(isset($category) && count($category)>0)

                              @foreach($category as $cKey=>$catValue)

                                <option {{ request('category') == $catValue->id ? 'selected' : '' }} value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option>

                              @endforeach

                              @endif

                           </select>

                           <select class="form-select" name="surveyor" aria-label="Default select example">

                              <option value="">Select Surveyor</option>

                              @if(isset($surveyor) && count($surveyor)>0)

                              @foreach($surveyor as $sKey=>$sValue)

                                <option {{ request('surveyor') == $sValue->id ? 'selected' : '' }} value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                              @endforeach

                              @endif

                           </select>

                           <select class="form-select" name="status" aria-label="Default select example">

                              <option value="" selected="">Status</option>

                              <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active</option>

                              <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive</option>

                           </select> --}}

                           <div class="filter-date">
                              {{-- <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date" id="start_date" autocomplete="off"> --}}

                              <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">
                           </div>
                           
                           <div class="filter-date">
                              {{-- <input type="date" name="end_date" value="{{ request('end_date') }}" id="end_date" class="form-control" placeholder="End Date"> --}}

                              <input type="text" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                           </div >
<div class="d-flex">  <button type="submit" class="searc-btn" >Search</button>

                           <a href="{{route('admin.report.list')}}" type="button" class="btn btn-secondary btn-sm">Reset</a></div>
                         

                        </div>

                     </form>

                  </div>



                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>

                           <tr class="border-b bg-light2">

                              <th>Survey ID</th>

                              <th>Survey Title</th>

                              <th>Zone</th>

                              <th>Store</th>

                              <th>Categories</th>

                              <th style="min-width:192px;">Compliance Officers</th>

                              <th>Status</th>

                              <th>Report</th>

                           </tr>

                        </thead>

                        <tbody>
                           <?php 
                           $today = date('Y-m-d');
                           ?>
                        	@if(isset($data) && count($data)>0)

                        	@foreach($data as $key=>$value)

                              @php
                                $markets = Market::whereIn('id', $value->markets->pluck('market_id'))->pluck('name')->toArray();
                                $categories = Category::whereIn('id', $value->categories->pluck('category_id'))->pluck('name')->toArray();
                                $comOfficers = User::whereIn('id', $value->surveyors->pluck('surveyor_id'))
                                 ->select('name', 'title')
                                 ->get()
                                 ->map(function($user) {
                                    return ($user->title ? $user->title . ' ' : '') . $user->name;
                                 })
                                 ->toArray();


                              @endphp

                           	<tr>

                              	<td>#{{$value->survey_id}}</td>

                                 <td>{{$value->name}}</td>

                              	<td>{{($value->zone)?ucfirst($value->zone->name):''}}</td>

                              	{{-- <td>{{($value->markets)?count($value->markets):'0'}}</td> --}}
                                 <td>
                                   @if(count($markets)>1)
                                     <a href="#" style="color: #2e2e2e;" title="Click to see all" data-id="{{implode(',', $markets)}}" class="markets" id="market_{{$value->id}}" data-bs-toggle="modal" data-bs-target="#marketModal">{{($markets['0'])}}<br>
                                     <span class="moreButton">See All</span>
                                  </a>
                                   @else
                                     {{implode(',', $markets)}}
                                   @endif
                                 </td>

                              	{{-- <td>{{($value->categories)?count($value->categories):'0'}}</td> --}}
                                 <td>
                                   @if(count($categories)>1)
                                     <a href="#" title="Click to see all" style="color: #2e2e2e;" data-id="{{implode(',', $categories)}}" class="categorrryyy" id="categorrryyy_{{$value->id}}" data-bs-toggle="modal" data-bs-target="#categorrryyyModal">{{($categories['0'])}}<br>
                                     <span class="moreButton">See All</span>
                                  </a>
                                   @else
                                     {{implode(',', $categories)}}
                                   @endif
                                 </td>

                                 {{--<td>{{($value->surveyors)?count($value->surveyors):'0'}}</td> --}}
                                 <td>
                                    @if(count($comOfficers) > 1)
                                       <a href="#"
                                          title="Click to see all"
                                          style="color: #2e2e2e;"
                                          data-id="{{ implode(',', $comOfficers) }}"
                                          class="compliance"
                                          id="compliance_{{ $value->id }}"
                                          data-bs-toggle="modal"
                                          data-bs-target="#complianceModal">
                                             {{ $comOfficers[0] }}<br>
                                             <span class="moreButton">See All</span>
                                       </a>
                                    @else
                                       {{ implode(',', $comOfficers) }}
                                    @endif
                                 </td>


                                {{-- <td class="active-bt">{{($value->is_complete == 1)?'Completed':'In Progress'}}</td> --}}
                                <td>
                                   @if($value->is_complete == 1)
                                     <span>Approved</span>
                                   @elseif(($value->is_complete == 0) && $value->end_date<$today)
                                     <span>Overdue</span>
                                   @else
                                     <span>In Progress</span>
                                   @endif
                                </td>

                              	<td>
                              		<?php 
                              			
                              			$endDate = date('Y-m-d', strtotime($value->end_date));
                              			if($value->is_complete == false && ($endDate < $today)){
                          			        $statuss = 'Overdue';
                          			    }else{
                          			        // $statuss = false;
                          			    }
                          			    $statuss = ($value->is_complete == 1)?'Completed':'Pending';
                              		?>
                                 	<div class="action-btn">
	                                    <a href="{{route('admin.survey.report.details', $value->id)}}"><button type="button" class="d-fle btn btn-success btn-sm">View Details</button></a>
                                       {{-- <a href="{{route('admin.survey.report.details', $value->id)}}"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a> --}}
                                 	</div>
                              	</td>
                           	</tr>
                           	@endforeach

                              @else
                              <tr>
                                 <td colspan="8" style="text-align: center;"><strong>No record found</strong></td>
                              </tr>

                           	@endif

                             


                           {{-- <tr>

                              <td>01</td>

                              <td>Jamie</td>

                              <td><span class="num-td">10+</span></td>

                              <td><span class="num-td">12+</span></td>

                              <td><span class="num-td">15+</span></td>

                              <td>Mable</td>

                              <td class="active-bt">Active</td>

                              	<td>

                                 	<div class="action-btn">

                                    	<label class="switch">

                                    	<input type="checkbox">

                                    		<span class="slider round"></span>

                                    	</label>

                                    	<a href="view.php"><img src="assets/img/view-eye.png" alt="view" class="view-icon"></a>

                                    	<img src="assets/img/edit-2.png" alt="edit">

                                    	<img src="assets/img/trash.png" alt="trash">

                                 	</div>

                              	</td>

                           	</tr> --}}

                        </tbody>

                     </table>

                     @if (isset($data))

                     {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }}

                     @endif

                  </div>

               </div>

            </div>

         </div>

      </div>

      <!-- end row-->

   </div>

   <!-- container -->

</div>

</div>

</div>

<div class="modal fade" id="marketModal" tabindex="-1" aria-labelledby="marketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="marketModalLabel">Store Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="marketList"></ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="categorrryyyModal" tabindex="-1" aria-labelledby="categorrryyyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categorrryyyModalLabel">Category Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="categorrryyyList"></ul>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="complianceModal" tabindex="-1" aria-labelledby="complianceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="complianceModalLabel">Compliance Officer Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul id="complianceList"></ul>
      </div>
    </div>
  </div>
</div>


<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

   <div class="modal-dialog modal-dialog-centered modal-lg">

      <div class="modal-content">

         <div class="modal-header">

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

         </div>

         <div class="modal-body">

            <div class="heading">

               <h2 id="">Add Survey</h2>

               <p>Please enter the following Data</p>

            </div>

            <form method="post" id="survey" class="mt-4">

               @csrf

               <div class="row">

                  	<div class="col-md-12">

                     	<input type="hidden" id="id" value="" name="id">

                        <input type="hidden" name="form_type" id="form_type" value="save">

	                     <div class="form-group ad-user">

                        	<label for="name">Survey Title</label>

                        	<input type="text" class="form-control" name="name" id="name" placeholder="Survey Title">

	                        <span class="text-danger error-name"></span>

	                     </div>

                  	</div>

                  	<div class="col-md-12">

                  	    <div class="form-group ad-user">

                  	        <label for="zone"> Zone</label>

                  	        <div class="dropdown-container">

                  	            <select class="form-control customDropdown2 js-select2" id="zone" name="zone[]" multiple>

                  	                <option value="">Select Zone</option>

                  	                @if(isset($zone) && count($zone)>0)

                  	                @foreach($zone as $zKey=>$zoneValue)

                  	                  <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option>

                  	                @endforeach

                  	                @endif

                  	            </select>

                  	            <span class="text-danger error-zone"></span>

                  	        </div>

                  	    </div>

                  	</div>

                     <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="market">Market</label>

                           <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="market" name="market[]" multiple="">

                                  <option value="">Select Market</option>

                                  {{-- @if(isset($market) && count($market)>0)

                                  @foreach($market as $cKey=>$mValue)

                                    <option value="{{$mValue->id}}">{{ucfirst($mValue->name)}}</option>

                                  @endforeach

                                  @endif --}}

                              </select>

                              <span class="text-danger error-market"></span>

                           </div>

                        </div>

                     </div>

                  <div class="col-md-12">

                      <div class="form-group ad-user">

                          <label for="category">Category</label>

                          <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="category" name="category[]" multiple="">

                                  <option value="">Select Category</option>

                                  @if(isset($category) && count($category)>0)

                                  @foreach($category as $cKey=>$catValue)

                                    <option value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option>

                                  @endforeach

                                  @endif

                              </select>

                              <span class="text-danger error-category"></span>

                          </div>

                      </div>

                  </div>

                  	{{-- <div class="col-md-12">

                      	<div class="form-group ad-user">

                          	<label for="product">Product</label>

                          	<div class="dropdown-container">

                              	<select class="form-control customDropdown2 js-select2" id="product" name="product[]" multiple>

                              		<option value="">Select Product</option>

                              	</select>

                              	<span class="text-danger error-product"></span>

                          	</div>

                      	</div>

                  	</div> --}}

                  	<div class="col-md-12">

                      	<div class="form-group ad-user">

                          	<label for="surveyor">Assign Surveyor</label>

                          	<div class="dropdown-container">

                          		<select class="js-select2" id="surveyor" name="surveyor[]" multiple>

                          			<option value="">Select Surveyor</option>

                          			@if(isset($surveyor) && count($surveyor)>0)

                          			@foreach($surveyor as $sKey=>$sValue)

                          			  <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                          			@endforeach

                          			@endif

                          		</select>

                          		{{-- <select class="form-control customDropdown2" id="surveyor" name="surveyor[]" multiple="">

                                    <option value="">Select Surveyor</option>

                                    @if(isset($surveyor) && count($surveyor)>0)

                                    @foreach($surveyor as $sKey=>$sValue)

                                      <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                                    @endforeach

                                    @endif

                              	</select> --}}

                              	<span class="text-danger error-surveyor"></span>

                          	</div>

                      	</div>

                  	</div>





                  	<div class="col-md-12">

	                    <div class="form-group ad-user">

                        	<label for="from_date">From Date</label>

                        	<input type="date" class="form-control" name="from_date" id="from_date" min="{{date('Y-m-d')}}">

	                        <span class="text-danger error-from_date"></span>

	                    </div>

                  	</div>

                  	<div class="col-md-12">

	                    <div class="form-group ad-user">

                        	<label for="to_date">To Date</label>

                        	<input type="date" class="form-control" name="to_date" id="to_date">

	                        <span class="text-danger error-to_date"></span>

	                    </div>

                  	</div>

                  	<div class="col-md-12">

                      	<div class="form-group ad-user">

                          	<label for="status">Status</label>

                          	<div class="dropdown-container">

                              	<select class="form-control customDropdown2" id="status" name="status">

                                  	<option value="1">Active</option>

                                  	<option value="0">Deactive</option>

                              	</select>

                              	<span class="text-danger error-status"></span>

                          	</div>

                      	</div>

                  	</div>

                  	<div class="text-center">

                     	<button type="submit" class="btn btn-save">Add Survey</button>

                  	</div>

               	</div>

            </form>

         </div>

      </div>

   </div>

</div>









<div class="modal fade home-modal exampleModal11" id="exampleModal11" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

   <div class="modal-dialog modal-dialog-centered modal-lg">

      <div class="modal-content">

         <div class="modal-header">

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

         </div>

         <div class="modal-body">

            <div class="heading">

               <h2 id="formHeading">Edit Survey</h2>

               <p>Please enter the following Data</p>

            </div>

            <form method="post" id="editSurvey" class="mt-4">

               @csrf

               <div class="row">

                     <div class="col-md-12">

                        <input type="hidden" id="ids" value="" name="id">

                        <input type="hidden" name="form_type" id="form_type" value="save">

                        <div class="form-group ad-user">

                           <label for="name">Survey Title</label>

                           <input type="text" class="form-control" name="name" id="names" placeholder="Survey Title">

                           <span class="text-danger error-name"></span>

                        </div>

                     </div>

                     <div class="col-md-12">

                         <div class="form-group ad-user">

                             <label for="zone"> Zone</label>

                             <div class="dropdown-container">

                                 <select class="form-control customDropdown2" id="zones" name="zone">

                                     <option value="">Select Zone</option>

                                     @if(isset($zone) && count($zone)>0)

                                     @foreach($zone as $zKey=>$zoneValue)

                                       <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option>

                                     @endforeach

                                     @endif

                                 </select>

                                 <span class="text-danger error-zone"></span>

                             </div>

                         </div>

                     </div>

                     <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="market">Market</label>

                           <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="markets" name="market[]" multiple="">

                                  <option value="">Select Market</option>

                                  {{-- @if(isset($market) && count($market)>0)

                                  @foreach($market as $cKey=>$mValue)

                                    <option value="{{$mValue->id}}">{{ucfirst($mValue->name)}}</option>

                                  @endforeach

                                  @endif --}}

                              </select>

                              <span class="text-danger error-market"></span>

                           </div>

                        </div>

                     </div>

                  <div class="col-md-12">

                      <div class="form-group ad-user">

                          <label for="category">Category</label>

                          <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="categorys" name="category[]" multiple="">

                                  <option value="">Select Category</option>

                                  @if(isset($category) && count($category)>0)

                                  @foreach($category as $cKey=>$catValue)

                                    <option value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option>

                                  @endforeach

                                  @endif

                              </select>

                              <span class="text-danger error-category"></span>

                          </div>

                      </div>

                  </div>

                     {{-- <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="product">Product</label>

                           <div class="dropdown-container">

                                 <select class="form-control customDropdown2 js-select2" id="product" name="product[]" multiple>

                                    <option value="">Select Product</option>

                                 </select>

                                 <span class="text-danger error-product"></span>

                           </div>

                        </div>

                     </div> --}}

                     <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="surveyor">Assign Surveyor</label>

                           <div class="dropdown-container">

                              <select class="js-select2" id="surveyors" name="surveyor[]" multiple>

                                 <option value="">Select Surveyor</option>

                                 @if(isset($surveyor) && count($surveyor)>0)

                                 @foreach($surveyor as $sKey=>$sValue)

                                   <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                                 @endforeach

                                 @endif

                              </select>

                              {{-- <select class="form-control customDropdown2" id="surveyor" name="surveyor[]" multiple="">

                                    <option value="">Select Surveyor</option>

                                    @if(isset($surveyor) && count($surveyor)>0)

                                    @foreach($surveyor as $sKey=>$sValue)

                                      <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                                    @endforeach

                                    @endif

                                 </select> --}}

                                 <span class="text-danger error-surveyor"></span>

                           </div>

                        </div>

                     </div>





                     <div class="col-md-12">

                       <div class="form-group ad-user">

                           <label for="from_date">From Date</label>

                           <input type="date" class="form-control" name="from_date" id="from_dates" min="{{date('Y-m-d')}}">

                           <span class="text-danger error-from_date"></span>

                       </div>

                     </div>

                     <div class="col-md-12">

                       <div class="form-group ad-user">

                           <label for="to_date">To Date</label>

                           <input type="date" class="form-control" name="to_date" id="to_dates">

                           <span class="text-danger error-to_date"></span>

                       </div>

                     </div>

                     <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="status">Status</label>

                           <div class="dropdown-container">

                                 <select class="form-control customDropdown2" id="statuss" name="status">

                                    <option value="1">Active</option>

                                    <option value="0">Deactive</option>

                                 </select>

                                 <span class="text-danger error-status"></span>

                           </div>

                        </div>

                     </div>

                     <div class="text-center">

                        <button type="submit" class="btn btn-save">Update Survey</button>

                     </div>

                  </div>

            </form>

         </div>

      </div>

   </div>

</div>



<div class="modal fade home-modal viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

   <div class="modal-dialog modal-lg modal-dialog-centered height-500">

      <div class="modal-content">

         <div class="modal-header">

            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

         </div>

         <div class="modal-body">

            <div class="heading">

               <h2 id="">Survey Details</h2>

               <!-- <p>Please enter the following Data</p> -->

            </div>

            <form  class="mt-4">

               

               <div class="row">

                     <div class="col-md-12">

                        <input type="hidden" id="ids" value="" name="id">

                        <input type="hidden" name="form_type" id="form_type" value="save">

                        <div class="form-group ad-user">

                           <label for="name">Survey Title</label>

                           <input type="text" class="form-control" name="name" id="namess" placeholder="Survey Title" readonly="">

                        </div>

                     </div>

                     <div class="col-md-12">

                         <div class="form-group ad-user">

                             <label for="zone"> Zone</label>

                             <div class="dropdown-container">

                                 <select class="form-control customDropdown2" id="zoness" name="zone" disabled>

                                     <option value="">Select Zone</option>

                                     @if(isset($zone) && count($zone)>0)

                                     @foreach($zone as $zKey=>$zoneValue)

                                       <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option>

                                     @endforeach

                                     @endif

                                 </select>

                             </div>

                         </div>

                     </div>

                     <div class="col-md-12">

                        <div class="form-group ad-user">

                           <label for="market">Market</label>

                           <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="marketss" name="market[]" multiple="" disabled>

                              </select>

                           </div>

                        </div>

                     </div>

                  <div class="col-md-12">

                      <div class="form-group ad-user">

                          <label for="category">Category</label>

                          <div class="dropdown-container">

                              <select class="form-control customDropdown2 js-select2" id="categoryss" name="category[]" multiple="" disabled>

                                  <option value="">Select Category</option>

                                  @if(isset($category) && count($category)>0)

                                  @foreach($category as $cKey=>$catValue)

                                    <option value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option>

                                  @endforeach

                                  @endif

                              </select>

                          </div>

                      </div>

                  </div>

                  <div class="col-md-12">

                     <div class="form-group ad-user">

                        <label for="surveyor">Assign Surveyor</label>

                        <div class="dropdown-container">

                           <select class="js-select2" id="surveyorss" name="surveyor[]" multiple disabled>

                              <option value="">Select Surveyor</option>

                              @if(isset($surveyor) && count($surveyor)>0)

                              @foreach($surveyor as $sKey=>$sValue)

                                <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option>

                              @endforeach

                              @endif

                           </select>

                        </div>

                     </div>

                  </div>

                  <div class="col-md-12">

                    <div class="form-group ad-user">

                        <label for="from_date">From Date</label>

                        <input type="date" class="form-control" name="from_date" id="from_datess" min="{{date('Y-m-d')}}" readonly="">

                    </div>

                  </div>

                  <div class="col-md-12">

                    <div class="form-group ad-user">

                        <label for="to_date">To Date</label>

                        <input type="date" class="form-control" name="to_date" id="to_datess" readonly="">

                    </div>

                  </div>

                  <div class="col-md-12">

                     <div class="form-group ad-user">

                        <label for="status">Status</label>

                        <div class="dropdown-container">

                              <select class="form-control customDropdown2" id="statusss" name="status" disabled>

                                 <option value="1">Active</option>

                                 <option value="0">Deactive</option>

                              </select>

                              <span class="text-danger error-status"></span>

                        </div>

                     </div>

                  </div>

               </div>

            </form>

         </div>

      </div>

   </div>

</div>

@endsection



@push('scripts')

  <script>

  	$('.js-select2').select2({

        dropdownParent: $('#exampleModal') // Ensures Select2 dropdown appears within modal

    });

    $('body').on('click','.open-survery-modal',function(event){



    	$('#exampleModal').modal('show');

    	$('.js-select2').select2({

            dropdownParent: $('#exampleModal')

        });

    })

	$(".js-select2").select2({

			closeOnSelect : false,

			placeholder : "Placeholder",

			// allowHtml: true,

			allowClear: true,

			tags: true

		});

  </script>

<script>

   // $(document).on('click', '#closeModal', function () {

   //     var modalElement = document.getElementById('exampleModal11');

       

   //     if (modalElement) {

   //         var modal = bootstrap.Modal.getInstance(modalElement);

           

   //         // If the modal instance doesn't exist, create a new one

   //         if (!modal) {

   //             modal = new bootstrap.Modal(modalElement);

   //         }

           

   //         // Hide the modal

   //         modal.hide();

   //     } else {

   //         console.error('Modal element not found');

   //     }

   // });



	$(document).ready(function() {



      $('body').on('click','.btn-close',function(e){

         $('.exampleModal11').modal('hide'); 

      })



      $('body').on('click','.open-edit-modal',function(e){

         $('.exampleModal11').modal('hide');

      })



	    $('.selectpicker').selectpicker();

	});



   $(document).on('click', '.editSurvey', function(e){

      // var modal = new bootstrap.Modal(document.getElementById('exampleModal11'), {

      //     backdrop: 'static',

      //     keyboard: false 

      // });

      $('.js-select2').select2({

         dropdownParent: $('#exampleModal11')

      });



      var id = $(this).attr('id');

      var url = "{{ route('admin.survey.edit', ':id') }}";

      url = url.replace(':id', id); 

      var typBefore = $("#form_type").val();

      if(id){

         $.ajax({

             url: url, 

             type: "GET",

             success: function (response) {

                  // console.log(response.data);

                 if (response.success) {

                  $('#names').val(response.data.name);

                  $('#from_dates').val(response.data.start_date);



                  $('#to_dates').val(response.data.end_date);

                  $('#ids').val(response.data.id);

                  // $('#form_type').val('update');

                  $('#statuss').val(response.data.status).change();

                  $('#zones').val(response.data.zone_id).change();

                  

                  if (response.market.length > 0) {

                     var marketDropdown = $('#markets');

                        marketDropdown.empty();

                     response.market.forEach(market => {

                        $('#markets').append(new Option(market.name, market.id));

                     });

                     setTimeout(()=>{

                        $('#markets option').each(function(){

                           // console.log($(this).val(), $(this).text());

                        });

                        

                        let selectedMarkets = response.data.markets.map(market => String(market.market_id));

                         $('#markets').val(selectedMarkets).trigger('change');

                     }, 500);

                  }



                  $('#categorys').val(response.data.categories.map(category => category.category_id)).change();

                  $('#surveyors').val(response.data.surveyors.map(surveyors => surveyors.surveyor_id)).change();

                  $('#exampleModal11').modal('show');

                 }

             },

             error: function (xhr) {

                 let errors = xhr.responseJSON.errors;

             }

         });

      }

   })



   $(document).on('click', '.copySurvey', function(e){

     

      $('.js-select2').select2({

         dropdownParent: $('#exampleModal11')

      });



      var id = $(this).attr('id');

      var url = "{{ route('admin.survey.edit', ':id') }}";

      url = url.replace(':id', id); 

      var typBefore = $("#form_type").val();

      if(id){

         $.ajax({

             url: url, 

             type: "GET",

             success: function (response) {

                 if (response.success) {

                  $('#names').val(response.data.name);

                  $('#from_dates').val(response.data.start_date);

                  $('#to_dates').val(response.data.end_date);

                  $('#statuss').val(response.data.status).change();

                  $('#zones').val(response.data.zone_id).change();

                  

                  if (response.market.length > 0) {

                     var marketDropdown = $('#markets');

                        marketDropdown.empty();

                     response.market.forEach(market => {

                        $('#markets').append(new Option(market.name, market.id));

                     });

                     setTimeout(()=>{

                        $('#markets option').each(function(){

                        });

                        

                        let selectedMarkets = response.data.markets.map(market => String(market.market_id));

                         $('#markets').val(selectedMarkets).trigger('change');

                     }, 500);

                  }



                  $('#categorys').val(response.data.categories.map(category => category.category_id)).change();

                  $('#surveyors').val(response.data.surveyors.map(surveyors => surveyors.surveyor_id)).change();

                  $("#formHeading").text('Copy Survey')

                  $(".btn").text('Copy Survey')

                  // modal.show();

                  $('.exampleModal11').modal('show');

                 }

             },

             error: function (xhr) {

                 let errors = xhr.responseJSON.errors;

             }

         });

      }

   })



   $(document).on('click', '.viewSurvey', function(e){

      $('.js-select2').select2({

         dropdownParent: $('#viewModal')

      });



      var id = $(this).attr('id');

      var url = "{{ route('admin.survey.edit', ':id') }}";

      url = url.replace(':id', id); 

      var typBefore = $("#form_type").val();

      if(id){

         $.ajax({

             url: url, 

             type: "GET",

             success: function (response) {

                  if (response.success) {

                     $('#namess').val(response.data.name);

                     $('#from_datess').val(response.data.start_date);



                     $('#to_datess').val(response.data.end_date);

                     $('#statusss').val(response.data.status).change();

                     $('#zoness').val(response.data.zone_id).change();

                     

                     if (response.market.length > 0) {

                        var marketDropdown = $('#markets');

                           marketDropdown.empty();

                        response.market.forEach(market => {

                           $('#marketss').append(new Option(market.name, market.id));

                        });

                        setTimeout(()=>{

                           $('#marketss option').each(function(){

                           });

                           

                           let selectedMarkets = response.data.markets.map(market => String(market.market_id));

                            $('#marketss').val(selectedMarkets).trigger('change');

                        }, 500);

                     }



                     $('#categoryss').val(response.data.categories.map(category => category.category_id)).change();

                     $('#surveyorss').val(response.data.surveyors.map(surveyors => surveyors.surveyor_id)).change();

                     $('.viewModal').modal('show');

                  }

             },

             error: function (xhr) {

                 let errors = xhr.responseJSON.errors;

             }

         });

      }

   })



	$(document).ready(function () {



      $('#editSurvey').on('submit', function (e) {

         e.preventDefault();

         let formData = new FormData(this);

         let url = "{{ route('admin.survey.update') }}";

          $.ajax({

              url: url, 

              type: "POST",

              data: formData,

              processData: false,

              contentType: false,

              beforeSend: function () {

                  $('.text-danger').text('');

              },

              success: function (response) {

                  if(!response.success){

                     toastr.error(response.message)

                  }else{

                     toastr.success(response.message)

                     modal.hide();

                     location.reload();

                  }

              },

              error: function (xhr) {

                  let errors = xhr.responseJSON.errors; 



                  $(".text-danger").text("");



                  if (errors.name) {

                      $(".error-name").text(errors.name[0]);

                  }

                  if (errors.zone) {

                      $(".error-zone").text(errors.zone[0]);

                  }

                  if (errors.market) {

                      $(".error-market").text(errors.market[0]);

                  }

                  if (errors.category) {

                      $(".error-category").text(errors.category[0]);

                  }

                  // if (errors.product) {

                  //     $(".error-product").text(errors.product[0]);

                  // }

                  if (errors.surveyor) {

                      $(".error-surveyor").text(errors.surveyor[0]);

                  }

                  if (errors.from_date) {

                      $(".error-from_date").text(errors.from_date[0]);

                  }

                  if (errors.to_date) {

                      $(".error-to_date").text(errors.to_date[0]);

                  }

                  if (errors.status) {

                      $(".error-status").text(errors.status[0]);

                  }
                   $('.exampleModal11').modal('show'); 
                  // modal.show();

              }

          });

      });



		$("#zone").on('change', function(){

			var id = $(this).val();

			var url = "{{ route('admin.get.zones.market', ':id') }}";

			url = url.replace(':id', id); 

			if(id){

			   $.ajax({

			       url: url, 

			       type: "GET",

			       success: function (response) {

			           	if(response.success){

				           	let data = response.data;

	                       	let dropdown = $('#market'); 

	                       	dropdown.empty();

	                       	dropdown.append('<option value="">Select Market</option>');

	                       	response.data.forEach(function(item) {

	                           	dropdown.append(`<option value="${item.id}">${item.name}</option>`);

	                       	});

			           	}

			       	},

			       	error: function (xhr) {

			           let errors = xhr.responseJSON.errors;

			       	}

			    });

			}else{

               	let dropdown = $('#market'); 

               	dropdown.append('<option value="">Select Market</option>');

			}

		})



      $("#filterZone").on('change', function(){

         var id = $(this).val();

         var url = "{{ route('admin.get.zones.market', ':id') }}";

         url = url.replace(':id', id); 

         if(id){

            $.ajax({

                url: url, 

                type: "GET",

                success: function (response) {

                     if(response.success){

                        let data = response.data;

                           let dropdown = $('#filterMarket'); 

                           dropdown.empty();

                           dropdown.append('<option value="">Select Market</option>');

                           response.data.forEach(function(item) {

                                 dropdown.append(`<option value="${item.id}">${item.name}</option>`);

                           });

                     }

                  },

                  error: function (xhr) {

                    let errors = xhr.responseJSON.errors;

                  }

             });

         }else{

                  let dropdown = $('#market'); 

                  dropdown.append('<option value="">Select Market</option>');

         }

      })



		$("#category").on('change', function(){

			var id = $(this).val();

			var url = "{{ route('admin.get.category.product', ':id') }}";

			url = url.replace(':id', id); 

			if(id){

			   $.ajax({

			       url: url, 

			       type: "GET",

			       success: function (response) {

			           	if(response.success){

				           	let data = response.data;

	                       	let dropdown = $('#product'); 

	                       	dropdown.empty();

	                       	dropdown.append('<option value="">Select Product</option>');

	                       	response.data.forEach(function(item) {

	                           	dropdown.append(`<option value="${item.id}">${item.name}</option>`);

	                       	});

			           	}

			       	},

			       	error: function (xhr) {

			           let errors = xhr.responseJSON.errors;

			       	}

			    });

			}else{

               	let dropdown = $('#product'); 

               	dropdown.append('<option value="">Select Produc</option>');

			}

		})

		



		var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {

		    backdrop: 'static',

		    keyboard: false 

		});



		$('#survey').on('submit', function (e) {

		    e.preventDefault();



		    let formData = new FormData(this);



		    $.ajax({

		        url: "{{ route('admin.survey.save') }}", 

		        type: "POST",

		        data: formData,

		        processData: false,

		        contentType: false,

		        beforeSend: function () {

		            $('.text-danger').text('');

		        },

		        success: function (response) {
                  console.log(response);
		            if(!response.success){

                     toastr.error(response.message)

                  }else{

                     toastr.success(response.message)

                     modal.hide();

                     location.reload();

                  }

		        },

		        error: function (xhr) {

		            let errors = xhr.responseJSON.errors; 



		            $(".text-danger").text("");



		            if (errors.name) {

		                $(".error-name").text(errors.name[0]);

		            }

		            if (errors.zone) {

		                $(".error-zone").text(errors.zone[0]);

		            }

		            if (errors.market) {

		                $(".error-market").text(errors.market[0]);

		            }

		            if (errors.category) {

		                $(".error-category").text(errors.category[0]);

		            }

		            // if (errors.product) {

		            //     $(".error-product").text(errors.product[0]);

		            // }

		            if (errors.surveyor) {

		                $(".error-surveyor").text(errors.surveyor[0]);

		            }

		            if (errors.from_date) {

		                $(".error-from_date").text(errors.from_date[0]);

		            }

		            if (errors.to_date) {

		                $(".error-to_date").text(errors.to_date[0]);

		            }

		            if (errors.status) {

		                $(".error-status").text(errors.status[0]);

		            }

		            modal.show();

		        }

		    });

		});







		$(".toggleSwitch").on("change", function(){

		   var status = $(this).is(":checked") ? 1 : 0;

		   var id = $(this).val();



		   $.ajax({

		      url: "{{ route('admin.survey.update.status') }}",

		      type: "POST",

		      data: {_token: "{{ csrf_token() }}", status: status, id:id },

		      success: function(response){

		         console.log(response);

		         if (response.success) {

		            toastr.success(response.message)

		         }

		      },

		      error: function(xhr, status, error){

		         toastr.success(response.message)

		      }

		   });

		});



		// File input change event

		$('#fileInput').on('change', function (event) {

		 const file = event.target.files[0];

		 $('#fileName').text(file ? file.name : 'Upload Image');

		});

	

		function setupDropdown(dropdownButtonId) {

		 	const $dropdownButton = $('#' + dropdownButtonId);

		 	const $dropdownMenu = $dropdownButton.next();

		 	const $dropdownItems = $dropdownMenu.find('.dropdown-item');

		

		 	// Toggle dropdown visibility

		 	$dropdownButton.on('click', function () {

		   		$dropdownMenu.toggle();

		 	});

		

		 	// Update dropdown button text on item click

		 	$dropdownItems.on('click', function () {

		   		const selectedValue = $(this).data('value');

		   		$dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');

		   		$dropdownMenu.hide();

		 	});

		

		 	// Close dropdown when clicking outside

		 	$(document).on('click', function (e) {

		   		if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {

		     		$dropdownMenu.hide();

		   		}

		 	});

		}

	

		// Initialize dropdowns

		setupDropdown('dropdownButton1');

		setupDropdown('dropdownButton2');

		setupDropdown('dropdownButton3');

		setupDropdown('dropdownButton4');

		

		// Multi-select dropdown setup

		const $dropdownButton = $('#dropdownButton');

		const $dropdownMenu = $('#dropdownMenu');

		const $checkboxes = $dropdownMenu.find("input[type='checkbox']");

		

		// Toggle dropdown visibility

		$dropdownButton.on('click', function () {

		 	$dropdownMenu.toggle();

		});

	

		// Close dropdown when clicking outside

		$(document).on('click', function (e) {

		 	if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {

		   		$dropdownMenu.hide();

		 	}

		});

	

		// Update dropdown button text based on selected items

		$checkboxes.on('change', function () {

		 	const selectedOptions = $checkboxes.filter(':checked').map(function () {

		   		return $(this).val();

		 	}).get();

		

		 	$dropdownButton.html(

		   		selectedOptions.length > 0 ? selectedOptions.join(', ') + ' <i class="fa fa-caret-down"></i>'

		     : 'Select Options <i class="fa fa-caret-down"></i>'

		 	);

		});

	});



	function toggleDropdown() {

	  var dropdown = document.getElementById("dropdown");

	   dropdown.classList.toggle("active");

	}



   window.onload = function () {

      let params = new URLSearchParams(window.location.search);

         if (params.has('name') || params.has('survey_id') || params.has('zone') || params.has('category') || params.has('surveyor') || params.has('start_date') || params.has('end_date') || params.has('status') || params.has('market')) {

         let dropdown = document.getElementById("dropdown");

         dropdown.classList.toggle("active");

      }

   };


   $(document).on('click', '.markets', function () {
         var data = $(this).data('id'); // Get comma-separated string
         var markets = data.split(','); // Convert to array
         var listHtml = '';

         // Loop through and create list items
         markets.forEach(function(item) {
             listHtml += '<li>' + item.trim() + '</li>';
         });

         // Inject into the modal's <ul>
         $('#marketList').html(listHtml);
     });

   $(document).on('click', '.categorrryyy', function () {
         var data = $(this).data('id'); // Get comma-separated string
         var markets = data.split(','); // Convert to array
         var listHtml = '';

         // Loop through and create list items
         markets.forEach(function(item) {
             listHtml += '<li>' + item.trim() + '</li>';
         });

         // Inject into the modal's <ul>
         $('#categorrryyyList').html(listHtml);
     });

   $(document).on('click', '.compliance', function () {
    var data = $(this).data('id');
    var markets = data.split(',');
    var listHtml = '';

    markets.forEach(function(item) {
        listHtml += '<li>' + item.trim() + '</li>';
    });

    $('#complianceList').html(listHtml);
});



</script>
<script src="{{ asset('admin/multi/multiselect-dropdown.js') }}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script>
   $(function() {
     $("#start_date").datepicker();
     $("#end_date").datepicker();
   });
</script>



@endpush