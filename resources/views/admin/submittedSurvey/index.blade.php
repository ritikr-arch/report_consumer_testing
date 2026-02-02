@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<?php 
use App\Models\SubmittedSurvey;
?>
<div class="px-3">

   <!-- Start Content-->

   <div class="container-fluid">

      <div class="row mt-3">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <div class="row align-items-center d-flex mb-2">
                     <div class="col-xl-5">
                        <h4 class="header-title mb-0 font-weight-bold">
                           Submitted Survey
                        </h4>
                     </div>


                     <div class="col-12 col-md-7 col-lg-7">
                        <div class="search-btn1 text-end">
                           <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()" data-toggle="tooltip" data-placement="top" title="Filter"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                           @can('submit_survey_create')
                           <a class="btn btn-secondary btn-sm" href="{{route('admin.submitted.survey.export', request()->query())}}" data-toggle="tooltip" data-placement="top" title="Export"><i class="fas fa-file-download"></i> Export</a>
                           <a class="btn btn-secondary btn-sm" href="{{route('admin.import.survey', request()->query())}}" data-toggle="tooltip" data-placement="top" title="Import Survey"><i class="fas fa-file-download"></i> Import Survey</a>
                           @endcan
                        </div>
                     </div>
                  </div>

                    
                  <!-- </div> -->

                     <div class="row mb-2">
                        <form action="{{route('admin.submitted.survey.filter')}}" method="get">
                           <hr>
                           <div id="dropdown" class="dropdown-container-filter fil-comm">
                              <div class="name-input">
                                 <input type="text" class="form-control" name="survey_number" id="survey_number" placeholder="Survey Number" value="{{ request('survey_number') }}">
                              </div>
                              <div class="name-input">
                                 <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Survey Title" value="{{ request('name') }}">
                              </div>
                              <select class="form-select" name="zone" aria-label="Default select example">
                                 <option value="" selected="">Zone</option>
                                 @if(isset($zone) && count($zone)>0)
                                 @foreach($zone as $catKey=>$zoneValue)

                                   <option {{ request('zone') == $zoneValue->id ? 'selected' : '' }} value="{{$zoneValue->id}}">{{ucfirst($zoneValue->name)}}</option>

                                 @endforeach

                                 @endif

                              </select>

                              <select class="form-select" name="status" aria-label="Default select example">

                                 <option value="" selected="">Status</option>

                                 <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Approved</option>

                                 <option {{ request('status') === '0' ? 'selected' : '' }} value="0">In Progress</option>

                                 <option {{ request('status') === '2' ? 'selected' : '' }} value="2">Overdue</option>
                               
                              </select>

                               <select class="form-select" name="is_publish" aria-label="Default select example">

                                 <option value="" selected="">Is Publish</option>

                                 <option {{ request('is_publish') === '1' ? 'selected' : '' }} value="1">Yes</option>

                                 <option {{ request('is_publish') === '0' ? 'selected' : '' }} value="0">No</option>
                               
                              </select>

                              <!-- <select class="form-select" name="publish" aria-label="Default select example">

                                 <option value="" selected="">Is Publish</option>

                                 <option {{ request('publish') === '1' ? 'selected' : '' }} value="1">Yes</option>

                                 <option {{ request('publish') === '0' ? 'selected' : '' }} value="0">No</option>

                              </select> -->

                              <div class="filter-date">

                               <!--   <label for="start-date">Start Date</label> -->

                                 <input type="text" value="{{ request('start_date') }}" autocomplete="off" name="start_date" class="form-control" placeholder="Start Date" id="start_date">

                              </div>

                              <div class="filter-date">

                                <!--  <label for="end-date">End Date</label> -->

                                 <input  type="text" value="{{ request('end_date') }}" autocomplete="off" name="end_date" class="form-control" placeholder="End Date" id="end_date">

                              </div>

                              <button type="submit" class="d-flex searc-btn" >Search</button>

                              <a type="button" class="btn btn-secondary btn-sm" href="{{ route('admin.submitted.survey.list') }}" >Reset</a>

                              <!-- <button type="reset" class="btn btn-secondary btn-sm">Reset</button> -->

                           </div>

                        </form>

                     </div>
                    @can('submit_survey_edit')
                      <div class="text-end mb-4">
                        <button class="btn btn-primary" type="button" id="publishButton" data-toggle="tooltip" data-placement="top" title="Publish">Publish</button>
                     </div>
                     @endcan

                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>

                           <tr class="border-b bg-light2">

                              <th style="min-width: 20px;" scope="row">Survey ID</th>

                              <th style="min-width: 230px;">Survey Title</th>

                              <th style="min-width: 110px;">Zone</th>

                              <th style="min-width: 160px;">Last Collected On</th>

                              {{-- <th style="min-width: 120px;">Collected By</th> --}}
                               @can('submit_survey_edit')
                              <th style="min-width: 80px;">Approved</th>

                              <th style="min-width: 150px;">
                                 <input type="checkbox" name="" id="selectedAll" class="" value="1">
                                 Published
                              </th>
                               @endcan
                              <th style="min-width: 80px;">Action</th>

                           </tr>

                        </thead>

                        <tbody>
                           <?php 
                           $today = date('Y-m-d');
                           ?>
                           @if(isset($data) && count($data)>0)

                           @foreach($data as $key=>$value)

                              <tr>

                                 <td>#{{($value->survey)?$value->survey->survey_id:''}}</td>

                                 <td>
                                    {{($value->survey)?$value->survey->name:''}}<br>
                                       @if($value->survey)
                                          @if($value->survey->is_complete == 1)
                                             <span style="background: #31cb72; color: #fff;padding: 1px 8px;border-radius: 10%;font-size: 12;">Approved</span>
                                          @elseif(($value->survey->is_complete == 0) && $value->survey->end_date<$today)
                                             <span style="background: #f15149; color: #fff;padding: 1px 8px;border-radius: 10%; font-size: 12px;">Overdue</span>
                                          @else
                                             <span style="background: #f1c907; color: #fff;padding: 1px 8px;border-radius: 10%; font-size: 12px;">In Progress</span>
                                          @endif
                                       @endif
                                 </td>

                                 <td>{{($value->zone)?$value->zone->name:''}}</td>

                                 <td>{{ customt_date_format($value->created_at) }}</td>


                                 {{-- <td>{{($value->submitter)?$value->submitter->name:''}}</td>
                                 
                                 <td class="active-bt">
                                     <a href="javascript:void(0)" class="{{(($value->survey)?$value->survey->is_complete != 1:'')?'submittedSurveyStatus':''}}" data-id="{{$value->survey_id}}"
                                         title="Status">
                                         <?php
                                         if($value->survey){
                                          if($value->survey->is_complete == 1){
                                           echo 'Approved';
                                          }
                                          else if(($value->survey->is_complete == 0) && $value->survey->end_date<$today){
                                           echo 'Overdue';
                                          }else{
                                           echo 'In Progress';
                                          }
                                         }
                                         ?>
                                         
                                     </a>
                                 </td> --}}
                                  @can('submit_survey_edit')
                                 <td>
                                 <?php 
                                    $toolTip = (@$value->survey->is_approve == '1')?'This Survey is already approved':'Do you want to approve this survey?';
                                 ?>

                                   <a href="javascript:void(0)" data-id="{{@$value->survey->id}}" class="{{ (@$value->survey->is_approve=='0')?'approveSurvey':''}}" data-toggle="tooltip" data-placement="top" title="{{$toolTip}}">{{(@$value->survey->is_approve == '1')?'Yes':'No'}}</a>
                                    
                                 </td>
                                 <td>
                                    @if($value->survey)
                                          @php 
                                          $checkPublishSurvey = SubmittedSurvey::where(['survey_id'=>@$value->survey->id, 'publish'=>'0'])->get();
                                          $toolTip = (@$value->survey->is_approve == '1')?'Do you want to publish this survey?':"You can't publish this survey until the survey is approved.";
                                          @endphp

                                          @if(isset($checkPublishSurvey) && count($checkPublishSurvey)>0)
                                             <a href="javascript:void(0)" data-id="{{@$value->survey->id}}" data-toggle="tooltip" data-placement="top" title="{{$toolTip}}">
                                                @if(@$value->survey->is_approve == '1')
                                                <input type="checkbox" name="selected[]" value="{{@$value->survey->id}}">
                                                @endif
                                                <span data-id="{{@$value->survey->id}}"  class="{{($value->survey->is_approve == '1')?'singlePublish':''}}">No</span>
                                             </a>
                                             {{-- <a href="javascript:void(0)" data-id="{{@$value->survey->id}}" data-toggle="tooltip" data-placement="top" class="{{ (@$value->survey->is_approve=='1')?'publish':''}}" title="{{$toolTip}}">
                                                @if(@$value->survey->is_approve == '1')
                                                <input type="checkbox" name="selected[]" value="{{@$value->survey->id}}">
                                                @endif
                                                No
                                             </a> --}}
                                          @else
                                          <button type="button" class="btn btn-secondary btn-sm unpublish-btn" data-toggle="tooltip" data-placement="top" data-id="{{$value->survey->id}}" title="Do you want to unpublish this survey?">Unpublish</button>
                                             <!-- <a href="javascript:void(0)" data-id="{{@$value->survey->id}}" data-bs-toggle="tooltip" title="This survey is already published">Yes</a> -->
                                          @endif
                                    @endif
                                 </td>
                                   @endcan
                                 <td>
                                     <div class="action-btn d-flex me-1">
                                          <a href="{{ route('admin.submitted.survey.details', $value->survey_id) }}" data-toggle="tooltip" data-placement="top" title="View">
                                             <img src="{{ asset('admin/img/view-eye.png') }}" alt="View" class="view-icon">
                                          </a>
                                          <a href="{{ route('admin.survey.report.preview', $value->survey_id) }}" data-toggle="tooltip" data-placement="top" title="Preview">
                                             <img src="{{ asset('admin/img/preview.png') }}" alt="Preview" class="view-icon">
                                          </a>

                                          <a href="{{ route('admin.price.log', $value->survey_id) }}" data-toggle="tooltip" data-placement="top" title="Log">
                                             <img src="{{ asset('admin/img/preview.png') }}" alt="Log" class="view-icon">
                                          </a>
                                     </div>
                                     
                                 </td>


                                 {{-- <td>
                                    <div class="action-btn">
                                       <a href="{{route('admin.submitted.survey.details', $value->survey_id)}}" data-toggle="tooltip" data-placement="top" title="View"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a>
                                    </div>
                                    <div class="action-btn">
                                       <a href="{{route('admin.survey.report.preview', $value->survey_id)}}" data-toggle="tooltip" data-placement="top" title="Preview"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a>
                                    </div>
                                 </td> --}}
                              </tr>

                           @endforeach
                           @else
                           <tr>
                              <td colspan="5">No records found</td>
                           </tr>
                           @endif
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

