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
</style>

<div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-5">
                <h4 class="header-title mb-0 font-weight-bold"> Audit Logs </h4>
              </div>
              {{-- <div class="col-12 col-md-7 col-lg-7">
                <div class="search-btn1 text-end">
                  <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                    <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>
                </div>
              </div> --}}
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
                    <option value="" selected disabled>Select Compliance Officer</option> @if(isset($surveyor) && count($surveyor)>0) @foreach($surveyor as $sKey=>$sValue) <option {{ request('surveyor') == $sValue->id ? 'selected' : '' }} value="{{$sValue->id}}">{{ucfirst($sValue->name)}}</option> @endforeach @endif
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
                    <th style="min-width:120px;">Survey ID</th>
                    <th style="min-width:450px;">Survey Title</th>
                    <th style="min-width:100px;">Zone</th>
                    <th style="min-width:200px;">Chief Investigation Officer</th>
                    <th style="min-width:50px;">Action</th>
                  </tr>
                </thead>
                <tbody> 
                @if(isset($data) && count($data)>0) 
                  @foreach($data as $key=>$value) 
                  <?php 
                  $countt = count($value->surveyLog);
                  ?>
                  <tr>
                    <td>#{{$value->survey_id}}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{($value->zone) ? ucfirst($value->zone->name):'' }}</td>
                    <td>
                    @php
                      $user = $value->surveyLog[$countt - 1]->user ?? null;
                    @endphp

                    @if($user)
                      {{ $user->title ? ucfirst($user->title) . ' ' : '' }}{{ ucfirst($user->name) }}
                    @else
                      N/A
                    @endif
                  </td>

                    <td>
                      <div class="action-btn">
                        <a href="{{ route('admin.audit.log.details', $value->id) }}">
                          <img  data-toggle="tooltip" data-placement="top" title="View" src="{{asset('admin/img/view-eye.png')}}" id="{{$value->id}}" alt="view" class="view-icon viewSurvey">
                        </a>
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
  <div class="modal-dialog">
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

@endsection
@push('scripts')
<script>
 
$(document).ready(function() {

  function toggleDropdown() {
    var dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
  }

  window.onload = function() {
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