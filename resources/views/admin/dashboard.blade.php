@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

       @canany(['dashboard_view'])


      <div class="px-3 pb-5">

         <!-- Start Content-->

         <div class="container-fluid">

            <div class="row">

               <div class="col-xl-8">

                  <div class="row graph-home-wrap mgt40">
                       <div class="row">
                    <div class="recent-data">
                       <h5><strong>Survey Data</strong></h5>
                    </div>
                    
                     <div class="col-lg-6 ">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/surveyor.png')}}" alt="dash-img" width="24px"> Created Surveys</h4>
                           <h2>{{$surveys}}</h2>

                        </div>

                     </div>

                     <div class="col-lg-6 pdr-0">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/clipboard-check.png')}}" alt="dash-img" width="24px"> Published Surveys</h4>

                           <h2>{{$completedSurvey}}</h2>

                        </div>

                     </div>

                     <div class="col-lg-6 ">
                        <div class="card-dash">
                           <h4><img src="{{asset('admin/images/evaluation.png')}}" alt="dash-img" width="24px">In Progress Surveys</h4>
                           <h2>{{$pendingSurvey}}</h2>
                        </div>
                     </div>

                     <div class="col-lg-6 pdr-0">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/deadline.png')}}" alt="dash-img" width="22px"> Overdue Surveys</h4>

                           <h2>{{$overdueSurvey}}</h2>

                        </div>

                     </div>
 </div>
                     <div class="row">
                        <div class="recent-data">
                           <h5><strong>Store & Commodity Data</strong></h5>
                        </div>
                        <div class="col-lg-6">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/store.png')}}" alt="dash-img" width="24px"> Total Stores</h4>
                              <h2>{{$markets}}</h2>
                           </div>
                        </div>
                        
                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/local-products.png')}}" alt="dash-img" width="24px"> Total Commodities</h4>
                              <h2>{{$commodities}}</h2>
                           </div>
                        </div>
                    </div>
                  {{-- </div>

                  <!-- end row-->
                  
                  <div class="row graph-home-wrap"> --}}
                    <div class="row">
                        <div class="recent-data">
                           <h5><strong>Complaint Data</strong></h5>
                        </div>
                        <div class="col-lg-6 ">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/report.png')}}" alt="dash-img" width="22px">  Total Complaints</h4>
                              <h2>{{$totalComplaints}}</h2>
                           </div>
                        </div>

                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/complain.png')}}" alt="dash-img" width="26px">  New Complaints</h4>
                              <h2>{{$newComplaints}}</h2>
                           </div>
                        </div>

                        <div class="col-lg-6 ">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/processing-time.png')}}" alt="dash-img" width="24px">  In Progress Complaints</h4>
                              <h2>{{$inprogressComplaints}}</h2>
                           </div>
                        </div>
                        
                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/resolved.png')}}" alt="dash-img" width="24px">  Resolved Complaints</h4>
                              <h2>{{$resolvedComplaints}}</h2>
                           </div>
                        </div>

                        <div class="col-lg-6">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/no.png')}}" alt="dash-img" width="22px"> Dismissed Complaints</h4>
                              <h2>{{$dismissedComplaints}}</h2>
                           </div>
                        </div>
                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/close.png')}}" alt="dash-img" width="22px"> Closed Complaints</h4>
                              <h2>{{$closedComplaints}}</h2>
                           </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="recent-data">
                           <h5><strong>Feedback & Contact us Data</strong></h5>
                        </div>
                        <div class="col-lg-6">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/review2.png')}}" alt="dash-img" width="24px"> Total Feedbacks</h4>
                              <h2>{{$feedback}}</h2>
                           </div>
                        </div>
                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/question.png')}}" alt="dash-img" width="24px">Total Contact Us</h4>
                              <h2>{{$enquiries}}</h2>
                           </div>
                        </div>
                    </div>
                                        
                    <div class="row">
                        <div class="recent-data">
                           <h5><strong>Officers Data</strong></h5>
                        </div>
                        <div class="col-lg-6">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/searching.png')}}" alt="dash-img" width="24px"> Total Chief Investigation Officers</h4>
                              <h2>{{$chiefinvestigationOfficers}}</h2>
                           </div>
                        </div>
                        <div class="col-lg-6 pdr-0">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/inspection.png')}}" alt="dash-img" width="24px"> Total Investigation Officers</h4>
                              <h2>{{$investigationOfficers}}</h2>
                           </div>
                        </div>

                        <div class="col-lg-6">
                           <div class="card-dash">
                              <h4><img src="{{asset('admin/images/compliance.png')}}" alt="dash-img" width="24px"> Total Compliance Officers</h4>
                              <h2>{{$complianceOfficers}}</h2>
                           </div>
                        </div>
                    </div>

               

                    
                  </div>

               </div>

               <!-- end col -->
            <!--  -->
               <div class="col-xl-4">
                  <div class="card-recent-data mgt40">
                     <div class="recent-data">
                        <h4>Recently Published Survey List</h4>
                        <img src="{{asset('admin/img/arrow-up-right.png')}}" alt="arrow">
                     </div>
                     @if(isset($publishSubmittedSurvey) && count($publishSubmittedSurvey)>0)
                     @foreach($publishSubmittedSurvey as $rKey=>$recentData)
                     <div class="recent-data-box">
                        <div class="rd-img">
                           <img src="{{ asset('admin/images/publish.png') }}" alt="arrow" style="height: 25px; width:25px;border-radius: 5%; ">
                           <span>
                              <a href="{{route('admin.submitted.survey.details', $recentData->id)}}">{{($recentData->name)?ucwords($recentData->name):''}}</a><br>
                              <span><b>{{ ($recentData->zone) ? $recentData->zone->name:'' }}</b></span>
                           </span>

                        </div>
                        <!-- <p>{{($recentData->zone)?$recentData->zone->name:''}}</p> -->
                    </div>
                    @endforeach
                    @endif

                  </div>
                  <div class="card-recent-data mt-3 pt-3">

                     <div class="recent-data">

                        <h4>Recently Collected Survey List</h4>
                        {{-- <h4>Recently Submitted Price Data</h4> --}}

                        <img src="{{asset('admin/img/arrow-up-right.png')}}" alt="arrow">

                     </div>

                    @if(isset($recentSubmittedSurvey) && count($recentSubmittedSurvey)>0)
                    @foreach($recentSubmittedSurvey as $rKey=>$recentData)
                    <div class="recent-data-box">
                        <div class="rd-img">
                           <?php 
                           $image = '';
                           if($recentData->commodity_image)
                           {
                              $image = '/submittedSurveyImage/'.$recentData->commodity_image;
                           }
                           else
                           {
                              $image = 'admin/img/rd1.png';                            
                           }  
                           ?>
                            <img src="{{asset($image)}}" alt="arrow" style="height: 35px; width:35px;border-radius: 5%; "> 
                           <span><a href="{{route('admin.submitted.survey.details', $recentData->survey->id)}}">{{($recentData->commodity)?ucwords($recentData->commodity->name):''}}</a></span>
                        </div>
                        <p>{{($recentData->market)?$recentData->market->name:''}}</p>
                    </div>
                    @endforeach
                    @endif
                     

                  </div>


               </div>


               <!-- end col-->

            </div>

            <!-- end row-->

         </div>

         <!-- container -->

      </div>
