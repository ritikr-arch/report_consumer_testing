@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<?php 
use App\Models\SubmittedSurvey;
$surveyName = ($survey->surveyType)?$survey->surveyType->name:'';
$type = strtolower($surveyName);
// if(strtolower($surveyName) == 'medication'){
//     $type = 'medication';
// }else{
//     $type = $surveyName;
// }
// dd($type);
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
                           Price Update Log
                        </h4>
                        <strong>{{ucfirst($survey->name)}}</strong>
                     </div>


                     {{-- <div class="col-12 col-md-7 col-lg-7">
                        <div class="search-btn1 text-end">
                           <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()" data-toggle="tooltip" data-placement="top" title="Filter"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                           @can('submit_survey_create')
                           <a class="btn btn-secondary btn-sm" href="{{route('admin.submitted.survey.export', request()->query())}}" data-toggle="tooltip" data-placement="top" title="Export"><i class="fas fa-file-download"></i> Export</a>
                           <a class="btn btn-secondary btn-sm" href="{{route('admin.import.survey', request()->query())}}" data-toggle="tooltip" data-placement="top" title="Import Survey"><i class="fas fa-file-download"></i> Import Survey</a>
                           @endcan
                        </div>
                     </div> --}}
                  </div>
                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>
                           <tr class="border-b bg-light2">
                              <th style="min-width: 20px;" scope="row">Sr.No</th>
                              <th style="min-width: 100px;">Category</th>
                              <th style="min-width: 100px;">Market</th>
                              <th style="min-width: 100px;">Commodity</th>
                              <th style="min-width: 100px;">Brand</th>
                              <th style="min-width: 100px;">Unit</th>
                              <th style="min-width: 100px;">Old Amount {{($type == 'medication')?'Generic':''}}</th>
                              <th style="min-width: 100px;">Updated Amount {{($type == 'medication')?'Generic':''}}</th>
                              @if($type == 'medication')
                              <th style="min-width: 100px;">Old Amount (Original)</th>
                              <th style="min-width: 100px;">Updated Amount (original)</th>
                              @endif
                              <th style="min-width: 100px;">Updated By</th>
                              <th style="min-width: 100px;">Updated On</th>
                           </tr>
                        </thead>

                        <tbody>
                           <?php 
                           $today = date('Y-m-d');
                           ?>
                           @if(isset($data) && count($data)>0)
                              @foreach($data as $key=>$value)
                                <?php 
                                $roles = ($value->updatedBy->getRoleNames()->first());
                                $words = explode(" ", $roles);
                                $role = '';
                                foreach ($words as $word) {
                                    $role .= strtoupper($word[0]);
                                }
                                ?>
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value->submittedSurvey->category->name}}</td>
                                    <td>{{$value->submittedSurvey->market->name}}</td>
                                    <td>{{$value->submittedSurvey->commodity->name}}</td>
                                    <td>{{$value->submittedSurvey->brand->name}}</td>
                                    <td>{{$value->submittedSurvey->unit->name}}</td>
                                    <td>{{($value->old_amount)?"$$value->old_amount":'0'}}</td>
                                    <td>{{($value->new_amount)?"$$value->new_amount":'0'}}</td> 
                                    @if($type == 'medication')
                                        <td>{{($value->old_amount_1)?"$$value->old_amount_1":'0'}}</td>
                                        <td>{{($value->new_amount_1)?"$$value->new_amount_1":''}}</td>
                                    @endif
                                    <td>{{$value->updatedBy->name}} <span>{{$role}}</span></td>
                                    <td>{{ date('d-m-Y h:i:s', strtotime($value->updated_at)) }}</td>
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