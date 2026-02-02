@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<?php
use App\Models\SubmittedSurvey;
?>
<style>
canvas {
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
                                    Price Analysis Report
                                </h4>
                            </div>
                             <div class="col-xl-6">
                            <div class="search-btn1 text-end">
                                {{-- <a class="searc-btn-im" href="{{ route('admin.export.price.analysis.report', array_merge(request()->query(), ['id' => request()->query('id', $id)])) }}" 
                               title="">
                                   <i class="fas fa-file-download"></i>Export
                                </a> --}}

                                <button id="exportExcel" class="btn btn-secondary btn-sm"><i class="fas fa-file-download"></i> Export to Excel</button>

                            </div>
                        </div>
                        </div>
                       
                        
                        <div class="row mb-4">
                            <hr>
                            <form action="{{route('admin.report.detail.filter')}}" method="get">
                                <input type="hidden" value="{{$id}}" name="id">
                                <div id="dropdown" class="dropdown-container-filter fil-comm">

                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name"
                                            id="exampleFormControlInput1" placeholder="Commodity Name"
                                            value="{{ request('name') }}">
                                    </div>

                                    <select class="form-select" name="category" aria-label="Default select example">
                                        <option value="" selected="">Category</option>
                                        @if(isset($filterCategories) && count($filterCategories)>0)
                                        @foreach($filterCategories as $catKey=>$categoryValue)
                                        <option {{ request('category') == $categoryValue->id ? 'selected' : '' }}
                                            value="{{$categoryValue->id}}">{{ucfirst($categoryValue->name)}}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                    <select class="form-select" name="market" aria-label="Default select example">

                                        <option value="" selected="">Markets</option>

                                        @if(isset($filterMarkets) && count($filterMarkets)>0)

                                        @foreach($filterMarkets as $brndKey=>$brndValue)

                                        <option {{ request('market') == $brndValue->id ? 'selected' : '' }}
                                            value="{{$brndValue->id}}">{{ucfirst($brndValue->name)}}</option>

                                        @endforeach

                                        @endif

                                    </select>

                                    <div class="filter-date">

                                        <label for="start-date">Start Date</label>

                                        <input type="date" value="{{ request('start_date') }}" name="start_date"
                                            class="form-control">

                                    </div>

                                    <div class="filter-date">

                                        <label for="end-date">End Date</label>

                                        <input value="{{ request('end_date') }}" type="date" name="end_date"
                                            class="form-control">

                                    </div>

                                    <button type="submit" class="d-flex searc-btn">Search</button>

                                    <a href="{{route('admin.survey.report.details', $id)}}">Reset</a>

                                </div>

                            </form>

                        </div>

                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-12 col-md-9 col-lg-9 report-tbll">
                                <p><strong>Price Observation for {{ucwords(@$survey->name??'Unknown Survey')}}</strong></p>
                            </div>
                           <div class="col-6 col-md-3 col-lg-3 report-tbll">
                           <input type="text" id="customSearchBox" class="form-control" placeholder="Search...">
                         </div>

                        </div>
                        <div class="white-space">
                            <table id="myDataTable"  class="table table-hover mb-0">
                                <thead>
                                    <tr class="border-b bg-light2">
                                        <th>Commodities</th>
                                        <th>Brand</th>
                                        <th>Unit</th>
                                        <th>Maximum Price</th>
                                        <th>Minimum Price</th>
                                        <th>Median Price</th>
                                        <th>Average Price</th>
                                        <th>Availability</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data) > 0)
                                        @foreach($data as $categoryName => $categoryData)
                                            <tr>
                                                <td colspan="8" class="bg-light fw-bold">
                                                    {{ strtoupper($categoryName) }}
                                                </td>
                                            </tr>

                                            @foreach($categoryData as $commodity)
                                                @php
                                                    $commodityName = $commodity->commodity->name ?? 'N/A';
                                                    $commodityId = $commodity->commodity->id ?? 'N/A';
                                                    $firstRow = $commodity; // Using the commodity directly

                                                    // Get all unique prices in this row
                                                    $rowPrices = collect();
                                                    foreach ($markets as $market) {
                                                        $marketPrice = $categoryData->where('market_id', $market->id)->first();
                                                        if ($marketPrice && isset($marketPrice->amount)) {
                                                            $rowPrices->push($marketPrice->amount);
                                                        }
                                                    }

                                                    $rowPrices = $rowPrices->unique()->values(); // Unique prices
                                                    $rowMinPrice = $rowPrices->min();
                                                    $rowMaxPrice = $rowPrices->max();

                                                    // Condition to check if all prices in the row are the same or only one price exists
                                                    $isSinglePriceRow = $rowPrices->count() === 1;
                                                @endphp
                                                <?php
                                                $price = SubmittedSurvey::select('id', 'amount')->where(['survey_id'=>$id, 'commodity_id'=>$commodityId])->get();

                                                $marketCount = SubmittedSurvey::where(['survey_id' => $id, 'commodity_id' => $commodityId])->distinct('market_id')->count('market_id');
                                            
                                                // $availability = (($marketCount/$totalMarkets)*100);
                                                $availability = ($totalMarkets > 0) ? (($marketCount / $totalMarkets) * 100) : 0;

                                                $highestPrice = SubmittedSurvey::where([
                                                    'survey_id' => $id, 
                                                    'commodity_id' => $commodityId
                                                ])->max('amount');

                                                $lowestPrice = SubmittedSurvey::where([
                                                    'survey_id' => $id, 
                                                    'commodity_id' => $commodityId
                                                ])->min('amount');

                                                $averagePrice = SubmittedSurvey::where([
                                                    'survey_id' => $id, 
                                                    'commodity_id' => $commodityId
                                                ])->avg('amount');

                                                $amounts = SubmittedSurvey::where([
                                                    'survey_id' => $id, 
                                                    'commodity_id' => $commodityId
                                                ])->orderBy('amount')->pluck('amount')->toArray();

                                                $median = null;
                                                $count = count($amounts);

                                                if ($count > 0) {
                                                    $middle = floor(($count - 1) / 2);

                                                    if ($count % 2) {
                                                        // Odd count: Take the middle value
                                                        $median = $amounts[$middle];
                                                    } else {
                                                        // Even count: Average of the two middle values
                                                        $median = ($amounts[$middle] + $amounts[$middle + 1]) / 2;
                                                    }
                                                }

                                                ?>

                                                <tr>
                                                    <td>{{ $commodityName }}</td>
                                                    <td>{{ $firstRow->brand->name ?? 'No Name' }} </td>
                                                    <td>{{ $firstRow->unit->name ?? 'No Name' }}</td>
                                                    <td class="admin_amnt">${{@$highestPrice}}</td>
                                                    <td class="admin_amnt">${{@$lowestPrice}}</td>
                                                    <td class="admin_amnt">${{number_format(@$median, 2)}}</td>
                                                    <td class="admin_amnt">${{number_format(@$averagePrice, 2)}}</td>
                                                    <td >{{number_format(@$availability, 2)}}%</td>
                                                    {{-- @foreach($markets as $market)
                                                        @php
                                                            $marketPrice = $categoryData->where('market_id', $market->id)->first();
                                                            $bgColor = 'transparent';
                                                            $textColor = 'black'; // Default text color

                                                            if (!$isSinglePriceRow && $marketPrice && isset($marketPrice->amount)) {
                                                                if ($marketPrice->amount == $rowMinPrice) {
                                                                    $bgColor = '#228522'; // Green for lowest price
                                                                    $textColor = 'white';
                                                                } elseif ($marketPrice->amount == $rowMaxPrice) {
                                                                    $bgColor = '#e52929'; // Red for highest price
                                                                    $textColor = 'white';
                                                                }
                                                            }
                                                        @endphp

                                                        <td style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                            {{ $marketPrice->amount ?? '-' }}
                                                        </td>
                                                    @endforeach --}}
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif


                                </tbody>
                            </table>
                        </div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.getElementById('exportExcel').addEventListener('click', function () {
      const table = document.getElementById('myDataTable');
      const rows = table.querySelectorAll('tbody tr');

      const filteredData = [];
      const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
      filteredData.push(headers);

      rows.forEach(row => {
        if (row.style.display !== 'none') {
          const rowData = Array.from(row.querySelectorAll('td')).map(td => td.innerText.trim());
          filteredData.push(rowData);
        }
      });

      const worksheet = XLSX.utils.aoa_to_sheet(filteredData);
      const workbook = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(workbook, worksheet, "PriceAnalysisReport");

      XLSX.writeFile(workbook, "Price-Observation-for-{{ @$survey->name }}.xlsx");

    });

</script>
<script>
  document.getElementById('customSearchBox').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#myDataTable tbody tr');

    rows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      row.style.display = rowText.includes(filter) ? '' : 'none';
    });
  });
</script>

<script>
function toggleDropdown() {

    var dropdown = document.getElementById("dropdown");

    dropdown.classList.toggle("active");

}

window.onload = function() {

    let params = new URLSearchParams(window.location.search);

    if (params.has('name') || params.has('category') || params.has('market') || params.has('start_date') || params
        .has('end_date')) {

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