@else


   

        <div class="container-fluid min-vh-90 d-flex justify-content-center align-items-center">
    <div class="text-center">
        <div class="row recent-data">
           <!-- Image Section -->
         

          <!-- Text Section -->
         <div class="col-lg-12">
              <div class="dash-screen">
    @php
                  $image = 'admin/images/users/avatar-removed-bg.png';
              @endphp
              <img src="{{ asset($image) }}" alt="user-image" class="img-fluid">
    <h2>Welcome to Consumer Affairs Department Saint Kitts &amp; Nevis</h2></div>
          </div>
      </div>

    </div>
</div>

      @endcanany

   </div>



</div>

      <!-- END wrapper -->

       <!-- Modal -->

<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

 <div class="modal-dialog modal-dialog-centered">

   <div class="modal-content">

     <div class="modal-header">

      

       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

     </div>

     <div class="modal-body">

   

   <div class="heading">

     <h2>Select Categories</h2>

     <p>Please Select any 4 Categories</p>

   </div>

   <div class="search-container">

     <input type="text" placeholder="Search Category">

     <!-- Search Icon -->

     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">

       <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.415l-3.85-3.85zm-5.442 1.398a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z"/>

     </svg>

   </div>

    <form>
        @if(isset($categories) && count($categories)>0)
        @foreach($categories as $cKey=>$categoryValue)
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="Vegetable" id="vegetable">
              <label class="form-check-label" for="vegetable">{{ucfirst($categoryValue->name)}}</label>
            </div>
        @endforeach
        @endif
        <div class="text-center">
            <button type="button" class="btn btn-save">Save Changes</button>
        </div>
    </form>

    </div>     

   </div>

 </div>

</div>

      <!-- App js -->

  @endsection

  @push('scripts')

  <script>
      document.addEventListener("DOMContentLoaded", function () {
          @if(isset($categorySurvey) && count($categorySurvey) > 0)
              @foreach($categorySurvey as $skey => $category)
                  <?php $i = $skey + 1; ?>
                  var ctx = document.getElementById('barChart{{ $i }}');
                  if (ctx) {
                      new Chart(ctx.getContext('2d'), {
                          type: 'bar',
                          data: {
                              labels: @json($category->labels), 
                              datasets: [{
                                  label: '{{ ucfirst($category->name) }}',
                                  data: @json($category->values), 
                                  backgroundColor: '#006738',
                                  borderColor: '#006738',
                                  borderWidth: 1,
                                  barThickness: 30
                              }]
                          },
                          options: {
                              responsive: true,
                              plugins: {
                                  legend: { display: false },
                                  tooltip: { enabled: true }
                              }
                          }
                      });
                  }
              @endforeach
          @endif
      });
  </script>


  @endpush