@extends('admin.layouts.app')
@section('title', @$title)
@section('content')

<style>
  .dropdown-container-filter .name-input {
    width: 16%;
}
.dropdown-container-filter select {
    width: 16%;
}
.filter-date {
    width: 16%;
}
.filter-date input {
    width: 100%;
}
.multiselect-dropdown {
    height: 50px;
    border-radius: 50px;
    background: #fff;
    padding: 14px 10px;
    width: 100% !important;
    white-space: nowrap;
}

.multiselect-dropdown span.placeholder {
    background: transparent;
    color: var(--bs-body-color) !important;
    opacity: 1;
}

.multiselect-dropdown:after {
    content: "";
    display: inline-block;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0.45em 0.45em 0 0.45em;
    border-color: #747a80 transparent transparent transparent;
    margin-left: 0.4em;
    vertical-align: 0.1em;
    position: absolute;
    right: 22px;
    top: 22px;
}
.multiselect-dropdown-list input[type="checkbox"] {
    display: inline-block;
    height: auto;
}
.moreButton{
  font-size: 12px;
  color: #3f87fd;
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
              <div class="col-xl-5">
                <h4 class="header-title mb-0 font-weight-bold"> Surveys </h4>
              </div>
              <div class="col-12 col-md-7 col-lg-7">
                <div class="search-btn1 text-end">

                  <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()" data-toggle="tooltip" data-placement="top" title="Filter">
                    <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>
                  @can('survey_create')
                  <button class="d-fle open-survery-modal btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Create Survey">
                    <i class="fa-solid fa-plus"></i>&nbsp;Surveys </button>
               
                  <a class="btn btn-secondary btn-sm" href="{{route('admin.survey.export', request()->query())}}" data-toggle="tooltip" data-placement="top" title="Export">
                    <i class="fas fa-file-download"></i> Export </a>
                    @endcan
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <form action="{{route('admin.survey.filter')}}" method="get">
                <hr>
                <div id="dropdown" class="dropdown-container-filter">
                  <div class="name-input">
                    <input type="text" class="form-control" name="survey_id" id="survey_id" placeholder="Survey ID" value="{{ request('survey_id') }}">
                  </div>
                  <div class="name-input">
                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Survey Title" value="{{ request('name') }}">
                  </div>

                  <select class="form-select" name="zone" id="filterZone" aria-label="Default select example">
                    <option value="" disabled selected>Select Zone</option> @if(isset($zone) && count($zone)>0) @foreach($zone as $zKey=>$zoneValue) <option {{ request('zone') == $zoneValue->id ? 'selected' : '' }} value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option> @endforeach @endif
                  </select>


                  <select class="form-select" name="market" id="filterMarket" aria-label="Default select example">
                    <option value="" disabled selected>Select Store</option> @if(isset($filterMarket) && count($filterMarket)>0) @foreach($filterMarket as $zKey=>$marketValues) <option {{ request('market') == $marketValues->id ? 'selected' : '' }} value="{{$marketValues->id}}">{{ucfirst($marketValues->name)}}</option> @endforeach @endif
                  </select>
                  <select class="form-select" name="category" aria-label="Default select example">
                    <option value="" selected disabled>Select Category</option> @if(isset($category) && count($category)>0) @foreach($category as $cKey=>$catValue) <option {{ request('category') == $catValue->id ? 'selected' : '' }} value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option> @endforeach @endif
                  </select>
                  <select class="form-select" name="surveyor" aria-label="Default select example">
                    <option value="" selected disabled>Select Compliance Officer</option> @if(isset($surveyor) && count($surveyor)>0) @foreach($surveyor as $sKey=>$sValue) <option {{ request('surveyor') == $sValue->id ? 'selected' : '' }} value="{{$sValue->id}}">{{ucfirst($sValue->title)}} {{ucfirst($sValue->name)}}</option> @endforeach @endif
                  </select>
                  <select class="form-select" name="status" aria-label="Default select example">
                    <option value="" selected disabled>Status</option>
                    <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active </option>
                    <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive </option>
                  </select>
                  <div class="filter-date">
                    {{-- <label for="start-date">Start Date</label> --}}
                    <input type="text" name="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date" id="start_date" autocomplete="off">
                  </div>
                  <div class="filter-date">
                    {{-- <label for="end-date">End Date</label> --}}
                    <input type="text" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                  </div>
                  <button type="submit" class="d-flex searc-btn ">Search</button>
                   <a href="{{ route('admin.survey.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                </div>
              </form>
            </div>
            <div class="table-responsive white-space md-4">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th style="min-width:80px;">Survey ID</th>
                    <th style="min-width:120px;">Survey Title</th>
                    <th style="min-width:100px;">Zone</th>
                    <th style="min-width:100px;">Store</th>
                    <th style="min-width:100px;">Categories</th>
                    <!-- <th style="min-width:10%;">Products</th> -->
                    <th style="min-width:165px;">Compliance Officer</th>
                    <th style="min-width:110px;">Created At</th>
                    @can('survey_edit')
                    <th style="min-width:100px;">Status</th>
                    @endcan
                    @canany(['survey_edit','survey_delete'])
                    <th style="min-width:220px;">Action</th>
                    @endcanany
                  </tr>
                </thead>
                <tbody> 
                @php $today = date('Y-m-d'); @endphp
                @if(isset($data) && count($data)>0) 
                  @foreach($data as $key=>$value) 
                  @php
                    $markets = Market::whereIn('id', $value->markets->pluck('market_id'))->pluck('name')->toArray();
                    $categories = Category::whereIn('id', $value->categories->pluck('category_id'))->pluck('name')->toArray();
                   $comOfficers = User::whereIn('id', $value->surveyors->pluck('surveyor_id'))
                    ->get(['title', 'name']) // fetch both fields
                    ->toArray();

                  @endphp
                  <tr>
                    <td>#{{$value->survey_id}}</td>
                    <td>{{ Str::limit($value->name, 20) }}<br>
                      @if($value->is_complete == 1)
                        <span style="background: #31cb72; color: #fff;padding: 1px 8px;border-radius: 10%;font-size: 12;">Approved</span>
                      @elseif(($value->is_complete == 0) && $value->end_date<$today)
                        <span style="background: #f15149; color: #fff;padding: 1px 8px;border-radius: 10%; font-size: 12px;">Overdue</span>
                      @else
                        <span style="background: #f1c907; color: #fff;padding: 1px 8px;border-radius: 10%; font-size: 12px;">In Progress</span>
                      @endif
                    </td>
                    <td>{{($value->zone) ? ucfirst($value->zone->name):'' }}</td>

                    <td>
                      @if(count($markets)>1)
                        <a href="#" title="Click to see all" style="color: #2e2e2e;" data-id="{{implode(',', $markets)}}" class="markets" id="market_{{$value->id}}" data-bs-toggle="modal" data-bs-target="#marketModal">{{($markets['0'])}} @if($markets['1']), {{($markets['1'])}}@endif<br> 
                          @if(count($markets)>2)
                          <span class="moreButton">See All</span></a>
                          @endif
                      @else
                        {{implode(',', $markets)}}
                      @endif
                    </td>

                    {{-- <td>{{($value->markets)?count($value->markets):'0'}}</td> --}}
                    {{-- <td>{{($value->categories)?count($value->categories):'0'}}</td> --}}

                    <td>
                      @if(count($categories)>1)
                        <a href="#" title="Click to see all" style="color: #2e2e2e;" data-id="{{implode(',', $categories)}}" class="categorrryyy" id="categorrryyy_{{$value->id}}" data-bs-toggle="modal" data-bs-target="#categorrryyyModal">{{($categories['0'])}} @if($categories['1']), {{($categories['1'])}}@endif<br> 
                          @if(count($categories)>2)
                          <span class="moreButton">See All</span></a>
                          @endif
                        </a>
                      @else
                        {{implode(',', $categories)}}
                      @endif
                    </td>

                    {{-- <td>{{($value->products)?count($value->products):'0'}}</td> --}} 
                    {{-- <td>{{($value->surveyors)?count($value->surveyors):'0'}}</td> --}}
                    <td>
                        @if(count($comOfficers) > 1)
                          <a href="#"
                            title="Click to see all"
                            style="color: #2e2e2e;"
                            data-officers='@json($comOfficers)'
                            class="compliance"
                            id="compliance_{{$value->id}}"
                            data-bs-toggle="modal"
                            data-bs-target="#complianceModal">

                            {{ !empty($comOfficers[0]['title']) ? $comOfficers[0]['title'].' ' : '' }}{{ $comOfficers[0]['name'] }}

                            @if(isset($comOfficers[1]))
                              , {{ !empty($comOfficers[1]['title']) ? $comOfficers[1]['title'].' ' : '' }}{{ $comOfficers[1]['name'] }}
                            @endif

                            <br>
                            @if(count($comOfficers) > 2)
                              <span class="moreButton">See All</span>
                            @endif
                          </a>
                        @else
                          {{ !empty($comOfficers[0]['title']) ? $comOfficers[0]['title'].' ' : '' }}{{ !empty($comOfficers[0]['name']) ? $comOfficers[0]['name'].' ' : '' }}
                        @endif
                      </td>


                    
                    {{-- <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td> --}}
                       <td>{{ customt_date_format($value->created_at)}}</td>
                     @can('survey_edit')
                    <td class="active-bt">
                      <label class="switch">
                        <input  data-toggle="tooltip" data-placement="top" title="Status" type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                      </label>
                    </td>
                    <td>
                      @endcan
                      <div class="action-btn">
                        @can('survey_edit')
                        <a href="javascript:void(0)">
                          <img  data-toggle="tooltip" data-placement="top" title="View" src="{{asset('admin/img/view-eye.png')}}" id="{{$value->id}}" alt="view" class="view-icon viewSurvey">
                        </a>
                        @endcan

                        @can('survey_edit')
                        <img  data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editSurvey " id="{{$value->id}}" alt="edit">
                        @endcan

                        @can('survey_delete')
                        <img  data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteSurvey" alt="trash">
                        @endcan

                        @can('survey_create')
                        <a  data-toggle="tooltip" data-placement="top" title="Copy"  href="javascript:void(0)" id="{{$value->id}}" class="copySurvey">
                          <i class="far fa-copy"></i>
                        </a>
                        @endcan
                      </div>
                    </td>
                  </tr> 
                  @endforeach
                  @else
                  <tr>
                    <td colspan="8">
                      <p class="no_data_found">No Data found! </p>
                    </td>
                  </tr> 
                  @endif 
                </tbody>
              </table> @if (isset($data)) {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<div class="modal fade" id="marketModal" tabindex="-1" aria-labelledby="marketModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
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


<!-- Add Survey -->
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
        <form method="post" id="survey" class="mt-4"> @csrf <div class="loader"></div>
          <div class="screen-block"></div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <input type="hidden" id="id" value="" name="id">
              <input type="hidden" name="form_type" id="form_type" value="save">
              <div class="form-group ad-user marg-bot">
                <label>Survey Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Survey Title">
                <span class="text-danger error-name"></span>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label>Type <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select id="type" name="type" class="form-control">
                    <option value="" selected disabled>Select</option>
                    @if(isset($type) && count($type)>0) 
                      @foreach($type as $oKey=>$typeValue) 
                      <option value="{{$typeValue->id}}">{{ucfirst($typeValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-type"></span>
                </div>
              </div>
            </div>

              <div class="col-md-6 mb-3" id="zone-wrapper">
                <label>Zone <span class="text-danger">*</span></label>
                <select id="zone" name="zone[]"  multiple multiselect-search="true" multiselect-select-all="true" onchange="console.log(this.selectedOptions)">
                  @if(isset($zone) && count($zone)>0) 
                    @foreach($zone as $zKey=>$zoneValue) 
                    <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option> 
                    @endforeach 
                  @endif
                </select>
                <span class="text-danger error-zone"></span>
              </div>
              
            <div class="col-md-6 mb-3" id="market-container">
              <label>Store <span class="text-danger">*</span></label>
              <div id="market-wrapper"></div>
              <div class="form-group ad-user marg-bot " id="market_hide">
                <div class="rela-icon">
                  <select name="" class="form-control">
                    <option value="" selected disabled>Select</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-market"></span>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3" id="category-container">
              <label>Category <span class="text-danger">*</span></label>
              <div id="category-wrapper"></div>
              <div class="form-group ad-user marg-bot" id="category_hide">
                <div class="rela-icon">
                  <select name="" class="form-control">
                    <option value="" selected disabled>Select</option>
                    </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-category"></span>
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3" id="compliance-wrapper">
              <label>Compliance Officer <span class="text-danger">*</span></label>
              <select id="surveyor" name="surveyor[]" multiple multiselect-search="true" multiselect-select-all="true" onchange="console.log(this.selectedOptions)">

                @if(isset($surveyor) && count($surveyor)>0) 
                  @foreach($surveyor as $sKey=>$sValue) 
                  <option value="{{$sValue->id}}">{{ucfirst($sValue->title)}} {{ucfirst($sValue->name)}}</option> 
                  @endforeach 
                @endif
              </select>
              <span class="text-danger error-surveyor"></span>
            </div>

            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label>Investigation Officer <span class="text-danger"></span></label>
                <div class="rela-icon">
                  <select id="investigation" name="investigation" class="form-control">
                    <option value="" selected >Select Investigation Officer</option>
                    @if(isset($investigationOfficer) && count($investigationOfficer)>0) 
                      @foreach($investigationOfficer as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->title)}} {{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-investigation"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label> Chief Investigation Officer<span class="text-danger"></span> </label>
                <div class="rela-icon">
                  <select class="form-control" id="chiefinvestigation" name="chiefinvestigation">
                    <option value="" selected >Select</option>
                    @if(isset($chiefofficer) && count($chiefofficer)>0) 
                      @foreach($chiefofficer as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->title)}} {{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-chiefinvestigation"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label>From Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control future_date" name="from_date" id="from_date" >
                <span class="text-danger error-from_date"></span>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label>To Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control future_date" name="to_date" id="to_date" >
                {{-- <input type="date" class="form-control" name="to_date" id="to_date" min="{{ date('Y-m-d') }}" > --}}
                <span class="text-danger error-to_date"></span>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label > Select Status <span class="text-danger">*</span> </label>
                <div class="rela-icon">
                  <select class="form-control" id="status" name="status">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-status"></span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btnrhrhtht btn-save">Add Survey</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of add survey -->

<!-- Edit Survey -->
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
        <form method="post" id="editSurvey" class="mt-4"> @csrf <div class="row">
            <div class="col-md-6">
              <input type="hidden" id="ids" value="" name="id">
              <input type="hidden" name="form_type" id="form_type" value="save">
              <div class="form-group ad-user">
                <label for="name">Survey Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="names" placeholder="Survey Title">
                <span class="text-danger error-name"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label>Type <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select id="types" name="type" class="form-control">
                    <option value="" selected disabled>Select</option>
                    @if(isset($type) && count($type)>0) 
                      @foreach($type as $oKey=>$typeValue) 
                      <option value="{{$typeValue->id}}">{{ucfirst($typeValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  
                  <span class="text-danger error-type"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="zone"> Zone  <span class="text-danger">*</span></label>
                <div class="rela-icon">

                  <select class="form-control" id="zones" name="zone">
                    <option value="">Select Zone</option> 
                    @if(isset($zone) && count($zone)>0) 
                      @foreach($zone as $zKey=>$zoneValue) 
                      <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>

                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-zone"></span>
                </div>
              </div>
            </div>

            <div class="col-md-6" id="edit-market-container">
              <label>Store <span class="text-danger">*</span></label>
              <div id="edit-market-wrapper"></div>
              <span class="text-danger error-market"></span>
            </div>
            <div class="col-md-6" id="hide-market">
              <div class="form-group ad-user">
                <label for="surveyor">Store <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select class="form-control" id="markets" name="market[]" multiple multiselect-search="true" multiselect-select-all="true">
                    @if(isset($market) && count($market)>0) 
                      @foreach($market as $cKey=>$mValue)
                        <option value="{{$mValue->id}}">{{ucfirst($mValue->name)}}</option>
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid "></i>
                  <span class="text-danger error-market"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="hide-category">
              <div class="form-group ad-user">
                <label for="surveyor">Category <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <!-- <select class="form-control" id="categorys" name="category[]" multiple multiselect-search="true" multiselect-select-all="true">
                    @if(isset($category) && count($category)>0) 
                     @foreach($category as $sKey=>$sValue) 
                      <option value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option> 
                     @endforeach 
                    @endif
                  </select> -->

                   <select class="form-control" id="categorys" name="category[]" multiple multiselect-search="true" multiselect-select-all="true">
                
        
                      <option value=""></option> 
                    
                   
                  </select>
                  <i class="fa-solid "></i>
                  <span class="text-danger error-category"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="edit-category-container">
              <label>Category <span class="text-danger">*</span></label>
              <div id="edit-category-wrapper"></div>
              <span class="text-danger error-category"></span>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="surveyor">Compliance Officer <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select class="form-control" id="surveyors" name="surveyor[]" multiple multiselect-search="true" multiselect-select-all="true">
                    @if(isset($surveyor) && count($surveyor)>0) @foreach($surveyor as $sKey=>$sValue) <option value="{{$sValue->id}}">{{ucfirst($sValue->title)}} {{ucfirst($sValue->name)}}</option> @endforeach @endif
                  </select>
                  <i class="fa-solid "></i>
                  <span class="text-danger error-surveyor"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label>Investigation Officer <span class="text-danger"></span></label>
                <div class="rela-icon">
                 
                  <select class="form-control" name="investigation" id="investigationsOfficer">
                      <option value="" selected>Select Investigation Officer</option> 
                      @foreach($investigationOfficer as $officerValue) 
                        <option value="{{$officerValue->id}}">
                          {{ ucfirst($officerValue->title) }} {{ ucfirst($officerValue->name) }}
                        </option> 
                      @endforeach
                    </select>

                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-investigation"></span>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="status">Chief Investigation Officer <span class="text-danger"></span></label>
                <div class="rela-icon">
                  <select class="form-control" id="chiefinvestigations" name="chiefinvestigation">
                    <option value="" selected >Select Chief Investigation Officer </option> 
                    @if(isset($chiefofficer) && count($chiefofficer)>0) 
                      @foreach($chiefofficer as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->title)}} {{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-chiefinvestigation"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="from_date">From Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control future_date" name="from_date" id="from_dates" >
            
                <span class="text-danger error-from_date"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="to_date">To Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control future_date" name="to_date" id="to_dates" >
                <span class="text-danger error-to_date"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="status">Status  <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select class="form-control customDropdown2" id="statuss" name="status">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-status"></span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btnrhrhtht btn-save">Update Survey</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End of edit survey -->

<!-- Detail survey -->
<div class="modal fade home-modal viewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="">Survey Details</h2>
        </div>
        <form class="mt-4">
          <div class="row surv-lab">
            <div class="col-md-6">
              <input type="hidden" id="ids" value="" name="id">
              <input type="hidden" name="form_type" id="form_type" value="save">
              <div class="form-group ad-user">
                <label for="name">Survey Title</label>
                <input type="text" class="form-control" name="name" id="namess" placeholder="Survey Title" readonly="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label> Type</label>
                <div class="rela-icon">
                  <select class="form-control" id="typess" name="type" disabled>
                    <option value="" selected disabled>Select</option>
                    @if(isset($type) && count($type)>0) 
                      @foreach($type as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="zone"> Zone</label>
                <div class="dropdown-container">
               <select class="form-control" id="zoness" name="zone" disabled>
                    @if(isset($zone) && count($zone)>0) @foreach($zone as $zKey=>$zoneValue) 
                    <option value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option> @endforeach @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="view-market-container">
              <div id="view-market-wrapper">
                <label>Store</label>
              </div>
              <span class="text-danger error-market"></span>
            </div>
             <div class="col-md-6" id="view-category-container">
              <div id="view-category-wrapper">
                <label>Category</label>
              </div>Compliance Officer
              <span class="text-danger error-category"></span>
            </div>
            <div class="col-md-6" id="view-compilances-container">
              <div id="view-compilances-wrapper">
                <label>Compliance Officer </label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <div class="dropdown-container">
                  <label>Investigation Officer</label>
                  <select class="form-control" id="investigationss" name="investigation" disabled>
                    <option value="" selected disabled>Select Investigation Officer</option> 
                    @if(isset($investigationOfficer) && count($investigationOfficer)>0) 
                      @foreach($investigationOfficer as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->title)}} {{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label> Chief Investigation Officer </label>
                <div class="rela-icon">
                  <select class="form-control" id="chiefinvestigationss" name="chiefinvestigation" disabled>
                    <option value="" selected disabled>Select Chief Investigation Officer</option>
                    @if(isset($chiefofficer) && count($chiefofficer)>0) 
                      @foreach($chiefofficer as $oKey=>$officerValue) 
                      <option value="{{$officerValue->id}}">{{ucfirst($officerValue->title)}} {{ucfirst($officerValue->name)}}</option> 
                      @endforeach 
                    @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="from_date">From Date</label>
                 <input type="text" class="form-control" name="from_date" id="from_datess" readonly="">

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="to_date">To Date</label>
                <input type="text" class="form-control" name="to_date" id="to_datess" readonly="" >
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label for="status">Status</label>
                <div class="dropdown-container">
                  <select class="form-control customDropdown2" id="statusss" name="status" disabled>
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> 
<!-- End of detail survey -->

@endsection
@push('scripts')
<!-- Add before closing </body> -->


<script>
  $('body').on('click', '.open-survery-modal', function(event) {
    $('#exampleModal').modal('show');
  })

$(document).ready(function() {

  $('body').on('click', '.btn-close', function(e) {
    $('.exampleModal11').modal('hide');
  })
  $('body').on('click', '.open-edit-modal', function(e) {
    $('.exampleModal11').modal('hide');
  })
  $('.selectpicker').selectpicker();
});

$(document).ready(function(){
  $('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
  });

  $('#exampleModal11').on('hidden.bs.modal', function () {
    location.reload();
  });
});

// Edit Survey
$(document).on('click', '.editSurvey', function(e) {
  var id = $(this).attr('id');
  var url = "{{ route('admin.survey.edit', ':id') }}";
  url = url.replace(':id', id);

  if (id) {
    $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
        if (response.success) {
          console.log(response.data);

          $('#edit-market-container').hide();
          $('#edit-category-container').hide();
          $('#names').val(response.data.name);
          $('#from_dates').val(response.data.start_date);
          $('#to_dates').val(response.data.end_date);
          $('#ids').val(response.data.id);
          $('#statuss').val(response.data.status);
          $('#zones').val(response.data.zone_id);
      let investigationIds = response.data.investigation_officer.map(officer => officer.id);

if (investigationIds.length > 0) {
    $('#investigationsOfficer').val(investigationIds).trigger('change');
} else {
    // Reset dropdown to first option
    $('#investigationsOfficer').val('').trigger('change');
}


          // Market Dropdown
          const marketEl = document.getElementById("markets");
          const marketWrapper = marketEl.nextElementSibling;
          if (marketWrapper && marketWrapper.classList.contains("multiselect-dropdown")) {
            marketWrapper.remove();
          }
          marketEl.removeAttribute("data-multiselect-init");

          const marketDropdown = $('#markets');
          marketDropdown.empty();
          response.totalMarkets.forEach(market => {
            marketDropdown.append(new Option(market.name, market.id));
          });
          const selectedMarkets = response.market.map(m => String(m.market_id)); 
          marketDropdown.val(selectedMarkets);
          marketDropdown.trigger('change');

          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(marketEl);
            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }

          // ✅ Set the category dropdown using all typecategory options and pre-select existing ones
            const categoryDropdown = $('#categorys');
            categoryDropdown.empty(); // Clear existing options

            // Get selected category IDs from the survey
            const selectedCategoryIds = response.data.categories.map(c => String(c.category_id));

            // Loop over all available typecategory items
            response.typecategory.forEach(category => {
              const option = new Option(category.name, category.id);
              if (selectedCategoryIds.includes(String(category.id))) {
                option.selected = true;
              }
              categoryDropdown.append(option);
            });

            categoryDropdown.trigger('change');

            // Multiselect re-initialization
            const categoryEl = document.getElementById("categorys");
            const wrapper = categoryEl.nextElementSibling;
            if (wrapper && wrapper.classList.contains("multiselect-dropdown")) {
              wrapper.remove();
            }
            categoryEl.removeAttribute("data-multiselect-init");

     


          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(categoryEl);
        
            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();
            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }

          // Surveyors (Compliance Officers)
          const compilancesids = response.data.surveyors.map(surveyor => String(surveyor.surveyor_id));
          $('#surveyors').val(compilancesids);

          const compilanceEl = document.getElementById("surveyors");
          const surveyorWrapper = compilanceEl.nextElementSibling;
          if (surveyorWrapper && surveyorWrapper.classList.contains("multiselect-dropdown")) {
            surveyorWrapper.remove();
          }
          compilanceEl.removeAttribute("data-multiselect-init");

          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(compilanceEl);
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();
            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }

          $('#investigations').val(response.data.investigationOfficer);
          $('#chiefinvestigations').val(response.data.chief_investigation_officer);
          $('#types').val(response.data.type[0]?.id);

          $("#formHeading").text('Edit Survey');
          $(".btnrhrhtht").text('Update Survey');
          $('#exampleModal11').modal('show');
        }
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
      }
    });
  }
});

// Add Survey
  $('#survey').on('submit', function(e) {

    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
      url: "{{ route('admin.survey.save') }}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,

      beforeSend: function() {
        $('.text-danger').text('');
      },

      success: function(response) 
      {
        if (!response.success) 
        {
          toastr.error(response.message)
        } 
        else 
        {
          toastr.success(response.message)
          $('exampleModal').modal('hide');
          location.reload();
        }
      },

      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
        $(".text-danger").text("");
        if (errors.name) {
          $(".error-name").text(errors.name[0]);
        }

        if (errors.type) {
          $(".error-type").text(errors.type[0]);
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

        if (errors.surveyor) {
          $(".error-surveyor").text(errors.surveyor[0]);
        }

        if (errors && errors.investigation) {
          $(".error-investigation").text(errors.investigation[0]);
        }

        if (errors.chiefinvestigation) {
          $(".error-chiefinvestigation").text(errors.chiefinvestigation[0]);
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

// Copy Survey
$(document).on('click', '.copySurvey', function(e) {
  var id = $(this).attr('id');
  var url = "{{ route('admin.survey.edit', ':id') }}";
  url = url.replace(':id', id);
  var typBefore = $("#form_type").val();

  if (id) {
    $.ajax({
      url: url,
      type: "GET",
      success: function(response) {

        if (response.success) {

          $('#edit-market-container').hide();
          $('#edit-category-container').hide();

          $('#names').val(response.data.name);
          $('#from_dates').val(response.data.start_date);
          $('#to_dates').val(response.data.end_date);
          $('#statuss').val(response.data.status).change();
          $('#zones').val(response.data.zone_id);
          $('#types').val(response.data.type[0]?.id);
          $('#chiefinvestigations').val(response.data.chiefofficer[0]?.id);
          

          let investigationIds = response.data.investigation_officer.map(officer => officer.id);

if (investigationIds.length > 0) {
    $('#investigationsOfficer').val(investigationIds).trigger('change');
} else {
    // Reset dropdown to first option
    $('#investigationsOfficer').val('').trigger('change');
}


          // Market Dropdown
          const marketEl = document.getElementById("markets");
          const marketWrapper = marketEl.nextElementSibling;
          if (marketWrapper && marketWrapper.classList.contains("multiselect-dropdown")) {
            marketWrapper.remove();
          }
          marketEl.removeAttribute("data-multiselect-init");

          // Append new options
          const marketDropdown = $('#markets');
          marketDropdown.empty();
          response.market.forEach(market => {
            marketDropdown.append(new Option(market.name, market.market_id));
          });
          $('#markets').val(response.data.markets.map(m => String(m.market_id)));

          // Reinitialize
          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(marketEl);

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();

            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }

          // --------------------------
          // Category Dropdown (FIXED)
          // --------------------------
          const categoryEl = document.getElementById("categorys");
          const categoryWrapper = categoryEl.nextElementSibling;
          if (categoryWrapper && categoryWrapper.classList.contains("multiselect-dropdown")) {
            categoryWrapper.remove();
          }
          categoryEl.removeAttribute("data-multiselect-init");

          const categoryDropdown = $('#categorys');
          categoryDropdown.empty();

          // Append all categories from response.typecategory
          response.typecategory.forEach(category => {
            categoryDropdown.append(new Option(category.name, category.id));
          });

          // Set selected categories from response.data.categories
          const selectedCategoryIds = response.data.categories.map(c => String(c.category_id));
          categoryDropdown.val(selectedCategoryIds);

          categoryDropdown.trigger('change');

          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(categoryEl);
         

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }
          

          // Compilance Officer Dropdown
          const compilancesids = response.data.surveyors.map(surveyor => String(surveyor.surveyor_id));
          $('#surveyors').val(compilancesids);


          const compilanceEl = document.getElementById("surveyors");
          const surveyorWrapper = compilanceEl.nextElementSibling;
          if (surveyorWrapper && surveyorWrapper.classList.contains("multiselect-dropdown")) {
            surveyorWrapper.remove(); // Remove the old dropdown
          }
          compilanceEl.removeAttribute("data-multiselect-init");


          if (typeof MultiselectDropdown !== 'undefined') {
            MultiselectDropdown(compilanceEl);

            // Optionally remove other dropdowns' multiselect wrappers if needed
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
          }

          $("#formHeading").text('Copy Survey')
          $(".btnrhrhtht").text('Copy Survey')
          $('.exampleModal11').modal('show');
        }
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
      }
    });
  }
})

// View Survey
$(document).on('click', '.viewSurvey', function(e) {
  var id = $(this).attr('id');
  var url = "{{ route('admin.survey.edit', ':id') }}";
  url = url.replace(':id', id);
  var typBefore = $("#form_type").val();

  if (id) {
    $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
    
        if (response.success) 
        {
          $('#namess').val(response.data.name);
          $('#from_datess').val(response.data.start_date);
          $('#to_datess').val(response.data.end_date);
          $('#statusss').val(response.data.status).change();
          $('#zoness').val(response.data.zone_id).change();
          $('#typess').val(response.data.type[0]?.id);
          $('#chiefinvestigationss').val(response.data.chiefofficer[0]?.id);

          let investigationIds = response.data.investigation_officer.map(officer => officer.id);

if (investigationIds.length > 0) {
    $('#investigationss').val(investigationIds).trigger('change');
} else {
    $('#investigationss').val('').trigger('change');
}


          // Store Dropdown
          if (response.data && Array.isArray(response.market)) 
          {
            $("#zone").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();

            $("#view-compilances").next(".multiselect-dropdown").remove();
            $("#view-category").next(".multiselect-dropdown").remove();

            $("#edit-category").next(".multiselect-dropdown").remove();
            $("#edit-market").next(".multiselect-dropdown").remove();

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            const $newSelect = $('<select>', {
              id: 'view-market',
              name: 'market[]',
              class: 'multiselect-dropdown',
              multiple: true,
            });

            response.market.forEach(item => {
              $newSelect.append(new Option(item.name, item.id, true,true));
            });

            $("#view-market-container").html(`
              <label>Store</label>
              <div id="view-market-wrapper"></div>
              <span class="text-danger error-market"></span>
            `);

            $("#view-market-wrapper").html($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') 
            {
              MultiselectDropdown(document.getElementById("view-market"));
            }
          } 

          // Category Dropdown
          if (response.data && Array.isArray(response.category)) 
          {
            $("#zone").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();

            $("#view-category").next(".multiselect-dropdown").remove();
            $("#view-category").remove();

            $("#view-market").next(".multiselect-dropdown").remove();
            $("#view-compilances").next(".multiselect-dropdown").remove();

            $("#edit-category").next(".multiselect-dropdown").remove();
            $("#edit-market").next(".multiselect-dropdown").remove();

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            const $newSelect = $('<select>', {
              id: 'view-category',
              name: 'category[]',
              class: 'multiselect-dropdown',
              multiple: true,
            });

            response.category.forEach(item => {
              $newSelect.append(new Option(item.name, item.id, true,true));
            });

            $("#view-category-container").html(`
              <label>Category</label>
              <div id="view-category-wrapper"></div>
            `);

            $("#view-category-wrapper").html($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') 
            {
              MultiselectDropdown(document.getElementById("view-category"));
            }
          } 

          // Compilances Dropdown
          if (response.data && Array.isArray(response.compilances)) 
          {
            $("#zone").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();

            $("#view-compilances").next(".multiselect-dropdown").remove();
            $("#view-compilances").remove();

            $("#view-market").next(".multiselect-dropdown").remove();
            $("#view-category").next(".multiselect-dropdown").remove();

            $("#edit-category").next(".multiselect-dropdown").remove();
            $("#edit-market").next(".multiselect-dropdown").remove();

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            const $newSelect = $('<select>', {
              id: 'view-compilances',
              name: 'category[]',
              class: 'multiselect-dropdown',
              multiple: true,
            });

            response.compilances.forEach(item => {
              const fullName = item.title ? `${item.title} ${item.name}` : item.name;
              const option = new Option(fullName, item.id, true, true);
              option.title = fullName;
              $newSelect.append(option);
            });



            $("#view-compilances-container").html(`
              <label>Compliance Officer</label>
              <div id="view-compilances-wrapper"></div>
            `);

            $("#view-compilances-wrapper").html($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') 
            {
              MultiselectDropdown(document.getElementById("view-category"));
            }
          } 

          $('#compilances').val(response.data.surveyors.map(surveyors => surveyors.surveyor_id)).change();

          $('.viewModal').modal('show');
        }
      },
      error: function(xhr) 
      {
        let errors = xhr.responseJSON.errors;
      }
    });
  }
})

// Update Survey
$(document).ready(function() {

  $('#editSurvey').on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    $('.loader').show();
    $('.screen-block').show();

    let url = "{{ route('admin.survey.update') }}";

    $.ajax({
      url: url,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $('.text-danger').text('');
      },

      success: function(response) {
        if (!response.success) {
          toastr.error(response.message)
        } else {
          toastr.success(response.message)
          modal.hide();
          location.reload();
        }
      },
      error: function(xhr) {

        let errors = xhr.responseJSON.errors;
        $(".text-danger").text("");

        if (errors.name) {
          $(".error-name").text(errors.name[0]);
        }

        if (errors.type) {
          $(".error-type").text(errors.type[0]);
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

        if (errors.surveyor) {
          $(".error-surveyor").text(errors.surveyor[0]);
        }

        if (errors.investigation) {
          $(".error-investigation").text(errors.investigation[0]);
        }

        if (errors.chiefinvestigation) {
          $(".error-chiefinvestigation").text(errors.chiefinvestigation[0]);
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

      },
      complete: function() {
        // Hide loader and unblock screen
        $('.loader').hide();
        $('.screen-block').hide();
      }

    });

  });

  // Start of survey type
  $("#type").on("change", function() {
    
    let typeIds = $(this).val();

    if (typeIds && typeIds.length > 0) {

      $.ajax({
        url: "{{ route('admin.get.type.category') }}",
        type: "POST",
        data: {id: typeIds,_token: '{{ csrf_token() }}'},
        success: function(response) {
          if (response.success && Array.isArray(response.data)) {
            
            $('#category_hide').hide();
            $("#category").next(".multiselect-dropdown").remove(); 
            $("#category").remove(); 

            $("#zone").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();

            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();
            
            // const $label = $('<label>', {
            //   for: 'edit-category',
            //   text: 'Category' // Change this text as needed
            // });
            const $label = $('<label>').html('Category <span class="text-danger">*</span>');

            const $newSelect = $('<select>', {
              id: 'category',
              name: 'category[]',
              class: 'multiselect-dropdown',
              multiple: true,
              'multiselect-search': 'true',
              'multiselect-select-all': 'true'
            });

            response.data.forEach(item => {
              $newSelect.append(new Option(item.name, item.id));
            });

            const $errorSpan = $('<span>', { class: 'text-danger error-category' });
            $("#category-container")
              .empty() // optional, if you want to clear previous content
              .append($label)
              .append($newSelect)
              .append($errorSpan);


            // $("#category-container").append($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') {
              MultiselectDropdown(document.getElementById("category"));
            }
          }
        },
        error: function(xhr) {
          console.error("Error:", xhr.responseText);
        }
      });
    }
  });
  //End of survey type

  // Start of edit survey type
  $(document).on("change", "#types", function() {
  let typeIds = $(this).val();

    if (typeIds && typeIds.length > 0) {
      $.ajax({
        url: "{{ route('admin.get.type.category') }}",
        type: "POST",
        data: { id: typeIds, _token: '{{ csrf_token() }}' },
        success: function(response) {
          if (response.success && Array.isArray(response.data)) {

            $('#edit-category-container').show();
            $('#hide-category').hide();

            $('#edit_category_hide').hide();
            $("#edit-category").next(".multiselect-dropdown").remove(); 
            $("#edit-category").remove();
            $("#categorys").remove();

            // Clean all potential duplicates
            $("#edit-category").next(".multiselect-dropdown").remove();
            $("#edit-market").next(".multiselect-dropdown").remove();
            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();
            $("#zones").next(".multiselect-dropdown").remove();
            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            const $newSelect = $('<select>', {
              id: 'edit-category',
              name: 'category[]',
              class: 'multiselect-dropdown',
              multiple: true,
              'multiselect-search': 'true',
              'multiselect-select-all': 'true'
            });

            response.data.forEach(item => {
              $newSelect.append(new Option(item.name, item.id));
            });

            $("#edit-category-container").append($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') {
              MultiselectDropdown(document.getElementById("edit-category"));
            }
          }
        },
        error: function(xhr) {
          console.error("Error:", xhr.responseText);
        }
      });
    }
  });

  // End of edit survey type

  // Start of survey zone
  $("#zone").on("change", function() {
    let zoneIds = $(this).val();

    if (zoneIds && zoneIds.length > 0) 
    {
      $.ajax({
        url: "{{ route('admin.get.zones.market') }}",
        type: "POST",
        data: {
          id: zoneIds,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success && Array.isArray(response.data)) {
            
            $('#market_hide').hide();
            $("#market").next(".multiselect-dropdown").remove(); 
            $("#market").remove(); 

            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();

            $("#zones").next(".multiselect-dropdown").remove();
            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();
           
            const $newSelect = $('<select>', {
              id: 'market',
              name: 'market[]',
              class: 'multiselect-dropdown',
              multiple: true,
              placeholder: 'Select',
              'multiselect-search': 'true',
              'multiselect-select-all': 'true',
            });

            response.data.forEach(item => {
              $newSelect.append(new Option(item.name, item.id));
            });

            $("#market-container").append($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') {
              MultiselectDropdown(document.getElementById("market"));
            }
          }
        },
        error: function(xhr) {
          console.error("Error:", xhr.responseText);
        }
      });
    }
    else
    {
    $('#market-container').empty().append(`
      <div class="form-group ad-user">
      <label for="market">Store <span class="text-danger">*</span></label>
      <div class="rela-icon">
      <select id="market" name="market" class="form-control">
      <option value="" selected disabled>Select</option>
      </select><i class="fa-solid fa-caret-down"></i><span class="text-danger error-market"></span></div></div>`);
    }
  });
  // End of survey zone

  // Start of edit survey zone
  $('#zones').off('change').on('change', function() {
    let zoneIds = $(this).val();

    if (zoneIds && zoneIds.length > 0) {
      $.ajax({
        url: "{{ route('admin.get.zones.market') }}",
        type: "POST",
        data: {
          id: zoneIds,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          if (response.success && Array.isArray(response.data)) {
            
            $('#edit-market-container').show();
            $('#hide-market').hide();

             $("#markets").remove(); 

            $("#edit-market").next(".multiselect-dropdown").remove();
            $("#edit-market").remove();

            $("#edit-category").next(".multiselect-dropdown").remove();
            $("#edit-market").next(".multiselect-dropdown").remove();

            $("#zone").next(".multiselect-dropdown").remove();
            $("#category").next(".multiselect-dropdown").remove();
            $("#surveyor").next(".multiselect-dropdown").remove();
            $("#market").next(".multiselect-dropdown").remove();

            $("#zones").next(".multiselect-dropdown").remove();
            $('#surveyors').next(".multiselect-dropdown").remove();
            $('#categorys').next(".multiselect-dropdown").remove();
            $('#markets').next(".multiselect-dropdown").remove();

            const $newSelect = $('<select>', {
              id: 'edit-market',
              name: 'market[]',
              class: 'multiselect-dropdown',
              multiple: true,
              'multiselect-search': 'true',
              'multiselect-select-all': 'true',
            });

            response.data.forEach(item => {
              $newSelect.append(new Option(item.name, item.id));
            });

            $("#edit-market-container").append($newSelect);

            if (typeof MultiselectDropdown !== 'undefined') {
              MultiselectDropdown(document.getElementById("edit-market"));
            }
          }
        },
        error: function(xhr) {
          console.error("Error:", xhr.responseText);
        }
      });
    }
  });
  // End of survey zone


  // Start of Store List 
  $("#filterZone").on('change', function() {

    var id = $(this).val();

    if (id) 
    {
      $.ajax({
        url: "{{ route('admin.get.zones.market') }}",
        type: "POST",
        data: {id:id, _token: '{{ csrf_token() }}'},

        success: function(response) 
        {
          if (response.success) 
          {
            let data = response.data;
            let dropdown = $('#filterMarket');
            dropdown.empty();
            dropdown.append('<option value="">Select Store</option>');
            response.data.forEach(function(item) 
            {
              dropdown.append(
                `<option value="${item.id}">${item.name}</option>`
              );
            });
          }
        },

        error: function(xhr) {
          let errors = xhr.responseJSON.errors;
        }
      });
    } 
    else {
      let dropdown = $('#market');
      dropdown.append('<option value="all">Select Store</option>');
    }
  })
  // End of store list

  $("#category").on('change', function() {

    var id = $(this).val();
    var url = "{{ route('admin.get.category.product', ':id') }}";
    url = url.replace(':id', id);

    if (id) {
      $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
          if (response.success) {
            let data = response.data;
            let dropdown = $('#product');
            dropdown.empty();
            dropdown.append('<option value="">Select Product</option>');
            response.data.forEach(function(item) {
              dropdown.append(
                `<option value="${item.id}">${item.name}</option>`
              );
            });
          }
        },

        error: function(xhr) {
          let errors = xhr.responseJSON.errors;
        }
      });

    } else {
      let dropdown = $('#product');
      dropdown.append('<option value="">Select Produc</option>');
    }
  })

  var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {

    backdrop: 'static',

    keyboard: false

  });


  $(".toggleSwitch").on("change", function() {

    var status = $(this).is(":checked") ? 1 : 0;

    var id = $(this).val();



    $.ajax({

      url: "{{ route('admin.survey.update.status') }}",

      type: "POST",

      data: {
        _token: "{{ csrf_token() }}",
        status: status,
        id: id
      },

      success: function(response) {

        console.log(response);

        if (response.success) {

          toastr.success(response.message)

        }

      },

      error: function(xhr, status, error) {

        toastr.success(response.message)

      }

    });

  });



  // File input change event

  $('#fileInput').on('change', function(event) {

    const file = event.target.files[0];

    $('#fileName').text(file ? file.name : 'Upload Image');

  });



  function setupDropdown(dropdownButtonId) {

    const $dropdownButton = $('#' + dropdownButtonId);

    const $dropdownMenu = $dropdownButton.next();

    const $dropdownItems = $dropdownMenu.find('.dropdown-item');



    // Toggle dropdown visibility

    $dropdownButton.on('click', function() {

      $dropdownMenu.toggle();

    });



    // Update dropdown button text on item click

    $dropdownItems.on('click', function() {

      const selectedValue = $(this).data('value');

      $dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');

      $dropdownMenu.hide();

    });



    // Close dropdown when clicking outside

    $(document).on('click', function(e) {

      if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e
          .target).length === 0) {

        $dropdownMenu.hide();

      }

    });

  }

  // Toggle dropdown visibility
  $dropdownButton.on('click', function() {
    $dropdownMenu.toggle();
  });


  // Close dropdown when clicking outside
  $(document).on('click', function(e) {

    if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target)
      .length === 0) {

      $dropdownMenu.hide();
    }

  });

  // Update dropdown button text based on selected items
  $checkboxes.on('change', function() {

    const selectedOptions = $checkboxes.filter(':checked').map(function() {

      return $(this).val();

    }).get();

    $dropdownButton.html(

      selectedOptions.length > 0 ? selectedOptions.join(', ') +
      ' <i class="fa fa-caret-down"></i>'
      :
      'Select Options <i class="fa fa-caret-down"></i>'

    );

  });

});

function toggleDropdown() {
  var dropdown = document.getElementById("dropdown");
  dropdown.classList.toggle("active");
}




window.onload = function() 
{
  let params = new URLSearchParams(window.location.search);
  if (params.has('name') || params.has('survey_id') || params.has('zone') || params.has('category') || params.has(
      'surveyor') || params.has('start_date') || params.has('end_date') || params.has('status') || params.has(
      'market')) {

    let dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
  }
};

$('#exampleModal').on('hidden.bs.modal', function() {
  document.body.classList.remove('modal-open');
  $('.modal-backdrop').remove();
}); 

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
    var officers = $(this).data('officers');

    var listHtml = '';
    officers.forEach(function(item) {
        let displayName = (item.title ? item.title + ' ' : '') + item.name;
        listHtml += '<li>' + displayName + '</li>';
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