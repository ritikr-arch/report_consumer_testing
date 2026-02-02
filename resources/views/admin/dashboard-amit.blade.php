@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

      @canany(['dashboard_view'])

      @else

      @endcanany

@endsection

  @push('scripts')
  
 <style>
 .rd-img a {
    color: #666;
	padding-left: 10px;
}

.rd-img b {
    color: #1C1C1C;
    font-size: 12px;
    font-weight: 500;
    padding-left: 10px;
}
 </style>
  
  
  <div class="container-fluid mt-4">
  <div class="card p-0 radius-12">
           
            <div class="card-body p-24 pb-0">
			
			
			
			<h5 class="mb-2">Survey Data</h5>
			  <div class="row ">
			       <div class="col">
                    <div class="card mb-2  border bg-gradient-start-1">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Created Surveys</p>
                            <h4 class="mb-0">{{$surveys}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/surveyor.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                     
                        </div>
                    </div><!-- card end -->
                    </div>
                    <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-2">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1"> Published Surveys</p>
                            <h4 class="mb-0">{{$completedSurvey}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/clipboard-check.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                      
                        </div>
						
                    </div><!-- card end -->
                    </div>
                    <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-3">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">In Progress Surveys</p>
                            <h4 class="mb-0">{{$pendingSurvey}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-info2 rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/processing-time.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                        </div>
                    </div><!-- card end -->
                    </div>
                   <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-4">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Overdue Surveys</p>
                            <h4 class="mb-0">{{$overdueSurvey}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/surveyor.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                     
                        </div>
                    </div><!-- card end -->
                    </div>
			    </div>
				<h5 class="mb-2 mt-2">Store & Commodity Data</h5>
					  <div class="row">
			     <div class="col-md-3 col-12">
               <div class="card mb-2 radius-8 border bg-gradient-start-1">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1"> Total Stores</p>
                            <h4 class="mb-0">{{$markets}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <img src="/admin/images/report.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
						
                       
                        </div>
                    </div>
					 </div>
                    <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-2">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Total Commodities</p>
                            <h4 class="mb-0">{{$commodities}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/complain.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                        </div>
                    </div><!-- card end -->
                    </div>    
		
              
			    </div>
					<h5 class="mb-2 mt-2">Complaint Data</h5>
			  <div class="row row-col-md-3 col-12s-xxxl-5 row-col-md-3 col-12s-lg-4 row-col-md-3 col-12s-sm-2 row-col-md-3 col-12s-1 gy-4">
			     <div class="col-md-3 col-12">
               <div class="card mb-2 radius-8 border bg-gradient-start-1">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Total Complaints</p>
                            <h4 class="mb-0">{{$totalComplaints}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <img src="/admin/images/report.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
						
                       
                        </div>
                    </div>
					 </div>
                    <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-2">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">New Complaints</p>
                            <h4 class="mb-0">{{$newComplaints}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/complain.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                        </div>
                    </div><!-- card end -->
                    </div>    
					
					<div class="col-md-3 col-12">
                    <div class="card mb-2 radius-8 border bg-gradient-start-3">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1"> In Progress Complaints</p>
                            <h4 class="mb-0">{{$inprogressComplaints}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-info2 rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/processing-time.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
						
						
                       
                        </div>
                    </div><!-- card end -->
                    </div>
                <div class="col-md-3 col-12">
                    <div class="card mb-2 radius-8 border bg-gradient-start-4">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">  Resolved Complaints</p>
                            <h4 class="mb-0">{{$resolvedComplaints}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/resolved.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                </div>

                
                    </div><!-- card end -->
                    </div>
                     <div class="col-md-3 col-12">
                    <div class="card mb-2 radius-8 border bg-gradient-start-4">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Dismissed Complaints</p>
                            <h4 class="mb-0">{{$dismissedComplaints}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/resolved.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                </div>

                
                    </div><!-- card end -->
                    </div>
			    </div>
				
				
				<h5 class="mb-2 mt-2">Feedback & Contact us Data</h2>
			  <div class="row">
			     <div class="col-md-3 col-12">
               <div class="card mb-2 radius-8 border bg-gradient-start-1">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1"> Total Feedbacks</p>
                            <h4 class="mb-0">{{$feedback}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                            <img src="/admin/images/review2.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>					
                       
                        </div>
                    </div>
					 </div>
                    <div class="col-md-3 col-12">
                    <div class="card mb-2  border bg-gradient-start-2">
                        <div class="card-body p-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div>
                            <p class="fw-medium text-primary-light mb-1">Total Contact Us</p>
                            <h4 class="mb-0">{{$enquiries}}</h4>
                            </div>
                            <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                           <img src="/admin/images/complain.png" alt="dash-img" class="img-white" width="24px">
                            </div>
                        </div>
                       
                        </div>
                    </div><!-- card end -->
                 
 </div> 

					 
				                <h5 class="mb-2 mt-2">Officers Data</h5>
                                <div class="row">
                                <!-- Chief Investigation Officers -->
                                <div class="col-md-3 col-12">
                                    <div class="card mb-2 radius-8 border bg-gradient-start-3">
                                    <div class="card-body p-2">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div>
                                            <p class="fw-medium text-primary-light mb-1">Total Chief Investigation Off.</p>
                                            <h4 class="mb-0">{{ $chiefinvestigationOfficers }}</h4>
                                        </div>
                                        <div class="w-50-px h-50-px bg-info2 rounded-circle d-flex justify-content-center align-items-center">
                                            <img src="/admin/images/searching.png" alt="Chief Investigation" class="img-white" width="24px">
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <!-- Investigation Officers -->
                                <div class="col-md-3 col-12">
                                    <div class="card mb-2 radius-8 border bg-gradient-start-4">
                                    <div class="card-body p-2">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div>
                                            <p class="fw-medium text-primary-light mb-1">Total Investigation Officers</p>
                                            <h4 class="mb-0">{{ $investigationOfficers }}</h4>
                                        </div>
                                        <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                                            <img src="/admin/images/inspection.png" alt="Investigation Officer" class="img-white" width="24px">
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                  <!-- Investigation Officers -->
                                <div class="col-md-3 col-12">
                                    <div class="card mb-2 radius-8 border bg-gradient-start-4">
                                    <div class="card-body p-2">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                        <div>
                                            <p class="fw-medium text-primary-light mb-1">Total Compliance Officers</p>
                                            <h4 class="mb-0">{{$complianceOfficers}}</h4>
                                        </div>
                                        <div class="w-50-px h-50-px bg-success rounded-circle d-flex justify-content-center align-items-center">
                                            <img src="/admin/images/inspection.png" alt="Investigation Officer" class="img-white" width="24px">
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>

				
    
    
             </div>
               </div>
            </div>
		
			
			
			<div class="card">	
			<div class="card-body">
			<div class="row">
			<div class="col-xl-6">
             <div class="card mb-2 border">
                        <div class="card-body p-2">
                     <div class="recent-data">
                        <h5 class="mt-2">Recently Published Survey List</h5>
                        <img src="/admin/img/arrow-up-right.png" alt="arrow">
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
                  </div>
                   </div>
				  
				  	<div class="col-xl-6">
				   <div class="card mb-2 border">
                        <div class="card-body p-2">

                     <div class="recent-data">

                        <h5 class="mt-2">Recently Collected Survey List</h5>
                        

                        <img src="/admin/img/arrow-up-right.png" alt="arrow">

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
  </div>

               </div>
        </div> 
		</div>


		</div>


</div>
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
                                  backgroundcol-md-3 col-12or: '#006738',
                                  bordercol-md-3 col-12or: '#006738',
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