@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>
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

        th,
        td {
            /* width: 150px; */
            /*  text-align: center;*/
            /*border: 0px !important;*/
        }

        table {
            width: 100% !important;
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
   /* border: none !important;*/
    height: 40px;
    padding: 10px 15px;
    border: 1px solid #bdc0c7 !important
     }
     
    </style>

    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css'>
    <?php
    use App\Models\Market;
    use App\Models\SubmittedSurvey;
    ?>

    <div class="bg-light space">
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
                    <div id="validationError">
                        
                    </div>
                    <form id="searchReport" action="{{ route('frontend.report.filter') }}" method="get" >
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
                                                    <label for=""> Type <span class="text-danger">*</span></label>
                                                    <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
                                                    <select name="type[]" id="type">
                                                        <!-- <option value=""> Select Type</option> -->
                                                        @if (isset($types) && count($types) > 0)
                                                            @foreach ($types as $typeValue)
                                                                @if (request()->type)
                                                                    <option value="{{ $typeValue->id }}"
                                                                        {{ is_array(request()->type) && in_array($typeValue->id, request()->type) ? 'selected' : '' }}>
                                                                        {{ ucfirst($typeValue->name) }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $typeValue->id }}"
                                                                        {{ $typeValue->id == @$type->id ? 'selected' : '' }}>
                                                                        {{ ucfirst($typeValue->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-12 mb-3" id="categoryWraper">
                                                    <label id="category_error" for=""> Category <span class="text-danger">*</span></label>
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
                                                                @else
                                                                    <option value="{{ $cat->id }}"
                                                                        {{ in_array($cat->id, $selectedCategories) ? 'selected' : '' }}>
                                                                        {{ ucfirst($cat->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <div class="col-md-4 col-12 mb-3">
                                                    <label id="zone_error" for=""> Zone <span class="text-danger">*</span></label>
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
                                                                    <option value="{{ $zones->id }}"
                                                                        {{ $zones->id == @$zone->id ? 'selected' : '' }}>
                                                                        {{ ucfirst($zones->name) }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-12 mb-3" id="marketWraper">
                                                    <label for="" id="market_error"> Store <span class="text-danger">*</span></label>
                                                    <select name="market[]" id="market" multiple
                                                        multiselect-search="true" multiselect-select-all="true">
                                                        <!-- <option value=""> Select Zone</option> -->
                                                        @if (isset($filterMarkets) && count($filterMarkets) > 0)
                                                            @foreach ($filterMarkets as $filters)
                                                                @if (request()->market)
                                                                    <option value="{{ $filters->id }}"
                                                                        {{ is_array(request()->market) && in_array($filters->id, request()->market) ? 'selected' : '' }}>
                                                                        {{ $filters->name }}-{{$filters->id}}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $filters->id }}"
                                                                        {{ in_array($filters->id, $markets) ? 'selected' : '' }}>
                                                                        {{ ucfirst($filters->name) }}
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
                                                    <label id="start_date_error" for="">Date <span class="text-danger">*</span></label>
                                                    @if (request()->start_date)
                                                    <input type="date" class="date-n" id="start_date" name="start_date[]" placeholder="" value="{{ \Carbon\Carbon::parse(request()->start_date['0'])->format('Y-m-d') }}" max="{{$today}}">
                                                    @else
                                                    <input type="date" class="date-n" id="start_date" name="start_date[]" placeholder="" value="{{ \Carbon\Carbon::parse(@$survey->start_date)->format('Y-m-d') }}" max="{{$today}}">
                                                    @endif
                                                </div>
                                                {{-- <div class="col-md-4 col-12 mb-3">
                                                    <label for=""> Date </label>
                                                    
                                                    <select name="start_date[]" id="start_date">

                                                        @foreach ($survey_select as $row)
                                                            <?php
                                                            $formattedStartDate = $row->only_date;
                                                            
                                                            $displayDate = \Carbon\Carbon::parse($formattedStartDate)->format('d-m-Y');
                                                            $zoneStartDate = optional(@$survey)->start_date ? date('Y-m-d', strtotime($survey->start_date)) : null;
                                                            
                                                            // $isSelected = request('start_date') == $formattedStartDate;
                                                            $isSelected = request('start_date') == $formattedStartDate || $zoneStartDate == $formattedStartDate;
                                                            ?>

                                                            <option value="{{ $formattedStartDate }}"
                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                  {{  customt_date_format($displayDate) }}
                                                               

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="col-md-4 col-12">
                                                    <label class="d-lg-block d-none"> &nbsp; </label>
                                                    <button class="button th-btn search-store ms-3 mt-0" type="submit">
                                                        Search </button>
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

                @if (isset($data) && count($data) > 0)
                    <div class="col-xl-12 mx-auto mt-3 ">
                        <div class="table-height-store2 mb-5">
                            <div class="zone-child my-4">
                                <div class="box-key">
                                    <div class="color-box loss-red"></div>
                                    <span>Highest Price</span>
                                </div>
                                <div class="box-key">
                                    <div class="color-box profit-green"></div>
                                    <span>Lowest Price</span>
                                </div>
                                {{-- <div class="">
                                    <a class="btn btn-success" href="{{route('frontend.product.mobile.view', request()->query())}}">Change view</a>
                                </div> --}}
                            </div>
                            <div class="table-container">

                                <table class="table table-bordered table-striped" align="center" border="1" width="90%"
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
                                                
                                                $marketIds = SubmittedSurvey::where('survey_id', $surverID)->pluck('market_id')->toArray();
                                                $marketIds = array_unique($marketIds);
                                                $markets = Market::where('status', '1')->whereIn('id', $marketIds)->orderBy('name', 'asc')->get();
                                                
                                                ?>

                                                <thead>
                                                    <!-- Table Header -->
                                                    <tr class="">
                                                        <th colspan="{{ 3 + $maxMarketCount }}"
                                                            class="text-center bg-head text-white fw-bold">
                                                            Price Collected on:
                                                             {{  customt_date_format($surveyName) }}

                                                            {{-- Price Collected in : {{ strtoupper($surveyName) }} --}}
                                                        </th>
                                                    </tr>

                                                    <tr bgcolor="#f1f1f1" class="zone-tbl-head">
                                                        <th class="sticky-1" align="center" style=""><div class="width1">Commodities</div></th>
                                                        <th class="sticky-2" align="center"><div class="width2">Brand</div></th>
                                                        <th class="sticky-3" align="center"><div class="width3">Unit</div></th>
                                                        <div>@if (isset($markets) && count($markets) > 0)
                                                            @foreach ($markets as $market)
                                                                <th align="center" class="text-center">{{ ucfirst($market->name) }}</th>
                                                            @endforeach
                                                        @endif</div>
                                                        
                                                    </tr>
                                                </thead>

                                                @foreach ($data['categories'] as $categoryName => $categoryData)
                                                    <!-- Category Row -->
                                                    <tr>
                                                        <td colspan="{{ 3 + count($markets) }}"
                                                            class="bg-light fw-bold" class="text-center">
                                                            {{ strtoupper($categoryName) }}
                                                        </td>
                                                    </tr>

                                                    @foreach ($categoryData->groupBy('commodity_id') as $commodityId => $commodityData)
                                                        @php
                                                            $firstRow = $commodityData->first();
                                                            $commodityName = $firstRow->commodity->name ?? 'N/A';
                                                            $uniquePrices = $commodityData
                                                                ->pluck('amount')
                                                                ->unique()
                                                                ->values();
                                                            $minPrice = $uniquePrices->min();
                                                            $maxPrice = $uniquePrices->max();
                                                            $isSamePrice = $uniquePrices->count() === 1;
                                                        @endphp
                                                        <tr>
                                                            <td class="sticky-1">{{ $commodityName }}</td>
                                                            <td class="sticky-2">{{ $firstRow->brand->name ?? 'N/A' }}
                                                            </td>
                                                            <td class="sticky-3">{{ $firstRow->unit->name ?? 'N/A' }}</td>

                                                            @foreach ($markets as $market)
                                                                @php
                                                                    $marketPrice = $commodityData
                                                                        ->where('market_id', $market->id)
                                                                        ->first();
                                                                    $bgColor = 'transparent';
                                                                    $textColor = 'black';
                                                                     $fontWeight = '200';
                                                                    if ($marketPrice) {
                                                                        if (!$isSamePrice) {
                                                                            if ($marketPrice->amount == $minPrice) {
                                                                                $fontWeight = '500 !important';
                                                                                $textColor = '#228522!important';
                                                                            } elseif (
                                                                                $marketPrice->amount == $maxPrice
                                                                            ) {
                                                                                $fontWeight = '500 !important';
                                                                                $textColor = '#e52929 !important';
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <td
                                                                    style="background-color: {{ $bgColor }}; color: {{ $textColor }}; font-weight: {{ $fontWeight }}" class="text-center">
                                                                    {{ $marketPrice && $marketPrice->amount ? '$' . $marketPrice->amount : '-' }}
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
            </div>

        </div>
    </div>
    </div>

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
                    showError('#type_error', 'Please select at least one type');
                }

                // Validate Category
                if ($('#category').val() === null || $('#category').val().length === 0) {
                    isValid = false;
                    showError('#category_error', 'Please select at least one category');
                }

                // Validate Zone
                if ($('#zone').val() === null || $('#zone').val().length === 0) {
                    isValid = false;
                    showError('#zone_error', 'Please select at least one zone');
                }

                // Validate Market
                if ($('#market').val() === null || $('#market').val().length === 0) {
                    isValid = false;
                    showError('#market_error', 'Please select at least one market');
                }

                // Validate Start Date
                if (!$('#start_date').val()) {
                    isValid = false;
                    showError('#start_date_error', 'Please select a date');
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

                                $("#searchReport").validate().settings.rules["market[]"] = {
                                    required: true
                                };

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
    </script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
@endsection
