@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<style>

 	canvas{

        max-width: 100%;

        height: 300px !important;

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
                     <div class="col-xl-6">
                        <h4 class="header-title mb-0 font-weight-bold">
                           Survey Report
                        </h4>
                     </div>
                     
                    <div class="col-xl-6">
                        <div class="search-btn">
                            <div>
                                <button class="d-flex" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                            </div>
                            <div>
                                <a class="searc-btn-im" href="javascript:void(0)" title=""><i class="fas fa-file-download"></i>Export</a>
                            </div>
                        </div>
                    </div>

                        <div class="row">

                           <form action="{{route('admin.report.detail.filter')}}" method="get">
                                <input type="hidden" value="{{$id}}" name="id">
                              <div id="dropdown" class="dropdown-container-filter fil-comm">

                                <div class="name-input">
                                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Commodity Name" value="{{ request('name') }}">
                                </div>

                                <select class="form-select" name="category" aria-label="Default select example">
                                   <option value="" selected="">Category</option>
                                    @if(isset($category) && count($category)>0)
                                    @foreach($category as $catKey=>$categoryValue)
                                        <option {{ request('category') == $categoryValue->id ? 'selected' : '' }} value="{{$categoryValue->id}}">{{ucfirst($categoryValue->name)}}</option>
                                    @endforeach
                                    @endif
                                </select>

                                <select class="form-select" name="market" aria-label="Default select example">

                                   <option value="" selected="">Markets</option>

                                   @if(isset($filterMarkets) && count($filterMarkets)>0)

                                   @foreach($filterMarkets as $brndKey=>$brndValue)

                                     <option {{ request('market') == $brndValue->id ? 'selected' : '' }} value="{{$brndValue->id}}">{{ucfirst($brndValue->name)}}</option>

                                   @endforeach

                                   @endif

                                </select>

                                {{-- <select class="form-select" name="uom" aria-label="Default select example">

                                   <option value="" selected="">UOM</option>

                                   @if(isset($uom) && count($uom)>0)

                                   @foreach($uom as $uomKey=>$uValue)

                                     <option {{ request('uom') == $uValue->id ? 'selected' : '' }} value="{{$uValue->id}}">{{ucfirst($uValue->name)}}</option>

                                   @endforeach

                                   @endif

                                </select> 

                                <select class="form-select" name="status" aria-label="Default select example">

                                   <option value="" selected="">Status</option>

                                   <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active</option>

                                   <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive</option>

                                </select> --}}

                                 <div class="filter-date">

                                    <label for="start-date">Start Date</label>

                                    <input type="date" value="{{ request('start_date') }}" name="start_date" class="form-control">

                                 </div>

                                 <div class="filter-date">

                                    <label for="end-date">End Date</label>

                                    <input value="{{ request('end_date') }}" type="date" name="end_date" class="form-control">

                                 </div>

                                 <button type="submit" class="d-flex searc-btn" >Search</button>

                                 <a href="{{route('admin.survey.report.details', $id)}}">Reset</a>

                              </div>

                           </form>

                        </div>

                    {{-- <div class="col-6 col-md-2 col-lg-2">

                        <label>From</label>

                        <input type="date" class="form-control">

                     </div>

                     <div class="col-6 col-md-2 col-lg-2">

                     <label>To</label>

                        <input type="date" class="form-control">

                     </div>

                     <div class="col-12 col-md-2 col-lg-2 report-export-btn">

                     <label>&nbsp;</label>

                        <button><img src="{{asset('admin/img/export.png')}}" alt="">Export Report As</button>

                     </div> --}}

                  </div>

                  <div class="row align-items-center d-flex mb-3">

                     

                     <div class="col-12 col-md-2 col-lg-2 report-tbll">

                        <p>Price Analysis</p>

                     </div>

                     <div class="col-6 col-md-2 col-lg-2 report-tbll">

                     <p>Availability</p>

                     </div>

                  </div>


                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>

                           <tr class="border-b">
                              <th>Commodities</th>

                              <th>Brand</th>

                              <th>Unit</th>
                              @if(isset($markets) && count($markets)>0)
                                 @foreach($markets as $market)
                                    <th style="min-width: 150px;">{{(ucfirst($market->name))}}</th>
                                 @endforeach
                              @endif
                           </tr>

                        </thead>

                        <tbody>
                           @if(isset($categories) && count($categories)>0)
                           @foreach($categories as $categoryValues)
                              <tr>
                                  <td colspan="{{ 3 + count($markets) }}" style="background-color: #ddd; text-align: center; font-weight: bold;">
                                      {{ $categoryValues->name }}
                                  </td>
                              </tr>
                              @if(count($categoryValues->commodities)>0)
                                 @foreach($categoryValues->commodities as $commodity)
                                    <tr>
                                      <td>{{ $commodity->name }} {{$commodity->id}}</td>
                                      <td>{{ ($commodity->brand)?$commodity->brand->name:'' }}</td>
                                      <td>{{ ($commodity->uom)?$commodity->uom->name:'' }}</td>
                                       @foreach($markets as $markett)
                                           @php
                                               $priceKey = $commodity->id . '-' . $market->id;
                                               $price = $prices[$priceKey][0]->amount ?? $priceKey;
                                           @endphp
                                           <td style="color: {{ is_numeric($price) ? ($price == collect($prices)->min('price') ? 'green' : 'red') : 'black' }}">
                                               {{ is_numeric($price) ? '$' . number_format($price, 2) : '-' }}
                                               {{$priceKey}}
                                           </td>
                                       @endforeach             
                                   </tr>
                                 @endforeach
                              @endif
                           @endforeach
                           @endif
                          
                        </tbody>

                     </table>

                  </div>

                  {{-- <canvas id="reportChart"></canvas> --}}               

               </div>

            </div>

         </div>

      </div>

      <!-- end row-->

      <div class="row">

         <div class="col-xl-12">

            <div class="card">

               <div class="card-body">

                  

               </div>

            </div>

         </div>

      </div>



   </div>

   <!-- container -->

</div>

</div>

</div>
<?php
$cat = [];
foreach ($categories as $key => $value) {
   array_push($cat, ucwords($value->name));
}
?>
@endsection

@push('scripts')

<script>

    function toggleDropdown() {

        var dropdown = document.getElementById("dropdown");

        dropdown.classList.toggle("active");

    }

    window.onload = function () {

       let params = new URLSearchParams(window.location.search);

          if(params.has('name') || params.has('category') || params.has('market') || params.has('start_date') || params.has('end_date')){

          let dropdown = document.getElementById("dropdown");

          dropdown.classList.toggle("active");

       }

    };


   var category = @json($cat);
   // var category = ['Milk', 'Butter', 'Biscuit', 'Carrot', 'Fish', 'Honey', 'Soap', 'Chicken', 'Bread'];
	const ctx = document.getElementById('reportChart').getContext('2d');

	console.log(ctx);

    const reportChart = new Chart(ctx, {

        type: 'bar',

        data: {

            labels: category,

            datasets: [

                { 

                    label: 'May', 

                    data: [50, 70, 60, 30, 40, 30, 20, 50, 45], 

                    backgroundColor: 'blue', 

                    

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                },

                { 

                    label: 'June', 

                    data: [30, 50, 90, 20, 70, 60, 10, 90, 30], 

                    backgroundColor: 'orange', 

                    // borderRadius: 50, 

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                },

                { 

                    label: 'July', 

                    data: [60, 50, 80, 40, 60, 50, 30, 70, 65], 

                    backgroundColor: 'green', 

                    

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'top',

                    align: 'start',

                    labels: {

                        usePointStyle: true,

                        pointStyle: 'circle',

                        font: {

                            size: 16 // Increase the font size for legend labels (May, June, July)

                        }

                    }

                }

            },

            scales: {

                x: {

                    ticks: {

                        font: {

                            size: 14 // Increase font size for X-axis labels (e.g., Milk, Butter)

                        }

                    }

                },

                y: {

                    beginAtZero: true,

                    max: 100,

                    ticks: {

                        font: {

                            size: 14 // Increase font size for Y-axis labels

                        }

                    }

                }

            }

        }

    });

</script>

@endpush