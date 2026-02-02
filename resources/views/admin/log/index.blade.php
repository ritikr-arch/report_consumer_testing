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
                    <th style="min-width:120px;">User</th>
                    <th style="min-width:120px;">Action</th>
                    <th style="min-width:20px;">Entity</th>
                    <th style="min-width:90px;">Entity ID</th>
                    <th style="min-width:150px;">Changes</th>
                    <th style="min-width:150px;">Date & Time</th>
                  </tr>
                </thead>
                <tbody> 
                @if(isset($data) && count($data)>0) 
                    @foreach ($data as $activity)
                      @php
                          $count = count($activity->properties);
                          $jsonString = json_encode($activity->properties, JSON_PRETTY_PRINT);
                          $short = Str::limit($jsonString, 100); // Limit to 100 characters
                          $id = 'activity-properties-' . $activity->id; // Unique ID for each row
                      @endphp
                      <tr>
                        <td>{{ $activity->causer->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($activity->event) }}</td>
                        <td>{{ class_basename($activity->subject_type) }}</td>
                        <td>{{ $activity->subject_id }}</td>
                        <td>
                          <div id="{{ $id }}-short">{{($count>0)?$short:''}} 
                              @if(strlen($jsonString) > 100)
                                  <a href="javascript:void(0);" onclick="toggleProperties('{{ $id }}')">Read More</a>
                              @endif
                          </div>
                          <div id="{{ $id }}-full" style="display: none;">
                              <pre>{{ $jsonString }}</pre>
                              <a href="javascript:void(0);" onclick="toggleProperties('{{ $id }}', false)">Read Less</a>
                          </div>
                        </td>
                        {{-- <td>{{ $activity->properties }}</td> --}}
                        <td>{{ date('d-m-Y H:i', strtotime($activity->created_at)) }}</td>
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
    function toggleProperties(id, expand = true) {
        const shortDiv = document.getElementById(id + '-short');
        const fullDiv = document.getElementById(id + '-full');
        if (expand) {
            shortDiv.style.display = 'none';
            fullDiv.style.display = 'block';
        } else {
            shortDiv.style.display = 'block';
            fullDiv.style.display = 'none';
        }
    }
</script>

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