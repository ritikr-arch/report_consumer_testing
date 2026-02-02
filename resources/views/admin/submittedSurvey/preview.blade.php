@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<style>

    canvas{

        max-width: 100%;

        height: 300px !important;

    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-color-state: transparent;
        --bs-table-bg-state: transparent;
    }

    table tr:hover {
    background-color: transparent !important;
}
a.btn.btn-secondary.btn-sm {
    font-size: 12px;
}

.table th {
    white-space: nowrap;
}

    .bg-head{
        background-color: var(--theme-color) !important;
        color:white !important;
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
                        Preview Survey Report
                        </h4>
                     </div>
                
                        <div class="row">

                           <form action="{{route('admin.report.detail.filter')}}" method="get">
                                <input type="hidden" value="{{$id}}" name="id">
                              <div id="dropdown" class="dropdown-container-filter fil-comm">

                                <select class="form-select" name="name" aria-label="Default select example">
                                   <option value="" selected="">Commodity</option>
                                    @if(isset($allCommodities) && count($allCommodities)>0)
                                    @foreach($allCommodities as $comKey=>$commodityValue)
                                        <option {{ request('name') == $commodityValue->id ? 'selected' : '' }} value="{{$commodityValue->id}}">{{ucfirst($commodityValue->name)}}</option>
                                    @endforeach
                                    @endif
                                </select>

                                <select class="form-select" name="category" aria-label="Default select example">
                                   <option value="" selected="">Category</option>
                                    @if(isset($filterCategories) && count($filterCategories)>0)
                                    @foreach($filterCategories as $catKey=>$categoryValue)
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

                                 <button type="submit" class="btn btn-success btn-sm" >Search</button>

                                 

                                <div class="search-btn1 text-end">
                                    <a class="btn btn-secondary btn-sm" href="{{route('admin.survey.report.details', $id)}}">Reset</a>
                                </div>


                              </div>

                           </form>

                        </div>
                  </div>

                  <div class="row align-items-center d-flex mb-3">

                     

                     <div class="col-12 col-md-6 col-lg-6 report-tbll">

                        <p><strong>{{ucwords(@$survey->name??'Unknown Survey')}}</strong></p>

                     </div>

                     <div class="col-6 col-md-6 col-lg-6 report-tbll">

                      <p><strong>{{ucwords(@$zone->name??'Unknown Zone')}}</strong></p>

                     </div>

                  </div>


                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">
                         <thead>
                            <tr class="">
                                <th style="background-color: #00a258 !important; color: white !important;" colspan="{{ 3 + count($markets) }}"
                                    class="text-center bg-head text-white fw-bold">
                                    Price Collected on : {{ customt_date_format(@$survey->start_date)}}
                                </th>
                            </tr>
                             <tr class="border-b">
                                 <th>Commodities</th>
                                 <th>Brand</th>
                                 <th>Unit</th>
                                 @if(isset($markets) && count($markets) > 0)
                                     @foreach($markets as $market)
                                         <th style="min-width: 150px;">{{ ucfirst($market->name) }}</th>
                                     @endforeach
                                 @endif
                             </tr>
                         </thead>
                         <tbody>
                            @if(isset($data) && count($data) > 0)
                                @foreach($data as $categoryName => $categoryData)
                                    <tr>
                                        <td colspan="{{ 3 + count($markets) }}" class="bg-light fw-bold">
                                            {{ strtoupper($categoryName) }}
                                        </td>
                                    </tr>
                                    @foreach($categoryData->groupBy('commodity_id') as $commodityId => $commodityData)
                                        @php 
                                            $firstRow = $commodityData->first();
                                            $commodityName = $firstRow->commodity->name ?? 'N/A';
                                            $uniquePrices = $commodityData->pluck('amount')->unique()->values();
                                            $minPrice = $uniquePrices->min();
                                            $maxPrice = $uniquePrices->max();
                                            $isSamePrice = $uniquePrices->count() === 1;
                                        @endphp
                                        <tr>
                                            <td>{{ $commodityName }}</td>
                                            <td>{{ !empty($firstRow->brand->name) && strtolower($firstRow->brand->name) !== 'no name' ? $firstRow->brand->name : 'N/A' }}</td>
                                            <td>{{ $firstRow->unit->name ?? 'No Name' }}</td>

                                            @foreach($markets as $market)
                                                @php
                                                    // Only get price for current commodity and current market
                                                    $marketPrice = $commodityData->where('market_id', $market->id)->first();
                                                    $bgColor = 'transparent';
                                                    $textColor = 'black';

                                                    if ($marketPrice) {
                                                        if (!$isSamePrice) {
                                                            if ($marketPrice->amount == $minPrice) {
                                                                $bgColor = '#228522'; // Green
                                                                $textColor = 'white !important';
                                                            } elseif ($marketPrice->amount == $maxPrice) {
                                                                $bgColor = '#e52929'; // Red
                                                                $textColor = 'white !important';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <td style="background-color: {{ $bgColor }}; color: {{ $textColor }};text-align: right;">
                                                    {{(@$marketPrice->amount)?'$':''}}{{ @$marketPrice->amount ?? '-' }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

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
if(isset($categories) && count($categories)){
foreach ($categories as $key => $value) {
   array_push($cat, ucwords($value->name));
}
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