@endsection



@push('scripts')

<script>
   document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });

   $(document).on('click', '.singlePublish', function(){
      let surveyId = $(this).data('id');

      Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to publish this survey?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, publish it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.single.survey.publish') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    survey_id: surveyId
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Published!', 'The survey has been published.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', 'Failed to publish the survey.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'An error occurred.', 'error');
                }
            });
        }
    });
   })

   $(document).on('click', '.unpublish-btn', function () {
    let surveyId = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to unpublish this survey?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, unpublish it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.survey.unpublish') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    survey_id: surveyId
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire('Unpublished!', 'The survey has been unpublished.', 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error!', 'Failed to unpublish the survey.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'An error occurred.', 'error');
                }
            });
        }
    });
});


   $(document).ready(function(){

      $("#selectedAll").on('click', function() {
          let isChecked = $(this).prop('checked');
          $('input[name="selected[]"]').prop('checked', isChecked);
      });

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

   });



   function toggleDropdown() {

       var dropdown = document.getElementById("dropdown");

       dropdown.classList.toggle("active");

   }



   window.onload = function () {

      let params = new URLSearchParams(window.location.search);

         if (params.has('name') || params.has('survey_number') || params.has('zone') || params.has('publish') || params.has('start_date') || params.has('end_date') || params.has('status')) {

         let dropdown = document.getElementById("dropdown");

         dropdown.classList.toggle("active");

      }

   };

</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

 <script>
   $( function() {
      $( "#start_date").datepicker();
      $( "#end_date").datepicker();
   });
  </script>
@endpush