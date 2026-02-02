@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .no-found {
            margin-bottom: 0;
            font-size: 20px;
            color: #000;
            padding: 20px 0 5px;
        }

        select {
            height: 41px;
            background-color: #fff;
            border: 1px solid #c2c6cf;
        }

        .search-store2 {
            background-color: var(--theme-color);
            min-width: auto;
            padding: 13px 18px;
            margin-top: 0px;
            position: relative;
            z-index: 2;
            overflow: hidden;
            vertical-align: middle;
            display: inline-block;
            text-transform: uppercase;
            text-align: center;
            border: none;
            color: var(--white-color);
            font-family: var(--body-font);
            font-size: 14px;
            font-weight: 600;
            line-height: 1;
            border-radius: 30px;
            -webkit-transition: all 0.4s ease-in-out;
            transition: all 0.4s ease-in-out;
        }

        .search-store2:hover {
            background-color: #05b363 !important;
            min-width: auto;
            padding: 13px 18px;
            margin-top: 0px;
            color: var(--white-color);
        }

    
    
.multiselect-dropdown span.optext, .multiselect-dropdown span.placeholder {
    margin-right: 0em;
}
 .date-n{
    /*box-shadow: inset 3px 3px 6px #BFC3CF, inset -3px -3px 6px #ffffff;
    box-shadow: inset 3px 3px 6px #BFC3CF, inset -3px -3px 6px #ffffff;*/
background-color: #fff !important;
border-radius: 8px;
cursor: default;
display: -ms-flexbox;
-ms-flex-wrap: wrap;
-webkit-justify-content: space-between;
-ms-flex-pack: justify;
-webkit-transition: all 100ms;
transition: all 100ms;
border: none !important;
height: 40px;
padding: 10px 15px;
 border: 1px solid #bdc0c7 !important
 }

 .carousel-caption {
    position: absolute;
    right: 15%;
    top: 9.25rem;
    left: 11%;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    color: #fff;
    text-align: left;
    width: 50%;
  }

  .carousel-caption h3,
  .carousel-caption p {
    color: #fff;
  }
.store-price {
    display: flex;
    justify-content: space-between;
    padding: 7px 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 12px !important;
    color: black !important;
}
  .store-price.collapsed .fa-plus {
    display: inline;
  }
 
  .store-price .fa-plus {
    display: none;
  }
 
  .store-price .fa-minus {
    display: inline;
  }
 
  .store-price.collapsed .fa-minus {
    display: none;
  }
  .price-green {
    background-color: transparent;
    color: #28a745;
    padding: 2px 8px;
    border-radius: 5px;
    text-align: right;
  }

  .price-red {
    background-color: transparent;
    color: #dc3545;
    padding: 2px 8px;
    border-radius: 5px;
    text-align: right;
  }

  .store-price.collaps {
    background-color: #f4f4f4;
  }

  .loss-red {
    background-color: red;
  }

  .profit-green {
    background-color: green;
  }

  .store-price.collapsed.collaps {
    background-color: #fff;
  }
.accordion-item{

    border-bottom: 1px solid #e9ecef;
}
#list-data {
  display: none;
}

/* Show on mobile only */
@media (max-width: 767.98px) {
  #list-data {
   
     display: none;
  }

  /* Optional: hide table-data on mobile */
  #table-data {
    display: block;
  }

  .btn-info.active{
    background-color: #4ca32e;
  }
  
}
.accordion-button {
    font-size: 13px !important;
    font-weight: 500;
}
.filterBtn {
    background: #31cb72;
    color: #fff;
    border-radius: 3px;
    font-size: 14px;
    padding: 7px 14px;
}
.card_form select#type, .card_form select#start_date {
    padding: 5px 15px;
}


.flatpickr-current-month {
    font-size:16px;
    line-height: inherit;
    font-weight: 300;
    display: flex;
    text-align: center;
    justify-content: space-around;
}

