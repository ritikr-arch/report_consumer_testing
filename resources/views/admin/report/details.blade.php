@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
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


thead
{
    position: sticky;
    top: 0;
    background-color: #f1f1f1;
    padding: 10px;
    text-align: left;
    z-index: 30;
    border-bottom: 0px solid #ccc;
}

.table-container 
{
  max-height: 400px;
  overflow-y: auto;
  /*border: 1px solid #ddd;
  background: white;*/
/*  width: fit-content;*/
/*  margin: 0 auto;*/
   */-webkit-box-shadow: 0 3px 16px rgba(112, 114, 160, .2);
    box-shadow: 0 3px 16px rgba(112, 114, 160, .2);
}


.sticky-1 {
                position: sticky;
                left: 0;
                z-index: 20;
                background-color: #f8f9fa !important;
            }

            .sticky-2 {
                position: sticky;
                left: 60px;
                z-index: 20;
                background-color: #f8f9fa !important;
            }

            .sticky-3 {
                position: sticky;
                left: 120px;
                z-index: 20;
                background-color: #f8f9fa !important;
            }

th, td 
{
  width: 150px;
/*  text-align: center;*/
  border: 0px !important;
}

th
{
  background-color: #fff !important;
}

table {
    /* width: max-content !important; */
    z-index: 999099999 !important;
}

.zone-child {
    width: 90%;
    display: flex;
    align-items: center;
    margin-top: 12px;
}

.color-box {
    width: 15px;
    height: 15px;
    margin-right: 5px;
    border-radius: 20px;
}
.loss-red {
    background-color: red !important;
    color: #fff;
}

.profit-green {
    background-color: green !important;
    color: #fff;
}

.box-key {
    display: flex;
    align-items: center;
    margin-right: 20px;
}

#ui-datepicker-div{
    z-index: 999999 !important;
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
                        <div class="search-btn1 text-end">
                            <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                            <a class="btn btn-secondary btn-sm"
                                href="{{ route('admin.submitted.survey.report.export', array_merge(request()->query(), ['id' => request()->query('id', $id)])) }}"
                                title=""><i class="fas fa-file-download"></i> Export</a>
                            {{-- <a class="searc-btn-im" href="{{ route('admin.submitted.survey.report.export', array_merge(request()->query(), ['id' => request()->query('id', $id)])) }}" 
                           title="">
                           <i class="fas fa-file-download"></i>Export
                        </a> --}}

                            <a class="btn btn-success btn-sm"
                                href="{{ route('admin.submitted.survey.price.analysis.report', array_merge(request()->query(), ['id' => request()->query('id', $id)])) }}"
                                title=""><i class="fas fa-file-download"></i> Price Analysis Report</a>

                        </div>
                    </div>

                    {{-- <div class="col-xl-6">
                        <div class="search-btn">
                            <div>
                                <button class="d-flex" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                            </div>
                            <div>
                                <a class="searc-btn-im" 
                                   href="{{ route('admin.submitted.survey.report.export', array_merge(request()->query(), ['id' => request()->query('id', $id)])) }}" 
                                   title="">
                                   <i class="fas fa-file-download"></i>Export
                                </a>

                            </div>
                        </div>
                    </div> --}}

                        <div class="row">

                           <form action="{{route('admin.report.detail.filter')}}" method="get">
                                <input type="hidden" value="{{$id}}" name="id">
                              <div id="dropdown" class="dropdown-container-filter fil-comm" style="flex-wrap: nowrap;">

                                {{-- <div class="name-input">
                                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Commodity Name" value="{{ request('name') }}">
                                </div> --}}
                                
                                {{-- <select class="form-select" name="type" aria-label="Default select example">
                                   <option value="" selected="">Types</option>
                                    @if(isset($types) && count($types)>0)
                                    @foreach($types as $comKey=>$type)
                                        <option {{ request('type') == $type->id ? 'selected' : '' }} value="{{$type->id}}">{{ucfirst($type->name)}}</option>
                                    @endforeach
                                    @endif
                                </select> --}}

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

                                    <!-- <label for="start-date">Start Date</label> -->

                                   
                              <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-control" placeholder="Start Date">

                                 </div>

                                 <div class="filter-date">

                                    <!-- <label for="end-date">End Date</label> -->

                                    <input type="text" name="end_date" value="{{ request('end_date') }}" class="form-control" placeholder="End Date" id="end_date" autocomplete="off">

                                 </div>


                           <div class="d-flex"> 
                               <button type="submit" class="btn btn-success btn-sm" >Search</button>
                                    <a class="btn btn-secondary btn-sm" href="{{route('admin.survey.report.details', $id)}}">Reset</a>
                                </div>


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

                     

                     <div class="col-12 col-md-6 col-lg-6 report-tbll">

                        <p><strong>{{ucwords(@$survey->name??'Unknown Survey')}}</strong></p>

                     </div>

                     <div class="col-6 col-md-6 col-lg-6 report-tbll">

                      <p><strong>{{ucwords(@$zone->name??'Unknown Zone')}}</strong></p>

                     </div>

                  </div>

                  <div class="row align-items-center d-flex mb-3">
                     <div class="col-12 col-md-6 col-lg-6 report-tbll">
                        <!-- <label for="">High</label>
                        <input type="checkbox" name="" value="High">
                        
                        <label for="">Low</label>
                        <input type="checkbox" name="" value="Low"> -->

                        <div class="zone-child my-4">
                        <div class="box-key">
                            <div class="color-box loss-red"></div>
                            <span>Highest Price</span>
                        </div>
                        <div class="box-key">
                            <div class="color-box profit-green"></div>
                            <span>Lowest Price</span>
                        </div>
                    </div>
                     </div>
                  </div>


                  <div class="table-responsive table-container white-space">

                     <table class="table table-hover mb-0 w-100">
                         <thead>
                             <tr class="border-b">
                                  <th class="sticky-1" align="center" style=""><div style="width: 112px;">Commodities</div></th>
                                                        <th class="sticky-2" align="center"><div style="width: 112px;">Brand</div></th>
                                                        <th class="sticky-3" align="center"><div style="width: 112px;">Unit</div></th>
                                 @if(isset($markets) && count($markets) > 0)
                                     @foreach($markets as $market)
                                         <th style="background-color: #f8f9fa !important;">{{ ucfirst($market->name) }}</th>
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
                                            <td class="sticky-1">{{ $commodityName }}</td>
                                            <td class="sticky-2">{{ $firstRow->brand->name ?? 'No Name' }}</td>
                                            <td class="sticky-3">{{ $firstRow->unit->name ?? 'No Name' }}</td>

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
<script src="{{ asset('admin/multi/multiselect-dropdown.js') }}"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script>
    $("#start_date").datepicker({
        dateFormat: "dd-mm-yy",
        onSelect: function (selectedDate) {
        $("#end_date").prop("disabled", false);

            if ($("#end_date").hasClass("hasDatepicker")) {
                $("#end_date").datepicker("destroy");
            }

            $("#end_date").datepicker({
               dateFormat: "dd-mm-yy",
               minDate: selectedDate
            });
        }
    });
   $(function() {
     $("#start_date").datepicker();
     $("#end_date").datepicker();
   });
</script>
@endpush