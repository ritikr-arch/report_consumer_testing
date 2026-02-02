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
                <h4 class="header-title mb-0 font-weight-bold"> Audit Log Details </h4>
              </div>
              
            </div>
            <div class="row mb-3">
              <p>
                <strong>{{($survey->survey_id)?"#".$survey->survey_id:''}}</strong>
              </p>
            </div>
            <div class="table-responsive white-space md-4">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th style="min-width:250px;">Survey Status</th>
                    <th style="min-width:300px;">Update By</th>
                    <th style="min-width:100px;">Updated On</th>
                  </tr>
                </thead>
                <tbody> 
                @if(isset($data) && count($data)>0) 
                  @foreach($data as $key=>$value) 
                  <tr>
                    <td>{{ ucfirst($value->type)}}</td>
                    <td>
  {{ $value->user->title ? ucfirst($value->user->title) . ' ' : '' }}{{ ucfirst($value->user->name) }}
</td>

                    <td>{{ customt_date_format($value->created_at)}}</td>
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