.flatpickr-current-month .numInputWrapper {
    padding-top: 5px;
}
/* .accordion-button{
background:rgb(236, 236, 236) !important;
} */
 .accordion-button:focus {
    z-index: 9999;
    background: #e7f1ff !important;
  
}
        .card2 .accordion-button:after
 {
    display: none;
}
.store-price.accordion-button span {
    display: inline-flex;
    align-items: center;     /* Align vertically center */
    flex-wrap: nowrap;       /* Keep all content in one line */
    gap: 8px;                /* Space between icon and text */
}

.store-price.accordion-button i {
    font-size: 16px;
    line-height: 1;
    margin-right: 4px;
}
.store-price.accordion-button {
    text-align: left;        /* Align all content to the left */
}

.custom-outline-btn {
    border: 1px solid black;
    color: black !important;
    background-color: transparent !important;
}

.custom-outline-btn:hover,
.custom-outline-btn:focus,
.custom-outline-btn:active {
    color: black !important;
    background-color: transparent !important;
    border-color: black !important;
    box-shadow: none !important;
}

.accordion-button:hover {
    z-index: initial !important;
}

.form-control[readonly]{
    background-color: white !important;
    background-color: #fff !important;
}
#start_date{
border: 1px solid #c2c6cf;
}
    </style>

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css'>
    <?php
    use App\Models\Market;
    use App\Models\SubmittedSurvey;
    use App\Models\Survey;
    use App\Models\Zone;
    ?>

    <div class="bg-light space pb-0">
        <div class="container">

            <div class="row gy-4  justify-content-center">
                <div class="title-area mb-0 pb-0 text-center">
                    <h2 class="sec-title mb-1 mgtt-20 font-30">{{ priceCollectionHeading() }}</h2>

                    <h5 class="sec-title mb-0">
                        A Look at
                        <?php
                        if (isset($types) && count($types) > 0) {
                            if (is_array(request()->type)) {
                                $typeCount = count(request()->type);
                            } else {
                                $typeCount = 0;
                            }
                            foreach ($types as $keyss => $valuess) {
                                if (request()->type) {
                                    if (is_array(request()->type) && in_array($valuess->id, request()->type)) {
                                        echo ucwords($valuess->name);
                                        echo $keyss < $typeCount - 1 ? ', ' : '';
                                    }
                                } else {
                                    if ($valuess->id == @$type->id) {
                                        echo ucwords($valuess->name);
                                    }
                                }
                            }
                        }
                        ?>
                        <br>
                        {{-- @if (@$zone->name)
                    {{ucwords(@$zone->name??'Unknown Zone')}}
                    @endif --}}

                    </h5>
                </div>

                <div class="mt-0">

                    <form id="searchReport" action="{{ route('frontend.report.filter') }}" method="get">
                        <div class="row">
                            <div class="zone-parent4">
                                {{-- <div class="text-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#demo">
                                    <i class="fa fa-filter" aria-hidden="true"></i>Filter
                                </button>
                            </div> --}}
                                <div id="demo" class="mt-3 mb-3 show">
                                    <div class="card card_form">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 col-12 mb-3">
                                                    <label id="type_error" for=""> Type <span class="text-danger">*</span>&nbsp;<span id="type_error"></span></label>
                                                    <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
                                                    <select name="type[]" id="type">
                                                        <option value=""> Select Type</option>
                                                        @if (isset($types) && count($types) > 0)
                                                            @foreach ($types as $typeValue)
                                                                @if (request()->type)
                                                                    <option value="{{ $typeValue->id }}"
                                                                        {{ is_array(request()->type) && in_array($typeValue->id, request()->type) ? 'selected' : '' }}>
                                                                        {{ ucfirst($typeValue->name) }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $typeValue->id }}">
                                                                        {{ ucfirst($typeValue->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-12 mb-3" id="categoryWraper">
                                                    <label  for=""> Category <span class="text-danger">*</span>&nbsp;<span id="category_error"></span></label>
                                                    <select name="category[]" id="category" multiple
                                                        multiselect-search="true" multiselect-select-all="true">
                                                        <!-- <option value=""> Select Category</option> -->
                                                        @if (isset($categoryy) && count($categoryy) > 0)
                                                            @foreach ($categoryy as $cat)
                                                                @if (request()->category)
                                                                    <option value="{{ $cat->id }}"
                                                                        {{ is_array(request()->category) && in_array($cat->id, request()->category) ? 'selected' : '' }}>
                                                                        {{ ucfirst($cat->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-12 mb-3">
                                                    <label  for=""> Zone <span class="text-danger">*</span>&nbsp;<span id="zone_error"></span></label>
                                                    <select name="zone[]" id="zone" multiple multiselect-search="true"
                                                        multiselect-select-all="true">
                                                        <!-- <option value=""> Select Zone</option> -->
                                                        @if (isset($zonesss) && count($zonesss) > 0)
                                                            @foreach ($zonesss as $zones)
                                                                @if (request()->zone)
                                                                    <option value="{{ $zones->id }}"
                                                                        {{ is_array(request()->zone) && in_array($zones->id, request()->zone) ? 'selected' : '' }}>
                                                                        {{ $zones->name }}
                                                                    </option>

                                                                    {{-- <option value="{{ $zones->id }}" 
                                                                {{ (request()->zone == $zones->id || (isset($zone->id) && $zone->id == $zones->id)) ? 'selected' : '' }}>
                                                                {{ $zones->name }}
                                                            </option> --}}
                                                                @else
                                                                    <option value="{{ $zones->id }}">
                                                                        {{ ucfirst($zones->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-12 mb-3" id="marketWraper">
                                                    <label  for=""> Store <span class="text-danger">*</span>&nbsp;<span id="market_error"></span></label>
                                                    <select name="market[]" id="market" multiple
                                                        multiselect-search="true" multiselect-select-all="true">

                                                        <!-- <option value=""> Select Zone</option> -->
                                                        @if (isset($filterMarkets) && count($filterMarkets) > 0)
                                                            @foreach ($filterMarkets as $filters)
                                                                @if (request()->market)
                                                                    <option value="{{ $filters->id }}"
                                                                        {{ is_array(request()->market) && in_array($filters->id, request()->market) ? 'selected' : '' }}>
                                                                        {{ $filters->name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <?php 
                                                $today = date('Y-m-d');
                                                ?>
                                                <div class="col-md-4 col-12 mb-3">
                                                    <label id="start_date_error" for="">Date <span class="text-danger">*</span>&nbsp;<span id="start_date_error"></span></label>
                                                   <input type="text"
                                                    id="start_date"
                                                    name="start_date[]"
                                                    placeholder="dd-mm-yyyy"
                                                    value="{{ request()->start_date ? \Carbon\Carbon::parse(request()->start_date[0])->format('d-m-Y') : '' }}"
                                                    class="form-control flatpickr-input"
                                                    style="height: 40px;"
                                                    readonly="readonly"
                                                    autocomplete="off">
                                                   
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <label class="d-lg-block d-none"> &nbsp; </label>
                                                    <button class="button th-btn search-store ms-3 mt-0" type="submit">Search </button>
                                                    <a href="{{ route('frontend.stores') }}"
                                                        class="button search-store2 ms-3 mt-0"> Reset </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class=" zone-tbll-flex1">
                                           <a class="btn btn-secondary btn-sm"
                                            href="{{ route('frontend.report.download.export', request()->query()) }}"
                                            title=""><i class="fas fa-file-download"></i>Export</a>
                                    </div> -->
                                </div>


                            </div>
                    </form>

                </div>

                @if(isset($data))
                @if (isset($data) && count($data) > 0)
                    <div class="col-xl-12 mx-auto mt-3 ">
                        <div class="table-height-store2 mb-3">
                            {{-- <div class="zone-child my-4">
                                <div class="box-key">
                                    <div class="color-box loss-red"></div>
                                    <span>Highest Price</span>
                                </div>
                                <div class="box-key">
                                    <div class="color-box profit-green"></div>
                                    <span>Lowest Price</span>
                                </div>
                            </div> --}}

                            <div class= "d-flex justify-content-end" style="width:100%;">
                                  <a class="filterBtn" style="padding: 3px 14px;color:white;" href="{{route('frontend.export.products.report', request()->query())}}">Export</a>
                              </div> 

                             <div class="mobile-toggle d-block d-md-none mt-2 mb-3">
           <button id="btnTable" class="toggle-btn btn custom-outline-btn btn-sm">
    <i class="fas fa-table"></i> Table View
</button>
<button id="btnList" class="toggle-btn btn custom-outline-btn btn-sm">
    <i class="fas fa-list"></i> List View
</button>

                                </div>
                                <div class="table-container mt-3" id="list-data">
                                    <?php
                                    $maxMarketCountlist = 0;
                                    $datalist = $data;

                                    if (isset($datalist) && count($datalist) > 0) {
                                        foreach ($datalist as $surveyNameList => $surveyDataList) {
                                            $surveyID = $surveyDataList['survey_id'];
                                            $marketIds = \App\Models\SubmittedSurvey::where('survey_id', $surveyID)->pluck('market_id')->unique()->toArray();
                                            $markets = \App\Models\Market::where('status', '1')->whereIn('id', $marketIds)->get();
                                            $maxMarketCountlist = max($maxMarketCountlist, $markets->count());
                                        }
                                    }
                                    ?>

                                    @if (isset($datalist) && count($datalist) > 0)
                                        <div class="accordion" id="mainAccordion">
                                            @foreach ($datalist as $surveyNameList => $datalist)
                                                <?php
                                                $surverID = $datalist['survey_id'];
                                                $surveyDatas = Survey::find($surverID);
                                                $marketIds = \App\Models\SubmittedSurvey::where('survey_id', $surverID)->pluck('market_id')->unique()->toArray();
                                                $markets = \App\Models\Market::where('status', '1')->whereIn('id', $marketIds)->orderBy('name', 'asc')->get();
                                                $accord_id = 1;
                                                 $zone_id = $surveyDatas->zone_id;
                                                $zone_name = Zone::find($zone_id);
                                                ?>

                                                <div class="bg-dark text-white px-3 py-2 text-center">
                                                    Price Collected on 
                                                    {{ date('d-m-Y', strtotime($surveyDatas->start_date)) }}  ({{ $zone_name->name }})
                                                    
                                                </div>

                                                @foreach ($datalist['categories'] as $categoryName => $categoryData)
                                                    @php
                                                        $outerCollapseID = 'collapseOuter_' . $accord_id;
                                                        $innerAccordionID = 'innerAccordion_' . $accord_id;
                                                    @endphp

                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading_{{ $accord_id }}">
                                                            <button class="accordion-button collapsed bg-white rounded-0 border-top-0 py-2"
                                                                    type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#{{ $outerCollapseID }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="{{ $outerCollapseID }}">
                                                                {{ ucfirst($categoryName) }}
                                                            </button>
                                                        </h2>

                                                        <div id="{{ $outerCollapseID }}"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="heading_{{ $accord_id }}"
                                                            data-bs-parent="#mainAccordion">

                                                            <div class="accordion-body px-2">
                                                                <div class="card2">
                                                                    <div class="accordion" id="{{ $innerAccordionID }}">
                                                                        @foreach ($categoryData->groupBy('commodity_id') as $commodityId => $commodityData)
                                                                            @php
                                                                                $firstRow = $commodityData->first();
                                                                                $commodityName = $firstRow->commodity->name ?? 'N/A';
                                                                                $uniquePrices = $commodityData->pluck('amount')->unique()->values();
                                                                                $minPrice = $uniquePrices->min();
                                                                                $maxPrice = $uniquePrices->max();
                                                                                $isSamePrice = $uniquePrices->count() === 1;
                                                                                $innerCollapseID = 'collapseCommodity_' . $commodityId . '_' . $loop->index . '_' . $accord_id;
                                                                            @endphp

                                                                            <div class="accordion-item">
                                                                                <h2 class="accordion-header"
                                                                                    id="headingCommodity_{{ $commodityId }}_{{ $loop->index }}_{{ $accord_id }}">
                                                                                    <button class="store-price accordion-button collapsed px-3 py-2"
                                                                                            type="button"
                                                                                            data-bs-toggle="collapse"
                                                                                            data-bs-target="#{{ $innerCollapseID }}"
                                                                                            aria-expanded="false"
                                                                                            aria-controls="{{ $innerCollapseID }}">
                                                                                        <span>
                                                                                            <i class="fas fa-plus"></i>
                                                                                            <i class="fas fa-minus"></i>
                                                                                            {{ !empty($commodityName) ? '🧃 ' . $commodityName : 'N/A' }}

{{ (!empty($firstRow->brand->name) && $firstRow->brand->name != 'No Name') 
    ? '🏷️ ' . $firstRow->brand->name 
    : 'N/A' }}

{{ !empty($firstRow->unit->name) ? '⚖️ ' . $firstRow->unit->name : 'N/A' }}
                                                                                        </span>
                                                                                    </button>

                                                                                </h2>

                                                                                <div id="{{ $innerCollapseID }}"
                                                                                    class="accordion-collapse collapse"
                                                                                    aria-labelledby="headingCommodity_{{ $commodityId }}_{{ $loop->index }}_{{ $accord_id }}"
                                                                                    data-bs-parent="#{{ $innerAccordionID }}">

                                                                                    <div class="accordion-body ps-3">
                                                                                        <!-- <div class="store-price px-0">
                                                                                            <span>Brand</span>
                                                                                            <span>{{ $firstRow->brand->name ?? 'N/A' }}</span>
                                                                                        </div>
                                                                                        <div class="store-price px-0">
                                                                                            <span>Unit</span>
                                                                                            <span>{{ $firstRow->unit->name ?? 'N/A' }}</span>
                                                                                        </div> -->

                                                                                        @foreach ($markets as $market)
                                                                                            @php
                                                                                                $marketPrice = $commodityData->where('market_id', $market->id)->first();
                                                                                            @endphp

                                                                                            <div class="border-bottom px-0 py-1">
                                                                                                <span style="font-size:12px !important;color:black;">{{ ucfirst($market->name) }}</span>
                                                                                                <div class="row">
                                                                                                    <div class="col-3" style="font-size:12px !important;color:black;">Generic</div>
                                                                                                       <div class="col-3 price-red border-right" style="font-size:12px !important;">{{ $marketPrice && $marketPrice->amount ? '$' . $marketPrice->amount : '-' }}</div>
                                                                                          <div class="col-1"></div>
                                                                                                    <div class="col-3" style="font-size:12px !important;color:black;">Original</div>
                                                                                                       <div class="col-2 price-red text-right" style="font-size:12px !important;">{{ $marketPrice && $marketPrice->amount_1 ? '$' . $marketPrice->amount_1 : '-' }}</div>
                                                                                                </div>
                                                                                                <!-- <div class="price-red">
                                                                                                    {{ $marketPrice && $marketPrice->amount ? '$' . $marketPrice->amount : 'N/A' }}
                                                                                                </div> -->
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php $accord_id++; @endphp
                                                @endforeach
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                    <!-- tabular data  -->
                            <div class="table-container mt-3" id="table-data">

                                <table class="table table-bordered" align="center" border="1" width="90%"
                                    bordercolordark="Black" bordercolor="Black" bordercolorlight="Black" cellspacing="0">
                                    <tbody>

                                        <?php
                                        $maxMarketCount = 0;
                                        if (isset($data) && count($data) > 0) {
                                            foreach ($data as $surveyName => $surveyData) {
                                                $surveyID = $surveyData['survey_id'];
                                        
                                                $marketIds = \App\Models\SubmittedSurvey::where('survey_id', $surveyID)->pluck('market_id')->unique()->toArray();
                                        
                                                $markets = \App\Models\Market::where('status', '1')->whereIn('id', $marketIds)->get();
                                        
                                                $maxMarketCount = max($maxMarketCount, $markets->count());
                                            }
                                        }
                                        ?>

                                        @if (isset($data) && count($data) > 0)
                                            @foreach ($data as $surveyName => $data)
                                                <?php
                                                $surverID = $data['survey_id'];
                                                $surveyDatas1 = Survey::find($surverID);
                                                $marketIds = SubmittedSurvey::where('survey_id', $surverID)->pluck('market_id')->toArray();
                                                $marketIds = array_unique($marketIds);
                                                $markets = Market::where('status', '1')->whereIn('id', $marketIds)->orderBy('name', 'asc')->get();
                                                 $zone_id = $surveyDatas->zone_id;
                                                $zone_name = Zone::find($zone_id);
                                                
                                                ?>

                                                <thead>
                                                    <!-- Table Header -->
                                                    <tr class="">
                                                        <th colspan="{{ 6 + (count($markets) < 7 ? 7 : count($markets)) }}"
                                                            class="text-center bg-head text-white fw-bold">
                                                            Price Collected on:
                                                            {{ date('d-m-Y', strtotime($surveyDatas1->start_date)) }}  ({{ $zone_name->name }})

                                                          

                                                            {{-- Price Collected in : {{ strtoupper($surveyName) }} --}}
                                                        </th>
                                                    </tr>

                                                    <tr bgcolor="#f1f1f1" class="zone-tbl-head">
                                                        <th class="sticky-1" align="center" rowspan="2"><div class="width1">Commodities</div></th>
                                                        {{-- <th class="sticky-2" align="center" rowspan="2">Brand</th> --}}
                                                        <th class="sticky-2" align="center" rowspan="2"><div class="width1">Content</div></th>
                                                        @if (isset($markets) && count($markets) > 0)
                                                            @foreach ($markets as $market)
                                                                <th align="center" colspan="2" class="text-center">{{ ucfirst($market->name) }}<br>
                                                                    {{-- <span style="font-size: 13px;">Generic</span> |
                                                                    <span style="font-size: 13px;">Original</span> --}}
                                                                </th>
                                                            @endforeach
                                                        @endif
                                                    </tr>
                                                            <tr>
                                                    @if (isset($markets) && count($markets) > 0)
                                                            @foreach ($markets as $market)
                                                                <th class="text-center">Generic</th>
                                                                 <th class="text-center">Original</th>
                                                        @endforeach
                                                    @endif
                                                            </tr>
                                                </thead>

                                                @foreach ($data['categories'] as $categoryName => $categoryData)
                                                    <!-- Category Row -->
                                                    <tr>
                                                        <td colspan="{{ 3 + count($markets) }}"
                                                            class="bg-light fw-bold">
                                                            {{ strtoupper($categoryName) }}
                                                        </td>
                                                    </tr>

                                                    @foreach ($categoryData->groupBy('commodity_id') as $commodityId => $commodityData)
                                                        @php
                                                            $firstRow = $commodityData->first();
                                                            $commodityName = $firstRow->commodity->name ?? 'N/A';
                                                            $uniquePrices = $commodityData
                                                                ->pluck('amount', 'amount_1')
                                                                ->unique()
                                                                ->values();
                                                            $minPrice = $uniquePrices->min();
                                                            $maxPrice = $uniquePrices->max();
                                                            $isSamePrice = $uniquePrices->count() === 1;
                                                        @endphp
                                                        <tr>
                                                            <td class="sticky-1">{{ $commodityName }}</td>
                                                            {{-- <td class="sticky-2">{{ !empty($firstRow->brand->name) && strtolower($firstRow->brand->name) !== 'no name' ? $firstRow->brand->name : 'N/A' }}
                                                            </td> --}}
                                                            <td class="sticky-2">{{ $firstRow->unit->name ?? 'N/A' }}</td>

                                                            @foreach ($markets as $market)
                                                                @php
                                                                    $marketPrice = $commodityData
                                                                        ->where('market_id', $market->id)
                                                                        ->first();
                                                                    $bgColor = 'transparent';
                                                                    $textColor = 'black';

                                                                    if ($marketPrice) {
                                                                        if (!$isSamePrice) {
                                                                            if ($marketPrice->amount == $minPrice) {
                                                                                $bgColor = '#228522'; // Green
                                                                                $textColor = 'white !important';
                                                                            } elseif (
                                                                                $marketPrice->amount == $maxPrice
                                                                            ) {
                                                                                $bgColor = '#e52929'; // Red
                                                                                $textColor = 'white !important';
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <td class="text-center">
                                                                    {{ $marketPrice && $marketPrice->amount ? '$' . $marketPrice->amount : '-' }}
                                                                   
                                                                </td>

                                                                <td  class="text-center">
                                                                    {{ @$marketPrice->amount_1 ? '$' : '' }}{{ @$marketPrice->amount_1 ?? '-' }} 
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
                @else
                    <div class="row gy-4 text-center">
                        <div class="col-xl-12 mx-auto mt-3 ">
                            <h4 class="text-danger">No record available</h4>
                        </div>
                    </div>
                @endif
                @else
                    <div class="row gy-4 text-center">
                        <div class="col-xl-12 mx-auto mt-3 ">
                            <strong class="text-danger">Please apply filters to generate the Price Collection report.</strong>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
    </div>
<script>
$(document).ready(function () {

        let wasMobile = window.innerWidth <= 767;

window.addEventListener('resize', function () {
    let isNowDesktop = window.innerWidth > 767;

    if (wasMobile && isNowDesktop) {
        location.reload(); // Reload when switching from mobile to desktop
    }

    // Update the state for next resize
    wasMobile = window.innerWidth <= 767;
});
    $('#type').on('change', function () {
        const selectedTypeId = $(this).val();

        $.ajax({
            url: "{{ route('frontend.get.highlighted.dates') }}",
            type: "POST",
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({ type_id: selectedTypeId }),
            success: function (highlightedDates) {
                console.log("Highlighted dates:", highlightedDates);
                
                // Define colors for each type_id
                const typeColors = {
                    1: "#00a258", // green
                    2: "#007bff", // blue
                    3: "#ff9800", // orange
                    4: "#e91e63"  // red
                };

                // Initialize or reinitialize flatpickr
                flatpickr("#start_date", {
                    dateFormat: "d-m-Y",
                    maxDate: "{{ date('d-m-Y') }}",
                    disableMobile: true,
                    onDayCreate: function(dObj, dStr, fp, dayElem) {
                        const date = fp.formatDate(dayElem.dateObj, "Y-m-d");
                        
                        if (highlightedDates.hasOwnProperty(date)) {
                            const typeId = highlightedDates[date];
                            const color = typeColors[typeId] || "#00a258";

                            dayElem.style.backgroundColor = color;
                            dayElem.style.color = "#fff";
                            dayElem.style.borderRadius = "50%";
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching highlighted dates:", error);
            }
        });
    });

    // Trigger change event on page load if there's a selected type
    $('#type').trigger('change');
});
</script>
    <script>
        $(document).ready(function () {
            flatpickr('.date-n', {
                dateFormat: "Y-m-d",  // match your value format
                altInput: true,
                altFormat: "d-m-Y"    // optional, if you want a friendly display
            });
            $('#searchReport').on('submit', function (e) {
                let isValid = true;
                let errorMessages = [];

                // Clear any previous error messages
                $('.error-message').remove();

                // Validate Type
                if ($('#type').val() === null || $('#type').val().length === 0) {
                    isValid = false;
                    showError('#type_error', '( Please select at least one type )');
                }

                // Validate Category
                if ($('#category').val() === null || $('#category').val().length === 0) {
                    isValid = false;
                    showError('#category_error', '( Please select at least one category )');
                }

                // Validate Zone
                if ($('#zone').val() === null || $('#zone').val().length === 0) {
                    isValid = false;
                    showError('#zone_error', '( Please select at least one zone )');
                }

                // Validate Market
                if ($('#market').val() === null || $('#market').val().length === 0) {
                    isValid = false;
                    showError('#market_error', '( Please select at least one market )');
                }

                // Validate Start Date
                if (!$('#start_date').val()) {
                    isValid = false;
                    showError('#start_date_error', '( Please select a date )');
                }

                if (!isValid) {
                    e.preventDefault(); // prevent form submission if invalid
                }
            });

            function showError(selector, message) {
                // selector = $("#validationError");
                // $(selector).after('<div class="error-message" style="color:red; font-size:13px;">' + message + '</div>');
                var $error = $('<span class="error-message" style="color:#ba202f; font-size:12px;">' + message + '</span>');
                $(selector).after($error);
                setTimeout(function () {
                    $error.fadeOut(300, function () {
                       
                    });
                }, 4000);
            }
        });

    </script>
    <script>
        $(document).ready(function() {
            $("#type").on('change', function() {
                var type = $(this).val();
                if (type && type.length > 0) {
                    var url = "{{ route('frontend.type.category') }}";
                    // url = url.replace(':type', type);
                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {
                            type: type
                        },

                        success: function(response) {
                            // console.log(response)
                            if (response.success && Array.isArray(response.data)) {
                                $("#category").next(".multiselect-dropdown").remove();
                                $("#category").remove();

                                $("#type").next(".multiselect-dropdown").remove();
                                $("#zone").next(".multiselect-dropdown").remove();
                                $("#zone").val(null).trigger("change");
                                $("#market").next(".multiselect-dropdown").remove();

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
                            }else{
                                $("#category").next(".multiselect-dropdown").remove();
                                $("#category").remove();

                                $("#type").next(".multiselect-dropdown").remove();
                                $("#zone").next(".multiselect-dropdown").remove();
                                $("#zone").val(null).trigger("change");
                                $("#market").next(".multiselect-dropdown").remove();

                                const $newSelect = $('<select>', {
                                    id: 'category',
                                    name: 'category[]',
                                    class: 'multiselect-dropdown',
                                    multiple: true,
                                    'multiselect-search': 'true',
                                    'multiselect-select-all': 'true',
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

                    })
                }
            })

            $("#zone").on('change', function() {
                var zones = $(this).val();
                if (zones && zones.length > 0) {
                    console.log('inside the if');
                    var url = "{{ route('frontend.zone.markets') }}";
                    $.ajax({
                        url: url,
                        type: "GET",
                        data: {
                            zones: zones
                        },

                        success: function(response) {
                            if (response.success && Array.isArray(response.data)) {

                                $("#category").next(".multiselect-dropdown").remove();
                                // $("#zone").remove();

                                $("#type").next(".multiselect-dropdown").remove();
                                $("#zone").next(".multiselect-dropdown").remove();

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
                                    $newSelectMaket.append(new Option(item.name, item
                                        .id));
                                });

                                $("#marketWraper").append($newSelectMaket);

                                if (typeof MultiselectDropdown !== 'undefined') {
                                    MultiselectDropdown(document.getElementById("edit-market"));
                                }
                            }else{
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

                                $("#marketWraper").append($newSelectMaket);
                            }
                        },
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText);
                        }
                    })
                }else{
                    $("#market").next(".multiselect-dropdown").remove();
                    $("#market").remove();
                    
                    const $newSelectMaket = $('<select>', {
                        id: 'market',
                        name: 'market[]',
                        class: 'multiselect-dropdown',
                        multiple: false,
                        'multiselect-search': 'false',
                        'multiselect-select-all': 'false',
                    });

                    $newSelectMaket.append(new Option('Select', '', true, true)).find('option:first').attr('disabled', true);
                    $("#marketWraper").append($newSelectMaket);
                }
            })
        });
    </script>
    <script src="{{ asset('admin/multi/multiselect-dropdown.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropdowns = document.querySelectorAll('select[multiple][multiselect-dropdown]');

            dropdowns.forEach(select => {
                const observer = new MutationObserver(() => {
                    const dropdownContainer = select.parentElement.querySelector(".dropdown-list");
                    if (dropdownContainer) {
                        // Prevent auto-close on short lists
                        dropdownContainer.onclick = function(e) {
                            e.stopPropagation(); // stop closing on click
                        };
                    }
                });

                observer.observe(select.parentElement, {
                    childList: true,
                    subtree: true,
                });
            });
        });
          document.getElementById('btnTable').addEventListener('click', function () {
  this.classList.add('active');
  document.getElementById('btnList').classList.remove('active');
  document.getElementById('table-data').style.display = 'block';
  document.getElementById('list-data').style.display = 'none';
});

document.getElementById('btnList').addEventListener('click', function () {
  this.classList.add('active');
  document.getElementById('btnTable').classList.remove('active');
  document.getElementById('table-data').style.display = 'none';
  document.getElementById('list-data').style.display = 'block';
});
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
@endsection
