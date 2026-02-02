@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

          

      <div class="px-3 pb-5">

         <!-- Start Content-->

         <div class="container-fluid">

            <div class="row">

               <div class="col-xl-8">

                  <div class="row mgt40">

                     <div class="col-lg-6 pdl-0">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/dash1.png')}}" alt="dash-img"> Created Surveys</h4>
                           <h2>{{$surveys}}</h2>

                        </div>

                     </div>

                     <div class="col-lg-6 pdr-0">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/dash2.png')}}" alt="dash-img"> Completed Surveys</h4>

                           <h2>{{$completedSurvey}}</h2>

                        </div>

                     </div>

                     <div class="col-lg-6 pdl-0">
                        <div class="card-dash">
                           <h4><img src="{{asset('admin/images/dash3.png')}}" alt="dash-img">In Progress Surveys</h4>
                           <h2>{{$pendingSurvey}}</h2>
                        </div>
                     </div>

                     <div class="col-lg-6 pdr-0">

                        <div class="card-dash">

                           <h4><img src="{{asset('admin/images/dash4.png')}}" alt="dash-img"> Overdue Surveys</h4>

                           <h2>{{$overdueSurvey}}</h2>

                        </div>

                     </div>

                  </div>

                  <!-- end row-->

                  <div class="row graph-home-wrap">

                    <div class="col-md-12">

                     <div class="bar-head">

                     <h4>Price Data</h4>

                     <div class="ch_cate" data-bs-toggle="modal" data-bs-target="#exampleModal">

                     <img src="{{asset('admin/img/edit-3.png')}}" alt="dash-img">

                     <span>Change Categories</span>

                     </div>

                     </div>

                    </div>

                    @if(isset($categorySurvey) && count($categorySurvey)>0)
                    @foreach($categorySurvey as $skey=>$category)
                        <div class="col-md-6 graph-home">
                            <span>{{ucfirst($category->name)}}</span>
                            <div id="chart-container">
                               <canvas id="barChart{{$skey}}"></canvas>
                            </div>
                        </div>
                    @endforeach
                    @endif
                    <div class="col-md-6 graph-home">
                        <span>Meat</span>
                        <div id="chart-container">
                           <canvas id="barChart"></canvas>
                        </div>
                    </div>

                     <div class="col-md-6 graph-home">

                        <span>Meat</span>

                        <div id="chart-container">

                           <canvas id="barChart1"></canvas>

                        </div>

                     </div>

                     <div class="col-md-6 graph-home mgt-50">

                        <span>Meat</span>

                        <div id="chart-container">

                           <canvas id="barChart2"></canvas>

                        </div>

                     </div>

                     <div class="col-md-6 graph-home mgt-50">

                        <span>Meat</span>

                        <div id="chart-container">

                           <canvas id="barChart3"></canvas>

                        </div>

                     </div>
                    

                  </div>

               </div>

               <!-- end col -->

               <div class="col-xl-4">

                  <div class="card-recent-data">

                     <div class="recent-data">

                        <h4>Recently Submitted Survey Data</h4>
                        {{-- <h4>Recently Submitted Price Data</h4> --}}

                        <img src="{{asset('admin/img/arrow-up-right.png')}}" alt="arrow">

                     </div>

                    @if(isset($recentSubmittedSurvey) && count($recentSubmittedSurvey)>0)
                    @foreach($recentSubmittedSurvey as $rKey=>$recentData)
                    <div class="recent-data-box">
                        <div class="rd-img">
                            <?php 
                            $image = '';
                            if($recentData->commodity_image){
                                $image = '/submittedSurveyImage/'.$recentData->commodity_image;
                            }else{
                                $image = 'admin/img/rd1.png';
                            }
                            ?>
                            <img src="{{asset($image)}}" alt="arrow" style="height: 50px; width:50px;border-radius: 5%; ">
                             <span>{{($recentData->commodity)?ucwords($recentData->commodity->name):''}}</span>
                        </div>
                        <p>{{$recentData->market->name}}</p>
                    </div>
                    @endforeach
                    @endif
                     
                    <div class="recent-data mt-5">
                    <h4>Notifications</h4>
                    </div>
                    @if(isset($recentNotification) && count($recentNotification)>0)
                        @foreach($recentNotification as $nKey=>$notifications)
                        <div class="recent-data-box-not">
                           <p>{{ucfirst($notifications->message)}}</p>
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

  <script src="{{ asset('admin/js/pages/dashboard.js')}}"></script>

  <script>

     const ctx = document.getElementById('barChart').getContext('2d');

     new Chart(ctx, {

       type: 'bar',

       data: {

         labels: ['No Brand', 'No Brand', 'No Brand', 'No Brand', 'No Brand'], // X-axis labels

         datasets: [{

           label: 'No Brand',

           data: [190, 150, 75, 130, 90],

           backgroundColor: [

             '#006738', '#006738', '#006738', '#006738', '#006738'

           ],

           borderColor: [

             '#006738', '#006738', '#006738', '#006738', '#006738'

           ],

           borderWidth: 1,

           barThickness: 30 // Adjust this value to change the bar width

         }]

       },

       options: {

         responsive: true,

         plugins: {

           legend: {

             display: false

           },

           tooltip: {

             enabled: true

           },

           datalabels: {

             color: 'white',

             font: {

               weight: 'bold',

               size: 12 // Increases the font size

             },

             padding: {

               left: 0,

               right: 0,

               top: 2,

               bottom: 2

             }, // Removes left and right padding

             formatter: (value, context) => {

               const categories = ['Wings', 'Thighs', 'Neck', 'Mutton', 'Backs'];

               return categories[context.dataIndex]; // Shows categories inside the bars

             },

             anchor: 'center',

             align: 'center',

             rotation: 270 // Rotates the text vertically

           }

         },

         scales: {

           x: {

             grid: {

               display: false // Removes vertical grid lines

             }

           },

           y: {

             beginAtZero: true,

             max: 200,

             ticks: {

               stepSize: 50

             },

             grid: {

               display: true // Keeps horizontal grid lines

             }

           }

         }

       },

       plugins: [ChartDataLabels]

     });

  </script>

  <script>

     const ctx1 = document.getElementById('barChart1').getContext('2d');

     new Chart(ctx1, {

       type: 'bar',

       data: {

         labels: ['Pure Wesson', 'Essential Everyday', 'Roberts'], // X-axis labels

         datasets: [{

           label: 'No Brand',

           data: [190, 150, 90],

           backgroundColor: [

             '#006738', '#006738', '#006738'

           ],

           borderColor: [

             '#006738', '#006738', '#006738'

           ],

           borderWidth: 1,

           barThickness: 30 // Adjust this value to change the bar width

         }]

       },

       options: {

         responsive: true,

         plugins: {

           legend: {

             display: false

           },

           tooltip: {

             enabled: true

           },

           datalabels: {

             color: 'white',

             font: {

               weight: 'bold',

               size: 12 // Increases the font size

             },

             padding: {

               left: 0,

               right: 0,

               top: 2,

               bottom: 2

             }, // Removes left and right padding

             formatter: (value, context) => {

               const categories = ['Wings', 'Thighs', 'Neck'];

               return categories[context.dataIndex]; // Shows categories inside the bars

             },

             anchor: 'center',

             align: 'center',

             rotation: 270 // Rotates the text vertically

           }

         },

         scales: {

           x: {

             grid: {

               display: false // Removes vertical grid lines

             }

           },

           y: {

             beginAtZero: true,

             max: 200,

             ticks: {

               stepSize: 50

             },

             grid: {

               display: true // Keeps horizontal grid lines

             }

           }

         }

       },

       plugins: [ChartDataLabels]

     });

  </script>

  <script>

     const ctx2 = document.getElementById('barChart2').getContext('2d');

     new Chart(ctx2, {

       type: 'bar',

       data: {

         labels: ['Kellogg(340G)', 'Kellogg(500G)', 'Kellogg(510G)','Kellogg(680G)'], // X-axis labels

         datasets: [{

           label: 'No Brand',

           data: [190, 120, 160, 90],

           backgroundColor: [

             '#006738', '#006738', '#006738', '#006738'

           ],

           borderColor: [

             '#006738', '#006738','#006738', '#006738'

           ],

           borderWidth: 1,

           barThickness: 30 // Adjust this value to change the bar width

         }]

       },

       options: {

         responsive: true,

         plugins: {

           legend: {

             display: false

           },

           tooltip: {

             enabled: true

           },

           datalabels: {

             color: 'white',

             font: {

               weight: 'bold',

               size: 12 // Increases the font size

             },

             padding: {

               left: 0,

               right: 0,

               top: 2,

               bottom: 2

             }, // Removes left and right padding

             formatter: (value, context) => {

               const categories = ['Cornflakes', 'Cornflakes', 'Cornflakes','Cornflakes'];

               return categories[context.dataIndex]; // Shows categories inside the bars

             },

             anchor: 'center',

             align: 'center',

             rotation: 270 // Rotates the text vertically

           }

         },

         scales: {

           x: {

             grid: {

               display: false // Removes vertical grid lines

             }

           },

           y: {

             beginAtZero: true,

             max: 200,

             ticks: {

               stepSize: 50

             },

             grid: {

               display: true // Keeps horizontal grid lines

             }

           }

         }

       },

       plugins: [ChartDataLabels]

     });

  </script>

  <script>

     const ctx3 = document.getElementById('barChart3').getContext('2d');

     new Chart(ctx3, {

       type: 'bar',

       data: {

         labels: ['Carnation', 'Distinction', 'Eve'], // X-axis labels

         datasets: [{

           label: 'No Brand',

           data: [190, 150, 90],

           backgroundColor: [

             '#006738', '#006738', '#006738'

           ],

           borderColor: [

             '#006738', '#006738', '#006738'

           ],

           borderWidth: 1,

           barThickness: 30 // Adjust this value to change the bar width

         }]

       },

       options: {

         responsive: true,

         plugins: {

           legend: {

             display: false

           },

           tooltip: {

             enabled: true

           },

           datalabels: {

             color: 'white',

             font: {

               weight: 'bold',

               size: 12 // Increases the font size

             },

             padding: {

               left: 0,

               right: 0,

               top: 2,

               bottom: 2

             }, // Removes left and right padding

             formatter: (value, context) => {

               const categories = ['Milk', 'Milk', 'Milk'];

               return categories[context.dataIndex]; // Shows categories inside the bars

             },

             anchor: 'center',

             align: 'center',

             rotation: 270 // Rotates the text vertically

           }

         },

         scales: {

           x: {

             grid: {

               display: false // Removes vertical grid lines

             }

           },

           y: {

             beginAtZero: true,

             max: 200,

             ticks: {

               stepSize: 50

             },

             grid: {

               display: true // Keeps horizontal grid lines

             }

           }

         }

       },

       plugins: [ChartDataLabels]

     });

  </script>

  @endpush