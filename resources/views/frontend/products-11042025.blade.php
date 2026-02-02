@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- breadcrumb -->
<!-- <div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
    <div class="container">
        <div class="breadcumb-content">
            <h2 class="breadcumb-title">Products</h2>

        </div>
    </div>
</div> -->
<style>
    .no-found{
    margin-bottom: 0;
    font-size: 20px;
    color: #000;
    padding: 20px 0 5px;
}

select{
    height: 41px;
    background-color: #fff;
    border: 1px solid #c2c6cf;
}

</style>

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css'>
<?php 
use App\Models\Market;
use App\Models\SubmittedSurvey;
?>

<div class="bg-light space">
    <div class="container">
        <div class="row gy-4  justify-content-center">
            <div class="title-area mb-0 pb-0 text-center">
                <h2 class="sec-title mb-1 mgtt-20 font-30">Ministry of Industry, Commerce and Consumer Affairs</h2>

                <h5 class="sec-title mb-0">
                    A Look at 
                    <?php
                    if(isset($types) && count($types)>0){
                        $typeCount = count($types);
                        foreach($types as $keyss=>$valuess){
                            echo ucwords($valuess->name); echo ($keyss<$typeCount-1)?', ':'';
                        }
                    }
                    ?>
                     <br>
                    {{-- @if(@$zone->name )
                    {{ucwords(@$zone->name??'Unknown Zone')}}
                    @endif --}}

                </h5>
            </div>
            <div class="mt-0">

                <form action="{{route('frontend.report.filter')}}" method="get"> 
                    <div class="row">
                        <div class="zone-parent4">
                            {{-- <div class="text-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#demo">
                                    <i class="fa fa-filter" aria-hidden="true"></i>Filter
                                </button>
                            </div> --}}
                            <div id="demo" class="mt-3 mb-3 show">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for=""> Type </label>
                                                
                                                <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true">
                                                    <!-- <option value=""> Select Type</option> -->
                                                    @if(isset($types) && count($types) > 0)
                                                    @foreach($types as $typeValue)
                                                        @if(request()->type)
                                                            <option value="{{ $typeValue->id }}" {{ (is_array(request()->type) && in_array($typeValue->id, request()->type)) ? 'selected' : '' }}>{{ ucfirst($typeValue->name) }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $typeValue->id }}" {{ ($typeValue->id == @$type->id) ? 'selected' : '' }}>{{ ucfirst($typeValue->name) }}
                                                            </option>
                                                        @endif

                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4 col-12 mb-3" id="categoryWraper">
                                                <label for=""> Category </label>
                                                <select name="category[]" id="category" multiple multiselect-search="true" multiselect-select-all="true">
                                                    <!-- <option value=""> Select Category</option> -->
                                                    @if(isset($categoryy) && count($categoryy) > 0)
                                                    @foreach($categoryy as $cat)
                                                    @if(request()->zone)
                                                        <option value="{{ $cat->id }}" {{ (is_array(request()->category) && in_array($cat->id, request()->category)) ? 'selected' : '' }}>{{ ucfirst($cat->name) }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $cat->id }}" {{ ($cat->id == @$zone->id) ? 'selected' : '' }}>{{ ucfirst($cat->name) }}
                                                        </option>
                                                    @endif
                                                 @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4 col-12 mb-3">
                                                <label for=""> Zone </label>
                                                <select name="zone[]" id="zone" multiple multiselect-search="true" multiselect-select-all="true">
                                                    <!-- <option value=""> Select Zone</option> -->
                                                    @if(isset($zonesss) && count($zonesss) > 0)
                                                    @foreach($zonesss as $zones)
                                                    @if(request()->zone)
                                                        <option value="{{ $zones->id }}" {{ (is_array(request()->zone) && in_array($zones->id, request()->zone)) ? 'selected' : '' }}>
                                                            {{ $zones->name }}
                                                            </option>

                                                        {{-- <option value="{{ $zones->id }}" 
                                                                {{ (request()->zone == $zones->id || (isset($zone->id) && $zone->id == $zones->id)) ? 'selected' : '' }}>
                                                                {{ $zones->name }}
                                                            </option> --}}
                                                    @else
                                                        <option value="{{ $zones->id }}" {{ ($zones->id == @$zone->id) ? 'selected' : '' }}>{{ ucfirst($zones->name) }}
                                                        </option>
                                                    @endif
                                                    
                                                 @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            <div class="col-md-4 col-12 mb-3" id="marketWraper">
                                                <label for=""> Market </label>
                                                <select name="market[]" id="market" multiple multiselect-search="true" multiselect-select-all="true">
                                                    <!-- <option value=""> Select Zone</option> -->
                                                    @if(isset($filterMarkets) && count($filterMarkets) > 0)
                                                    @foreach($filterMarkets as $filters)
                                                    @if(request()->market)
                                                        <option value="{{ $filters->id }}" {{ (is_array(request()->market) && in_array($filters->id, request()->market)) ? 'selected' : '' }}>
                                                            {{ $filters->name }}
                                                            </option>
                                                    @else
                                                        <option value="{{ $filters->id }}" {{ (in_array($filters->id, $selectedCategories)) ? 'selected' : '' }}>{{ ucfirst($filters->name) }}
                                                        </option>
                                                    @endif
                                                    
                                                 @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-4 col-12 mb-3">
                                                <label for=""> Date </label>
                                                <select name="start_date" id="">
                                                    <option value="">Select Date</option>
                                                    
                                                    @foreach($survey_select as $row)
                                                        <?php
                                                            $formattedStartDate = $row->only_date; 

                                                            $displayDate = \Carbon\Carbon::parse($formattedStartDate)->format('d-m-Y');
                                                            $zoneStartDate = optional(@$survey)->start_date ? date('Y-m-d', strtotime($survey->start_date)) : null;

                                                            $isSelected = request('start_date') == $formattedStartDate;
                                                            // $isSelected = request('start_date') == $formattedStartDate || $zoneStartDate == $formattedStartDate;
                                                        ?>
                                                        
                                                        <option value="{{ $formattedStartDate }}" {{ $isSelected ? 'selected' : '' }}>
                                                            {{ $displayDate }}

                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                <div class="col-md-4 col-12">
                                    <label class="d-block"> &nbsp; </label>
                                    <button class="button th-btn search-store ms-3 mt-0" type="submit"> Search </button>
                                </div> 
                                <div class="col-md-4 col-12">
                                    <label class="d-block"> &nbsp; </label>
                                    <a href="{{route('frontend.stores')}}" class="button th-btn search-store ms-3 mt-0"> Reset </a>
                                </div>
                            </div>
                            </div>
                            </div>

                            <!-- <div class=" zone-tbll-flex1">
                                       <a class="btn btn-secondary btn-sm"
                                        href="{{route('frontend.report.download.export', request()->query())}}"
                                        title=""><i class="fas fa-file-download"></i>Export</a>
                                </div> -->
                        </div>

                         <div class="zone-child mt-0">
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
                </form>

            </div>


            <div class="col-xl-12 mx-auto mt-3 ">
                <div class="table-height-store mb-5">
                    
                    <table class="table" align="center" border="1" width="90%" bordercolordark="Black" bordercolor="Black" bordercolorlight="Black" cellspacing="0">
                        <tbody>

                            <?php
                                $maxMarketCount = 0;

                                // First loop: determine the max number of markets
                                if(isset($data) && count($data) > 0){
                                    foreach($data as $surveyName => $surveyData) {
                                        $surveyID = $surveyData['survey_id'];

                                        $marketIds = \App\Models\SubmittedSurvey::where('survey_id', $surveyID)
                                            ->pluck('market_id')
                                            ->unique()
                                            ->toArray();

                                        $markets = \App\Models\Market::where('status', '1')
                                            ->whereIn('id', $marketIds)
                                            ->get();

                                        $maxMarketCount = max($maxMarketCount, $markets->count());
                                    }
                                }
                            ?>

                            @if(isset($data) && count($data) > 0)
                                @foreach($data as $surveyName => $data)
                                    <!-- Survey Name Header Row -->
                                    <?php
                                    $surverID = $data['survey_id'];

                                    $marketIds = SubmittedSurvey::where('survey_id', $surverID)->pluck('market_id')->toArray();
                                    $marketIds = array_unique($marketIds);
                                    $markets = Market::where('status', '1')
                                        ->whereIn('id', $marketIds)
                                        ->orderBy('name', 'asc')
                                        ->get();

                                    ?>
                                    <tr>
                                        <td colspan="{{ 3 + ($maxMarketCount) }}" class="text-center bg-info text-white fw-bold">
                                            Survey: {{ strtoupper($surveyName) }}
                                        </td>
                                    </tr>

                                    <!-- Table Header -->
                                    <tr bgcolor="#f1f1f1" class="zone-tbl-head">
                                        <td align="center">Commodities</td>
                                        <td align="center">Brand</td>
                                        <td align="center">Unit</td>
                                        @if(isset($markets) && count($markets) > 0)
                                            @foreach($markets as $market)
                                                <th align="center">{{ ucfirst($market->name) }}</th>
                                            @endforeach
                                        @endif
                                    </tr>

                                    @foreach($data['categories'] as $categoryName => $categoryData)
                                        <!-- Category Row -->
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
                                                <td>{{ $firstRow->brand->name ?? 'N/A' }}</td>
                                                <td>{{ $firstRow->unit->name ?? 'N/A' }}</td>

                                                @foreach($markets as $market)
                                                    @php
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
                                                    <td style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                                        {{ ($marketPrice && $marketPrice->amount) ? '$' . $marketPrice->amount : '-' }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="100%" align="center">No data found.</td>
                                </tr>
                            @endif
                        </tbody>

                    </table>


                </div>

            </div>
        </div>
    </div>
</div>
</div>
                           


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

    
    $(document).ready(function(){
        $("#type").on('change', function(){
            var type = $(this).val();
            if(type && type.length > 0){
                var url = "{{ route('frontend.type.category') }}";
                // url = url.replace(':type', type);
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        type:type
                    },

                    success: function(response) {
                        console.log(response)
                      if (response.success && Array.isArray(response.data)) {
                        
                        $("#category").next(".multiselect-dropdown").remove();
                        $("#category").remove();

                        $("#type").next(".multiselect-dropdown").remove();
                        $("#zone").remove();
                        $("#market").next(".multiselect-dropdown").remove();
                        // $("#edit-category").next(".multiselect-dropdown").remove();
                        // $("#edit-market").next(".multiselect-dropdown").remove();

                        // $("#zone").next(".multiselect-dropdown").remove();
                        // $("#category").next(".multiselect-dropdown").remove();
                        // $("#type").next(".multiselect-dropdown").remove();
                        // $("#surveyor").next(".multiselect-dropdown").remove();
                       
                        const $newSelect = $('<select>', {
                          id: 'category',
                          name: 'category[]',
                          class: 'multiselect-dropdown',
                          multiple: true,
                          'multiselect-search': 'true',
                          'multiselect-select-all': 'true',
                        });

                        response.data.forEach(item => {
                          $newSelect.append(new Option(item.name, item.id));
                        });

                        $("#categoryWraper").append($newSelect);

                        if (typeof MultiselectDropdown !== 'undefined') {
                          MultiselectDropdown(document.getElementById("edit-market"));
                        }
                      }
                    },
                    error: function(xhr) {
                      console.error("Error:", xhr.responseText);
                    }

                    // success: function(response) {
                    //     if (response.success) {
                    //         let data = response.data;
                    //         let dropdown = $('#category');

                    //         dropdown.empty(); // clear all options
                    //         // dropdown.append('<option value="">Select Category</option>');

                    //         // data.forEach(function(item) {
                    //         //     dropdown.append(
                    //         //         `<option value="${item.id}">${item.name}</option>`
                    //         //     );
                    //         // });

                    //         // console.log("Dropdown updated successfully.");
                    //     }
                    // }

                })
            }
        })

        $("#zone").on('change', function(){
            var zones = $(this).val();
            if(zones && zones.length > 0){
                var url = "{{ route('frontend.zone.markets') }}";
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        zones:zones
                    },

                    success: function(response) {
                        console.log(response)
                      if (response.success && Array.isArray(response.data)) {
                        
                        $("#category").next(".multiselect-dropdown").remove();
                        $("#zone").remove();

                        $("#type").next(".multiselect-dropdown").remove();
                        $("#zone").remove();
                        $("#market").next(".multiselect-dropdown").remove();
                        $("#market").remove();
                        
                        const $newSelectMaket = $('<select>', {
                          id: 'market',
                          name: 'market[]',
                          class: 'multiselect-dropdown',
                          multiple: true,
                          'multiselect-search': 'true',
                          'multiselect-select-all': 'true',
                        });

                        response.data.forEach(item => {
                          $newSelectMaket.append(new Option(item.name, item.id));
                        });

                        $("#marketWraper").append($newSelectMaket);

                        if (typeof MultiselectDropdown !== 'undefined') {
                          MultiselectDropdown(document.getElementById("edit-market"));
                        }
                      }
                    },
                    error: function(xhr) {
                      console.error("Error:", xhr.responseText);
                    }
                })
            }
        })
    });
</script>
<script src="{{ asset('admin/multi/multiselect-dropdown.js') }}"></script>
 <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
@endsection