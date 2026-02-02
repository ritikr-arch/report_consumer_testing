@extends('admin.layouts.app') 
@section('title', @$title) 
@section('content') 
<style>
/* Base table style */
table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

/* Header row styling */
thead th {
  background-color: #f5f5f5;
  font-weight: 600;
  font-size: 14px;
  padding: 12px 10px;
  text-align: left;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Body cells styling */
tbody td {
  padding: 12px 10px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  
}

/* Column widths */
/* You can set percentages or fixed widths */
/* Assuming 5 columns total for example */

thead th:nth-child(1),
tbody td:nth-child(1) {
  width: 7%;  /* less width */
}
thead th:nth-child(2),
tbody td:nth-child(2) {
  width: 16%;  /* less width */
}
thead th:nth-child(3),
tbody td:nth-child(3) {
  width: 17%;  /* less width */
}

thead th:nth-child(4),
tbody td:nth-child(4) {
  width: 20%;  /* more width for columns 2,3,4 */
}
thead th:nth-child(5),
tbody td:nth-child(5) {
  width: 10%;  /* more width for columns 2,3,4 */
}
thead th:nth-child(6),
tbody td:nth-child(6) {
  width: 10%;  /* more width for columns 2,3,4 */
}
thead th:nth-child(7),
tbody td:nth-child(7) {
  width: 16%;  /* more width for columns 2,3,4 */
}
thead th:nth-child(8),
tbody td:nth-child(8) {
  width: 10%;  /* less width */
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
                <h4 class="header-title mb-0 font-weight-bold"> Complaint List </h4>
              </div>
              <div class="col-12 col-md-7 col-lg-7">
                <div class="search-btn1 text-end">
                  <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                    <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>
                    @can('complaint_form_create')
                    <a href="{{ route('admin.complaint.form.index') }}" data-toggle="tooltip" data-placement="top" title="Create Compaint List">
                      <button class="d-fle btn btn-primary btn-sm" ><i class="fa-solid fa-plus"></i>&nbsp;Complaint</button>
                    </a>
                    @endcan
                </div>
              </div>
            </div>
            <div class="row mb-4">
              <form action="{{route('admin.complaint.form.filter')}}" method="get" id="complaintfilter">
                <hr>
                <div id="dropdown" class="dropdown-container-filter">
                  <div class="name-input">
                    <input type="text" class="form-control" name="complaint_id" id="exampleFormControlInput1" placeholder="Complaint ID" value="{{request('complaint_id')}}">
                    <div class="text text-danger">@error('complaint_id')<p>{{$message}}</p>@enderror</div>
                  </div>
                  <select class="form-select" name="status" aria-label="Default select example">
                    <option value="" selected disabled>Status</option>
                    <option {{ request('status') === '0' ? 'selected' : '' }} value="0">New </option>
                    <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Resolved </option>
                    <option {{ request('status') === '2' ? 'selected' : '' }} value="2">In Progress </option>
                    <option {{ request('status') === '3' ? 'selected' : '' }} value="3">Dismissed </option>
                    <option {{ request('status') === '4' ? 'selected' : '' }} value="4">Closed </option>
                  </select>
                  <div class="text text-danger">@error('status')<p>{{$message}}</p>@enderror</div>
                  <div class="filter-date">
                    {{-- <label for="start-date">Start Date</label> --}}
                    <input type="text" value="{{ request('start_date') }}" name="start_date" class="form-control" placeholder="Start Date" id="start_date" autocomplete="off">
                    <div class="text text-danger">@error('start_date')<p>{{$message}}</p>@enderror</div>
                  </div>
                  <div class="wid-16">
                  <div class="filter-date">
                    {{-- <label for="end-date">End Date</label> --}}
                    <input type="text" value="{{ request('end_date') }}" name="end_date" class="form-control" placeholder="End Date" id="end_date" autocomplete="off"><br>
                    
                  </div>
                  <div class="text text-danger mb-0">@error('end_date')<p>{{$message}}</p>@enderror</div>
                </div>

                  <button type="submit" class="d-flex searc-btn">Search</button>
                  <a href="{{ route('admin.complaint.form.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                </div>
              </form>
            </div>
            <div class="table-responsive white-space">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    
                    <th>S.No.</th>
                    <th>Complaint ID</th>
                    <th>Complaint Date</th>
                    <th>Recent updated On</th>
                    <th>Status</th>
                    <th>Name</th>
                    <!-- <th>Email</th> -->
                    @if($roles[0] == "Admin")
                    <th>Investigator</th>
                    @endif
                    @can('complaint_form_edit')
                    <th>Action</th>
                    @endcanany
                  </tr>
                </thead>
                <tbody> 
                    @if(isset($data) && count($data)>0) 
                    @foreach($data as $key=>$value) 
                        @php
                      $status = $complaintformstatuses->where('complaints_id', $value->id)->last();
                      
                  @endphp
                    <tr>
                    <td>{{$key+1}}</td>
                    <td>#{{ucfirst('CID'.$value->complaint_id)}}</td>
                   @php
                    $tdStyle = ($roles[0] == 'Admin') ? 'min-width: 130px;' : 'width: 130px;';
                @endphp
                <td>
                     {{ customt_date_format($value->created_at) }}
                    
                   
                    </td>
                     <td>
                  
                        {{ 
                            $status && $status->official_use_date 
                                ? customt_date_format($status->official_use_date) . ' ' . date('h:i A', strtotime($status->official_use_date)) 
                                : '-' 
                        }}


                    </td>
                     <td class="active-bt">
                      @if($value->status == '0')
                      <span class="badge bg-danger">New</span>
                      @elseif($value->status == '1')
                      <span class="badge bg-primary">Resolved</span>
                      @elseif($value->status == '2')
                      <span class="badge bg-warning">In Progress</span>
                      @elseif($value->status == '3')
                      <span class="badge bg-info">Dismissed</span>
                      @elseif($value->status == '4')
                      <span class="badge bg-success">Closed</span>
                      @endif
                    </td>
                    
                    <td>{{ucfirst($value->first_name).' '.ucfirst($value->last_name)}}</td>
                    <!-- <td>{{$value->email}}</td> -->
                   @if($roles[0] == "Admin")
<td>
                      

                        @if($value->investing_officer != "")
                           
                        <!-- <strong>Assigned To :</strong> -->
                         {{ $value->investing_officer }}
                            
                            <br>
                            @can('complaint_form_edit')
                            <a href="#" class="read-more" data-comment="{{ $value->remark }}" data-bs-toggle="modal" data-bs-target="#commentModal{{ $value->id }}">
                               Reassign Case
                            </a>
                            @endcan
                        @else
                            Not Assigned
                            <br>
                            @can('complaint_form_edit')
                            <a href="#" class="read-more" data-comment="{{ $value->remark }}" data-bs-toggle="modal" data-bs-target="#commentModal{{ $value->id }}">
                               Assign Case
                            </a>
                            @endcan
                        @endif

                        <!-- Modal -->
  <div class="modal fade" id="commentModal{{ $value->id }}" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="commentModalLabel">Assign Complaint</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="modalComment">
                                         <form class="complaint_form" data-id="{{ $value->id }}">

                                                @csrf  <div class="row">
            
            <div class="col-md-12">
              <div class="form-group ad-user p-2">
                <label>Investigator <span style="color:red;">*</span> </label>
                <div class="rela-icon">
                  <input type="hidden" id="id" value="{{ $value->id }}" name="id">
                  <select class="form-control" id="investing_officer" name="investing_officer" required>
                    <option value="" selected disabled>Select </option>
                    @if(isset($officer) && count($officer) > 0)
                      @foreach($officer as $values)
                          <option value="{{ $values->title }} {{ $values->name }}">
                             {{ $values->title }} {{ $values->name }} ({{ $values->getRoleNames()->implode(', ') }})
                          </option>
                      @endforeach
                  @endif

                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-investing_officer"></span>
                </div>
              </div>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-success btn-sm">Update</button>
            </div>
          </div>
        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </td>
                    @endif
                    @can('complaint_form_edit')
                    <td>
                  

                      <div class="action-btn">

                        
                       
                        <a href="{{route('admin.complaint.form.view', $value->id)}}" data-toggle="tooltip" data-placement="top" title="Click to Proceed">
                        <i class="fas fa-angle-double-right mr-2"></i>
                        </a> 
                     
                       

                        <!-- @can('complaint_form_edit')
                        @if($value->status == '0' || $value->status == '2') 
                        <button type="button" class="btn btn-info btn-sm editcomplain" id="{{$value->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title="Take Action"><i class="fas fa-edit mr-2"></i></button>
                        @endif
                        @endcan -->
                      </div>
                    </td>
                     @endcan
                  </tr> 
                  @endforeach 
                  @else 
                  <tr>
                    <td colspan="5">
                      <p class="no_data_found">No Data found! </p>
                    </td>
                  </tr> 
                  @endif 
                </tbody>
              </table>
               @if (isset($data)) {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
            </div>
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
<!-- END wrapper -->

@endsection 

@push('scripts') 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function () {
    $('.complaint_form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('admin.complaint.form.assigned') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => location.reload(), 1000);
                }
            }
        });
    });
});


</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
  function toggleDropdown() {
    var dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
}
$(function() {
    $("#start_date").datepicker();
    $("#end_date").datepicker();
});
</script> 
@endpush