@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<style>
   
    .checkbox-dropdown {
        position: relative;
    }
    .checkbox-dropdown .dropdown-list {
        display: none;
        position: absolute;
        z-index: 1000;
        background: #fff;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
    }
    .checkbox-dropdown.on .dropdown-list {
        display: block;
    }
    .chart-container {
        position: relative;
        width: 100%; 
        height: 400px;
        margin-bottom: 30px;
    }
    .dropdown-label {
        cursor: pointer;
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: white;
    }
    .dropdown-option {
        padding: 5px 10px;
        cursor: pointer;
    }
    .dropdown-option:hover {
        background-color: #f8f9fa;
    }
    .check-all {
        font-weight: bold;
        border-bottom: 1px solid #eee;
        margin-bottom: 5px;
    }
   #border-top{
border-top: 0.1px solid;
    border-color: #e7d3d3;
    }
</style>

<div class="px-3  mt-4">
    <div class="container-fluid">
    
        <!-- Accordion Container -->
        <div class="accordion" id="reportsAccordion">
            
            <!-- Price Observation Section -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingObservation">
                      <button class="accordion-button @if(request()->has('type_id') || request()->has('start_date') || request()->has('end_date')) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseObservation" aria-expanded="@if(request()->has('type_id') || request()->has('start_date') || request()->has('end_date')) true @else false @endif" aria-controls="collapseObservation">
                        <i class="fas fa-binoculars me-2"></i> Surveillance Report
                    </button>
                </h2>
               <div id="collapseObservation" class="accordion-collapse collapse @if(request()->has('type_id') || request()->has('start_date') || request()->has('end_date')) show @endif" aria-labelledby="headingObservation" data-bs-parent="#reportsAccordion">
                    <div class="accordion-body p-0">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="text-center">Surveillance Report</h4> 
                                <div class="row align-items-center d-flex mb-3">
                                    <div class="col-xl-8 mb-3">
                                        @php
                                        $filtersApplied = request()->has('type_id') || request()->has('start_date') || request()->has('end_date');
                                        $typeName = '';
                                        
                                        if ($filtersApplied) {
                                            if ($typeId == 1) {
                                                $typeName = "Food Basket";
                                            } elseif ($typeId == 2) {
                                                $typeName = "Hardware and Building Materials";
                                            } elseif ($typeId == 3) {
                                                $typeName = "Furniture and Appliances";
                                            } elseif ($typeId == 4) {
                                                $typeName = "Medication";
                                            }
                                        } else {
                                            $typeId = '';
                                            $typeName = '';
                                        }
                                        @endphp
                                    </div>
                                    <div class="col-xl-4 text-end">
                                        @if($filtersApplied)
                                            <a href="{{ route('admin.surveillance.report.export', [
                                                'type_id'    => request('type_id'),
                                                'start_date' => request('start_date'),
                                                'end_date'   => request('end_date'),
                                            ]) }}" class="btn btn-secondary btn-sm">
                                                <i class="fas fa-file-download"></i> Export to Excel
                                            </a>
                                        @endif
                                    </div>
                                </div>

                               <form method="GET" class="row" id="observationFilterForm">
                                    <div class="col-md-3">
                                        <label>Survey Type</label>
                                        <select name="type_id" class="form-control">
                                            <option value="">-- All Types --</option>
                                            <option value="1" {{ request('type_id') == 1 ? 'selected' : '' }}>Food Basket</option>
                                            <option value="2" {{ request('type_id') == 2 ? 'selected' : '' }}>Hardware and Building Materials</option>
                                            <option value="3" {{ request('type_id') == 3 ? 'selected' : '' }}>Furniture and Appliances</option>
                                            <option value="4" {{ request('type_id') == 4 ? 'selected' : '' }}>Medication</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Year</label>
                                        <select name="year" class="form-control">
                                            <option value="">-- Select Year --</option>
                                            @foreach($years as $year)
                                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Quarter</label>
                                        <select name="quarter" class="form-control">
                                            <option value="">-- Select Quarter --</option>
                                            <option value="Q1" {{ request('quarter') == 'Q1' ? 'selected' : '' }}>Q1 (Jan - Mar)</option>
                                            <option value="Q2" {{ request('quarter') == 'Q2' ? 'selected' : '' }}>Q2 (Apr - Jun)</option>
                                            <option value="Q3" {{ request('quarter') == 'Q3' ? 'selected' : '' }}>Q3 (Jul - Sep)</option>
                                            <option value="Q4" {{ request('quarter') == 'Q4' ? 'selected' : '' }}>Q4 (Oct - Dec)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mt-4">
                                        <button class="btn btn-success btn-sm me-2" type="submit">Search</button>
                                        <a href="{{ route('admin.surveillance.report.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                                    </div>
                                </form>

                                @if($typeName)
                                    <h4 class="header-title mb-0 font-weight-bold my-3">
                                        Surveillance Report {{ $typeName ? 'for ' . $typeName : '' }} 
                                        {{ request('year') ? ' - ' . request('year') : '' }} 
                                        {{ request('quarter') ? ' ' . request('quarter') : '' }}
                                    </h4>
                                @endif

                                @if(isset($grouped) && count($grouped) > 0)
                                    <div class="table-responsive mt-2">
                                        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
                                            <thead>
                                                <tr>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Category</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Commodities</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Brand</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Size</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Max Price</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Min Price</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Median</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Average</th>
                                                    <th style="background-color: #D9D9D9; font-weight: bold; border: 1px solid #000; padding: 6px;">Availability %</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($grouped as $categoryName => $commodities)
                                                    @php $firstRow = true; $rowCount = $commodities->count(); @endphp
                                                    @foreach($commodities as $data)
                                                        <tr>
                                                            @if($firstRow)
                                                                <td rowspan="{{ $rowCount }}" style="border: 1px solid #000; background-color: #F2F2F2; font-weight: bold; padding: 6px;">
                                                                    {{ strtoupper($categoryName) }}
                                                                </td>
                                                                @php $firstRow = false; @endphp
                                                            @endif
                                                            <td style="border: 1px solid #000; padding: 6px;">{{ $data['commodityName'] }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">{{ $data['brandName'] }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">{{ $data['unitName'] }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">${{ number_format($data['max'], 2) }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">${{ number_format($data['min'], 2) }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">${{ number_format($data['median'], 2) }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">${{ number_format($data['avg'], 2) }}</td>
                                                            <td style="border: 1px solid #000; padding: 6px;">{{ number_format($data['availability'], 2) }}%</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($filtersApplied ?? false)
                                    <div class="mt-3 text-center">
                                        <hr>No Record Found.<hr>
                                    </div>
                                @else
                                    <div class="mt-3 text-center text-danger">
                                        <hr>Please apply filters to generate the Surveillance Report.<hr>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Price Fluctuations Section -->
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingFluctuations">
                        <button class="accordion-button @if(request()->has('pf_categories') || request()->has('pf_start_date') || request()->has('pf_end_date')) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFluctuations" aria-expanded="@if(request()->has('pf_categories') || request()->has('pf_start_date') || request()->has('pf_end_date')) true @else false @endif" aria-controls="collapseFluctuations">
                            <i class="fas fa-chart-line me-2"></i> Price Fluctuations Report Chart
                        </button>
                    </h2>
                                <div id="collapseFluctuations" class="accordion-collapse collapse @if(request()->has('pf_categories') || request()->has('pf_start_date') || request()->has('pf_end_date')) show @endif" aria-labelledby="headingFluctuations" data-bs-parent="#reportsAccordion">
                        <div class="accordion-body p-0">
                            <div class="card">
                                <div class="card-body"> 
                                    <h4 class="text-center">Price Fluctuations Report Chart</h4>
                                    <form method="post" action="{{ route('admin.price.fluctuations') }}" class="row" id="priceFluctuationForm">
                                        @csrf

                                        {{-- Categories --}}
                                        <div class="col-md-3">
                                            <label>Categories</label>
                                            <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="pf_category">
                                                <div class="dropdown-label">Select Categories</div>
                                                <div class="dropdown-list shadow border rounded bg-white w-100 p-2">
                                                    <label class="dropdown-option d-block check-all">
                                                        <input type="checkbox" class="form-check-input pf-category-check-all me-1"> Select All
                                                    </label>
                                                    @foreach($categories as $category)
                                                        <label class="dropdown-option d-block">
                                                            <input type="checkbox" class="form-check-input pf-category-checkbox me-1"
                                                                name="pf_categories[]" value="{{ $category->id }}"
                                                                {{ in_array($category->id, request()->get('pf_categories', [])) ? 'checked' : '' }}>
                                                            {{ $category->name }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <span class="error pf_categories_error text-danger" style="font-size: 13px;"></span>
                                            </div>
                                        </div>

                                        {{-- Commodities --}}
                                        <div class="col-md-3">
                                            <label>Commodities</label>
                                            <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="pf_commodity">
                                                <div class="dropdown-label">Select Commodities</div>
                                                <div class="dropdown-list shadow border rounded bg-white w-100 p-2" id="pf-commodities-container">
                                                    {{-- Dynamically filled by JavaScript --}}
                                                </div>
                                                <span class="error pf_commodities_error text-danger" style="font-size: 13px;"></span>
                                            </div>
                                        </div>

                                        {{-- Year --}}
                                        <div class="col-md-3">
                                            <label>Year</label>
                                            <select name="q_year" class="form-control">
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}" {{ request('q_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error q_year_error text-danger" style="font-size: 13px;"></span>
                                        </div>

                                        {{-- Quarter --}}
                                        <div class="col-md-3">
                                            <label for="quarter-select" class="form-label" style="margin-bottom:0px;">Quarters</label>
                                            <select name="q_selected_quarter" id="quarter-select" class="form-select w-100" style="line-height: 1.5 !important;">
                                                <option value="">Select Quarter</option>
                                                <option value="Q1" {{ request('q_selected_quarter') == 'Q1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                                                <option value="Q2" {{ request('q_selected_quarter') == 'Q2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                                                <option value="Q3" {{ request('q_selected_quarter') == 'Q3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                                                <option value="Q4" {{ request('q_selected_quarter') == 'Q4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                                            </select>
                                            <span class="error q_selected_quarter_error text-danger" style="font-size: 13px;"></span>
                                        </div>

                                        <div class="col-md-12 mt-3">
                                            <button class="btn btn-success btn-sm me-2" type="submit">Search</button>
                                            <a href="{{ route('admin.surveillance.report.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                                        </div>
                                    </form>


                                    @if(isset($pf_groupedData) && !empty($pf_groupedData))
                                            <div class="row mt-3">
                                                <div class="col-md-12 text-end">
                                                    <button class="btn btn-danger btn-sm" id="downloadPdf">
                                                        <i class="fas fa-file-pdf"></i> Download as PDF
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="chart-container" id="priceChartContainer" style="min-height: 500px;">
                                                <canvas id="priceChart"></canvas>
                                            </div>

                                            {{-- Include JS Libraries --}}
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

                                            <script>
                                                const ctx = document.getElementById('priceChart').getContext('2d');

                                                // Full labels (Commodity | Brand | Unit)
                                                const fullLabels = {!! json_encode(array_keys(reset($pf_groupedData))) !!};

                                                // Commodity-only labels for X-axis
                                                const commodities = fullLabels.map(label => label.split('|')[0].trim());

                                                // Dataset: One for each month in the quarter
                                                const datasets = [
                                                    @foreach($pf_groupedData as $month => $commodityPrices)
                                                        {
                                                            label: '{{ $month }}',
                                                            backgroundColor: '#' + Math.floor(Math.random()*16777215).toString(16),
                                                            data: {!! json_encode(array_values($commodityPrices)) !!}
                                                        },
                                                    @endforeach
                                                ];

                                                const chart = new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: commodities,
                                                        datasets: datasets
                                                    },
                                                    options: {
                                                        responsive: true,
                                                        maintainAspectRatio: false,
                                                        plugins: {
                                                            title: {
                                                                display: true,
                                                                text: 'Price Fluctuations - {{ $q_selected_quarter }} {{ $q_year }}',
                                                                color: '#000',
                                                                font: {
                                                                    size: 18,
                                                                    weight: 'bold'
                                                                }
                                                            },
                                                            legend: {
                                                                position: 'bottom',
                                                                labels: {
                                                                    color: '#000'
                                                                }
                                                            },
                                                            tooltip: {
                                                                callbacks: {
                                                                    title: function(context) {
                                                                        const index = context[0].dataIndex;
                                                                        const datasetIndex = context[0].datasetIndex;
                                                                        const month = context[0].chart.data.datasets[datasetIndex].label;
                                                                        const fullLabel = fullLabels[index];
                                                                        return `${fullLabel}\nMonth: ${month}`;
                                                                    },
                                                                    label: function(context) {
                                                                        const value = context.formattedValue;
                                                                        return `Avg Price: $${parseFloat(value).toFixed(2)}`;
                                                                    }
                                                                }
                                                            }
                                                        },
                                                        scales: {
                                                            x: {
                                                                title: {
                                                                    display: true,
                                                                    text: 'Commodities',
                                                                    color: '#000'
                                                                },
                                                                ticks: {
                                                                    color: '#000',
                                                                    maxRotation: 90,
                                                                    minRotation: 60
                                                                }
                                                            },
                                                            y: {
                                                                ticks: {
                                                                    color: '#000',
                                                                    callback: value => '$' + value.toFixed(2)
                                                                },
                                                                title: {
                                                                    display: true,
                                                                    text: 'Price in $',
                                                                    color: '#000'
                                                                }
                                                            }
                                                        }
                                                    }
                                                });

                                                // PDF Download
                                                document.getElementById('downloadPdf').addEventListener('click', function() {
                                                    const { jsPDF } = window.jspdf;
                                                    const chartContainer = document.getElementById('priceChartContainer');

                                                    html2canvas(chartContainer).then(canvas => {
                                                        const imgData = canvas.toDataURL('image/png');
                                                        const pdf = new jsPDF('landscape');
                                                        const imgProps = pdf.getImageProperties(imgData);
                                                        const pdfWidth = pdf.internal.pageSize.getWidth();
                                                        const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                                                        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                                                        pdf.save('price-fluctuations-{{ $q_selected_quarter }}-{{ $q_year }}.pdf');
                                                    });
                                                });
                                            </script>

                                        @else
                                            <div class="mt-3 text-center text-danger">
                                                <hr>Please apply filters to generate the Price Fluctuations Report Chart.<hr>
                                            </div>
                                        @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Comparative Average Prices Section -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingComparative">
                        <button class="accordion-button @if(request()->has('ca_categories') || request()->has('ca_start_date') || request()->has('ca_end_date')) @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseComparative" aria-expanded="@if(request()->has('ca_categories') || request()->has('ca_start_date') || request()->has('ca_end_date')) true @else false @endif" aria-controls="collapseComparative">
                                <i class="fas fa-balance-scale me-2"></i> Comparative Average Prices Report Chart
                            </button>
                        </h2>
                        <div id="collapseComparative" class="accordion-collapse collapse @if(request()->has('ca_categories') || request()->has('ca_start_date') || request()->has('ca_end_date')) show @endif" aria-labelledby="headingComparative" data-bs-parent="#reportsAccordion">
                            <div class="accordion-body p-0">
                            <div class="accordion-body p-0">
                                <div class="card">
                                    <div class="card-body"> 
                                        <h4 class="text-center">Comparative Average Prices Report Chart</h4>
                                        <form method="post" action="{{ route('admin.comparative.average') }}" class="row" id="comparativeAverageForm">
                                            @csrf

                                            {{-- Categories --}}
                                            <div class="col-md-3">
                                                <label>Categories</label>
                                                <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="ca_category">
                                                    <div class="dropdown-label">Select Categories</div>
                                                    <div class="dropdown-list shadow border rounded bg-white w-100 p-2">
                                                        <label class="dropdown-option d-block check-all">
                                                            <input type="checkbox" class="form-check-input ca-category-check-all me-1"> Select All
                                                        </label>
                                                        @foreach($categories as $category)
                                                            <label class="dropdown-option d-block">
                                                                <input type="checkbox" class="form-check-input ca-category-checkbox me-1"
                                                                    name="ca_categories[]" value="{{ $category->id }}"
                                                                    {{ in_array($category->id, request()->get('ca_categories', [])) ? 'checked' : '' }}>
                                                                {{ $category->name }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <span class="error ca_categories_error text-danger" style="font-size: 13px;"></span>
                                                </div>
                                            </div>

                                            {{-- Commodities --}}
                                            <div class="col-md-3">
                                                <label>Commodities</label>
                                                <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="ca_commodity">
                                                    <div class="dropdown-label">Select Commodities</div>
                                                    <div class="dropdown-list shadow border rounded bg-white w-100 p-2" id="ca-commodities-container">
                                                        <!-- AJAX loaded -->
                                                    </div>
                                                    <span class="error ca_commodities_error text-danger" style="font-size: 13px;"></span>
                                                </div>
                                            </div>

                                            {{-- Year --}}
                                            <div class="col-md-3">
                                                <label>Year</label>
                                                <select name="q_year" class="form-control">
                                                    @foreach($years as $year)
                                                        <option value="{{ $year }}" {{ request('q_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="error q_year_error text-danger" style="font-size: 13px;"></span>
                                            </div>

                                            {{-- Quarter --}}
                                            <div class="col-md-3">
                                                <label for="quarter-select" class="form-label" style="margin-bottom:0px;">Quarters</label>
                                                <select name="q_selected_quarter" id="quarter-select" class="form-select w-100" style="line-height: 1.5 !important;">
                                                    <option value="">Select Quarter</option>
                                                    <option value="Q1" {{ request('q_selected_quarter') == 'Q1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                                                    <option value="Q2" {{ request('q_selected_quarter') == 'Q2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                                                    <option value="Q3" {{ request('q_selected_quarter') == 'Q3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                                                    <option value="Q4" {{ request('q_selected_quarter') == 'Q4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                                                </select>
                                                <span class="error q_selected_quarter_error text-danger" style="font-size: 13px;"></span>
                                            </div>

                                            {{-- Submit --}}
                                            <div class="col-md-12 mt-3">
                                                <button class="btn btn-success btn-sm me-2" type="submit">Search</button>
                                                <a href="{{ route('admin.surveillance.report.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                                            </div>
                                        </form>


                                        @php
                                        $allColors = [
                                            '#2b6cb0', '#ed8936', '#a0aec0', '#ecc94b', '#4299e1', '#48bb78', '#4a5568',
                                            '#9f7aea', '#38b2ac', '#f56565', '#805ad5', '#718096', '#f6ad55', '#e53e3e'
                                        ];
                                    @endphp

                                    @if(isset($ca_groupedData) && !empty($ca_groupedData))
                                        <div class="row mt-3">
                                            <div class="col-md-12 text-end">
                                                <button class="btn btn-danger btn-sm" id="downloadComparativePdf">
                                                    <i class="fas fa-file-pdf"></i> Download as PDF
                                                </button>
                                            </div>
                                        </div>

                                        <div class="chart-container" id="comparativeChartContainer" style="height: 400px;">
                                            <canvas id="comparativeChart"></canvas>
                                        </div>

                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                                      <script>
                                            const caGroupedData = {!! json_encode($ca_groupedData) !!};
                                            const fullLabels = @json($fullCommodityNames);
                                            const markets = @json($markets);
                                            const allColors = @json($allColors);

                                            const xLabels = fullLabels.map(label => label.split('|')[0].trim());

                                            const datasets = markets.map((market, index) => {
                                                return {
                                                    label: market,
                                                    backgroundColor: allColors[index % allColors.length],
                                                    data: fullLabels.map(label => caGroupedData[label][market] ?? 0),
                                                };
                                            });

                                            new Chart(document.getElementById('comparativeChart').getContext('2d'), {
                                                type: 'bar',
                                                data: {
                                                    labels: xLabels,
                                                    datasets: datasets
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    plugins: {
                                                        title: {
                                                            display: true,
                                                            text: 'Comparative Average Prices by Market ({{ $q_selected_quarter }} {{ $q_year }})'
                                                        },
                                                        legend: {
                                                            position: 'bottom',
                                                            labels: { color: '#000' }
                                                        },
                                                        tooltip: {
                                                            callbacks: {
                                                                title: function(context) {
                                                                    const index = context[0].dataIndex;
                                                                    const market = context[0].dataset.label;
                                                                    const fullLabel = fullLabels[index];
                                                                    return `${fullLabel} | ${market}`;
                                                                },
                                                                label: function(context) {
                                                                    const value = context.raw ?? 0;
                                                                    return `Avg Price: $${parseFloat(value).toFixed(2)}`;
                                                                }
                                                            }
                                                        }
                                                    },
                                                    scales: {
                                                        x: {
                                                            ticks: {
                                                                color: '#000',
                                                                maxRotation: 90,
                                                                minRotation: 60
                                                            }
                                                        },
                                                        y: {
                                                            ticks: {
                                                                color: '#000',
                                                                callback: value => '$' + value.toFixed(2)
                                                            },
                                                            title: {
                                                                display: true,
                                                                text: 'Price in $',
                                                                color: '#000'
                                                            }
                                                        }
                                                    }
                                                }
                                            });

                                            document.getElementById('downloadComparativePdf').addEventListener('click', function () {
                                                const { jsPDF } = window.jspdf;
                                                const chartContainer = document.getElementById('comparativeChartContainer');

                                                html2canvas(chartContainer).then(canvas => {
                                                    const imgData = canvas.toDataURL('image/png');
                                                    const pdf = new jsPDF('landscape');
                                                    const pdfWidth = pdf.internal.pageSize.getWidth();
                                                    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                                                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                                                    pdf.save('comparative-prices-{{ $q_selected_quarter }}-{{ $q_year }}.pdf');
                                                });
                                            });
                                        </script>


                                    @else
                                        <div class="mt-3 text-center text-danger">
                                            <hr>Please apply filters to generate the Comparative Average Prices Report Chart<hr>
                                        </div>
                                    @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Quarterly Price Fluctuations Section -->
                    <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingQuarterly">
                                                    <button class="accordion-button @if(request()->has('q_categories') || request()->has('q_year') || request()->has('q_selected_quarters')) @else collapsed @endif" type="button" data-bs-toggle="collapse" id="border-top" data-bs-target="#collapseQuarterly" aria-expanded="@if(request()->has('q_categories') || request()->has('q_year') || request()->has('q_selected_quarters')) true @else false @endif" aria-controls="collapseQuarterly">
                                                        <i class="fas fa-calendar-alt me-2"></i> Total Average Price Fluctuations Per Quarter Prices Report Chart
                                                    </button>
                                                </h2>
                                                <div id="collapseQuarterly" class="accordion-collapse collapse @if(request()->has('q_categories') || request()->has('q_year') || request()->has('q_selected_quarters')) show @endif" aria-labelledby="headingQuarterly" data-bs-parent="#reportsAccordion">
                                                    <div class="accordion-body p-0">
                                                        <div class="card">
                                                            <div class="card-body"> 
                                                                <h4 class="text-center">Total Average Price Fluctuations Per Quarter Prices Report Chart</h4>
                                                                <form method="post" action="{{ route('admin.price.fluctuations.per.quarter') }}" class="row" id="quarterlyForm">
                                                                    @csrf
                                                                    <div class="col-md-3">
                                                                        <label>Categories</label>
                                                                        <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="q_category">
                                                                            <div class="dropdown-label">
                                                                                Select Categories
                                                                            </div>
                                                                            <div class="dropdown-list shadow border rounded bg-white w-100 p-2">
                                                                                <label class="dropdown-option d-block check-all">
                                                                                    <input type="checkbox" class="form-check-input q-category-check-all me-1"> Select All
                                                                                </label>
                                                                                @foreach($categories as $category)
                                                                                    <label class="dropdown-option d-block">
                                                                                        <input type="checkbox" class="form-check-input q-category-checkbox me-1"
                                                                                            name="q_categories[]" value="{{ $category->id }}"
                                                                                            {{ in_array($category->id, request()->get('q_categories', [])) ? 'checked' : '' }}>
                                                                                        {{ $category->name }}
                                                                                    </label>
                                                                                @endforeach
                                                                            </div>
                                                                            <span class="error q_categories_error text-danger" style="font-size: 13px;"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Commodities</label>
                                                                        <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="q_commodity">
                                                                            <div class="dropdown-label">
                                                                                Select Commodities
                                                                            </div>
                                                                            <div class="dropdown-list shadow border rounded bg-white w-100 p-2" id="q-commodities-container">
                                                                            
                                                                            </div>
                                                                            <span class="error q_commodities_error text-danger" style="font-size: 13px;"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Year</label>
                                                                        <select name="q_year" class="form-control">
                                                                            @foreach($years as $year)
                                                                                <option value="{{ $year }}" {{ isset($q_year) && $q_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <span class="error q_year_error text-danger" style="font-size: 13px;"></span>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <label>Quarters</label>
                                                                        <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="q_quarter">
                                                                            <div class="dropdown-label">
                                                                                Select Quarters
                                                                            </div>
                                                                            <div class="dropdown-list shadow border rounded bg-white w-100 p-2">
                                                                                <label class="dropdown-option d-block check-all">
                                                                                    <input type="checkbox" class="form-check-input q-quarter-check-all me-1"> Select All
                                                                                </label>
                                                                                <label class="dropdown-option">
                                                                                    <input type="checkbox" class="form-check-input q-quarter-checkbox me-1"
                                                                                        name="q_selected_quarters[]" value="Q1"
                                                                                        {{ isset($q_selected_quarters) && in_array('Q1', $q_selected_quarters) ? 'checked' : '' }}
                                >
                                                                                    Q1 (Jan-Mar)
                                                                                </label>
                                                                                <label class="dropdown-option">
                                                                                    <input type="checkbox" class="form-check-input q-quarter-checkbox me-1"
                                                                                        name="q_selected_quarters[]" value="Q2"
                                                                                        {{ isset($q_selected_quarters) && in_array('Q2', $q_selected_quarters) ? 'checked' : '' }}
                                >
                                                                                    Q2 (Apr-Jun)
                                                                                </label>
                                                                                <label class="dropdown-option">
                                                                                    <input type="checkbox" class="form-check-input q-quarter-checkbox me-1"
                                                                                        name="q_selected_quarters[]" value="Q3"
                                                                                        {{ isset($q_selected_quarters) && in_array('Q3', $q_selected_quarters) ? 'checked' : '' }}
                                >
                                                                                    Q3 (Jul-Sep)
                                                                                </label>
                                                                                <label class="dropdown-option">
                                                                                    <input type="checkbox" class="form-check-input q-quarter-checkbox me-1"
                                                                                        name="q_selected_quarters[]" value="Q4"
                                                                                        {{ isset($q_selected_quarters) && in_array('Q4', $q_selected_quarters) ? 'checked' : '' }}
                                >
                                                                                    Q4 (Oct-Dec)
                                                                                </label>
                                                                            </div>
                                                                            <span class="error q_selected_quarters_error text-danger" style="font-size: 13px;"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12 mt-3">
                                                                        <button class="btn btn-success btn-sm me-2" type="submit">Search</button>
                                                                        <a href="{{ route('admin.surveillance.report.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                                                                    </div>
                                                                </form>

                                                                @if(isset($q_quarterlyData) && !empty($q_quarterlyData))
                                                                    <div class="row mt-3">
                                                                        <div class="col-md-12 text-center">
                                                                            <h4>Graph Showing Total Average Price of Products Per Quarter</h4>
                                                                            <h5>For Year {{ $q_year }}</h5>
                                                                        </div>
                                                                        <div class="col-md-12 text-end">
                                                                            <button class="btn btn-danger btn-sm" id="downloadQuarterlyPdf">
                                                                                <i class="fas fa-file-pdf"></i> Download as PDF
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="chart-container" id="quarterlyChartContainer">
                                                                        <canvas id="quarterlyChart"></canvas>
                                                                    </div>
                                                                        @php
                                                                            $quarterMap = [
                                                                                'Q1' => 'q1',
                                                                                'Q2' => 'q2',
                                                                                'Q3' => 'q3',
                                                                                'Q4' => 'q4'
                                                                            ];
                                                                        @endphp
                                                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
                                                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                                                                    <script>
                                                                    const quarterlyData = {!! json_encode($q_quarterlyData) !!};
                                                                    const fullLabels = Object.keys(quarterlyData); // "Commodity | Brand | Unit"
                                                                    const xLabels = fullLabels.map(label => label.split('|')[0].trim()); // Only commodity name for x-axis

                                                                    const quarterMeta = {
                                                                        Q1: { key: 'q1', label: 'Q1 (Jan-Mar)', color: '#1e40af' },
                                                                        Q2: { key: 'q2', label: 'Q2 (Apr-Jun)', color: '#f97316' },
                                                                        Q3: { key: 'q3', label: 'Q3 (Jul-Sep)', color: '#10b981' },
                                                                        Q4: { key: 'q4', label: 'Q4 (Oct-Dec)', color: '#6b7280' },
                                                                    };

                                                                    // Only include quarters that were selected
                                                                    const selectedQuarters = @json($q_selected_quarters); // e.g. ['Q1', 'Q3']
                                                                    // alert(selectedQuarters);
                                                                    const datasets = selectedQuarters.map(quarter => {
                                                                        const meta = quarterMeta[quarter];
                                                                        return {
                                                                            label: meta.label,
                                                                            backgroundColor: meta.color,
                                                                            data: fullLabels.map(label => quarterlyData[label][meta.key] ?? 0)
                                                                        };
                                                                    });

                                                                    const quarterlyChart = new Chart(document.getElementById('quarterlyChart').getContext('2d'), {
                                                                        type: 'bar',
                                                                        data: {
                                                                            labels: xLabels,
                                                                            datasets: datasets
                                                                        },
                                                                        options: {
                                                                            responsive: true,
                                                                            maintainAspectRatio: false,
                                                                            plugins: {
                                                                                title: {
                                                                                    display: true,
                                                                                    text: 'Quarterly Price Fluctuations for {{ $q_year }}'
                                                                                },
                                                                                legend: {
                                                                                    position: 'bottom',
                                                                                    labels: { color: '#000' }
                                                                                },
                                                                                tooltip: {
                                                                                    callbacks: {
                                                                                        title: function(context) {
                                                                                            const index = context[0].dataIndex;
                                                                                            const quarter = context[0].dataset.label;
                                                                                            return `${fullLabels[index]} | ${quarter}`;
                                                                                        },
                                                                                        label: function(context) {
                                                                                            const value = context.raw ?? 0;
                                                                                            return `Avg Price: $${parseFloat(value).toFixed(2)}`;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            },
                                                                            scales: {
                                                                                x: {
                                                                                    ticks: {
                                                                                        color: '#000',
                                                                                        maxRotation: 90,
                                                                                        minRotation: 60
                                                                                    }
                                                                                },
                                                                                y: {
                                                                                    ticks: {
                                                                                        color: '#000',
                                                                                        callback: value => '$' + value.toFixed(2)
                                                                                    },
                                                                                    title: {
                                                                                        display: true,
                                                                                        text: 'Price in $',
                                                                                        color: '#000'
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    });

                                                                    // PDF Download
                                                                    document.getElementById('downloadQuarterlyPdf').addEventListener('click', function () {
                                                                        const { jsPDF } = window.jspdf;
                                                                        const chartContainer = document.getElementById('quarterlyChartContainer');

                                                                        html2canvas(chartContainer).then(canvas => {
                                                                            const imgData = canvas.toDataURL('image/png');
                                                                            const pdf = new jsPDF('landscape');
                                                                            const pdfWidth = pdf.internal.pageSize.getWidth();
                                                                            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                                                                            pdf.setFontSize(16);
                                                                            pdf.text('Graph Showing Total Average Price of Products Per Quarter', pdfWidth / 2, 20, { align: 'center' });
                                                                            pdf.setFontSize(14);
                                                                            pdf.text('For Year {{ $q_year }}', pdfWidth / 2, 30, { align: 'center' });

                                                                            pdf.addImage(imgData, 'PNG', 10, 40, pdfWidth - 20, pdfHeight - 40);
                                                                            pdf.save('quarterly-prices-{{ $q_year }}.pdf');
                                                                        });
                                                                    });
                                                                </script>

                                                                @else
                                                                    <div class="mt-3 text-center text-danger">
                                                                        <hr>Please apply filters to generate the Total Average Price Fluctuations Per Quarter Prices Report Chart<hr>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                    </div>

                     <!-- Total Average Price of all -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTotalAvg">
                            <button class="accordion-button {{ (request()->has('ta_categories') || request()->has('ta_commodities')) ? '' : 'collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseTotalAvg"
                                aria-expanded="{{ (request()->has('ta_categories') || request()->has('ta_commodities') || request()->has('start_date')) ? 'true' : 'false' }}"
                                aria-controls="collapseTotalAvg">
                                <i class="fas fa-chart-line me-2"></i> Total Average Price of all
                            </button>
                        </h2>
                        <div id="collapseTotalAvg" class="accordion-collapse collapse {{ (request()->has('ta_categories') || request()->has('ta_commodities')) ? 'show' : '' }}"
                            aria-labelledby="headingTotalAvg" data-bs-parent="#reportsAccordion">
                            <div class="accordion-body p-0">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="text-center">Total Average Price of all</h4>

                                        <form method="post" action="{{ route('admin.reports.total.average') }}" class="row mb-4">
                                            @csrf
                                            <div class="col-md-3">
                                                <label>Categories</label>
                                                <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="ta_category">
                                                    <div class="dropdown-label">Select Categories</div>
                                                    <div class="dropdown-list shadow border rounded bg-white w-100 p-2">
                                                        <label class="dropdown-option d-block check-all">
                                                            <input type="checkbox" class="form-check-input ta-category-check-all me-1"> Select All
                                                        </label>
                                                        @foreach($categories as $category)
                                                            <label class="dropdown-option d-block">
                                                                <input type="checkbox" class="form-check-input ta-category-checkbox me-1"
                                                                    name="ta_categories[]" value="{{ $category->id }}"
                                                                    {{ in_array($category->id, request()->get('ta_categories', [])) ? 'checked' : '' }}>
                                                                {{ $category->name }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <span class="error ta_categories_error text-danger" style="font-size: 13px;"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Commodities</label>
                                                <div class="dropdown checkbox-dropdown w-100" data-control="checkbox-dropdown" data-type="ta_commodity">
                                                    <div class="dropdown-label">Select Commodities</div>
                                                    <div class="dropdown-list shadow border rounded bg-white w-100 p-2" id="ta-commodities-container"></div>
                                                    <span class="error ta_commodities_error text-danger" style="font-size: 13px;"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Year</label>
                                                <select name="ta_year" class="form-control">
                                                    <option value="">Select Year</option>
                                                    @foreach($years as $year)
                                                        <option value="{{ $year }}" {{ old('ta_year', $ta_year ?? '') == $year ? 'selected' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="error ta_year_error text-danger" style="font-size: 13px;"></span>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Quarter</label>
                                                <select name="ta_quarter" class="form-control">
                                                    <option value="">Select Quarter</option>
                                                    <option value="Q1" {{ old('ta_quarter', $ta_quarter ?? '') == 'Q1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                                                    <option value="Q2" {{ old('ta_quarter', $ta_quarter ?? '') == 'Q2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                                                    <option value="Q3" {{ old('ta_quarter', $ta_quarter ?? '') == 'Q3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                                                    <option value="Q4" {{ old('ta_quarter', $ta_quarter ?? '') == 'Q4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                                                </select>
                                                <span class="error ta_quarter_error text-danger" style="font-size: 13px;"></span>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <button class="btn btn-success btn-sm">Search</button>
                                                <a href="{{ route('admin.surveillance.report.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                                            </div>
                                        </form>


                                        @if(!empty($averages))
                                            <div class="text-end mb-2">
                                                <button class="btn btn-danger btn-sm" id="downloadPdf"><i class="fas fa-file-pdf"></i> Download PDF</button>
                                            </div>

                                            <div id="chartContainer">
                                                <canvas id="totalAverageChart"></canvas>
                                            </div>
                                        @else
                                            <div class="mt-3 text-center text-danger">
                                                <hr>Please apply filters to generate the Total Average Price of all<hr>
                                            </div>
                                        @endif

                                        @push('scripts')
                                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

                                        <script>
                                            @if(!empty($averages))
                                                const labels = {!! json_encode(array_column($averages, 'location')) !!};
                                                const dataValues = {!! json_encode(array_column($averages, 'average')) !!};

                                                const ctx = document.getElementById('totalAverageChart').getContext('2d');
                                                new Chart(ctx, {
                                                    type: 'bar',
                                                    data: {
                                                        labels: labels,
                                                        datasets: [{
                                                            label: 'Average Price ($)',
                                                            data: dataValues,
                                                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                                                            borderColor: '#0c5da9',
                                                            borderWidth: 1
                                                        }]
                                                    },
                                                    options: {
                                                        plugins: {
                                                            title: {
                                                                display: true,
                                                                text: 'Total Average Price by Location - {{ $ta_quarter }} {{ $ta_year }}'
                                                            },
                                                            legend: {
                                                                display: false
                                                            }
                                                        },
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true,
                                                                ticks: {
                                                                    callback: value => '$' + value
                                                                },
                                                                title: {
                                                                    display: true,
                                                                    text: 'Price in $'
                                                                }
                                                            },
                                                            x: {
                                                                title: {
                                                                    display: true,
                                                                    text: 'Locations'
                                                                }
                                                            }
                                                        }
                                                    }
                                                });

                                                document.getElementById('downloadPdf').addEventListener('click', function () {
                                                    const chartContainer = document.getElementById('chartContainer');
                                                    html2canvas(chartContainer).then(canvas => {
                                                        const { jsPDF } = window.jspdf;
                                                        const imgData = canvas.toDataURL('image/png');
                                                        const pdf = new jsPDF('landscape');
                                                        const pdfWidth = pdf.internal.pageSize.getWidth();
                                                        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
                                                        pdf.addImage(imgData, 'PNG', 0, 10, pdfWidth, pdfHeight);
                                                        pdf.save(`total-average-price-{{ $ta_quarter }}-{{ $ta_year }}.pdf`);
                                                    });
                                                });
                                            @endif
                                        </script>
                                    @endpush


                            </div>
                        </div>
                    </div>

        
            </div>
    </div>
</div>





@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Expand/Collapse All Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const yearSelect = document.querySelector('select[name="q_year"]');
    const quartersBox = document.querySelector('[data-type="q_quarter"]');
    const quarterCheckboxes = document.querySelectorAll('.q-quarter-checkbox');
    const checkAllWrapper = document.querySelector('.q-quarter-check-all').closest('.dropdown-option');

    // Hide quarter section initially
    quartersBox.style.display = 'none';

    function updateQuarters() {
        const selectedYear = parseInt(yearSelect.value);
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1;

        // If no year selected, hide quarters section
        if (!selectedYear) {
            quartersBox.style.display = 'none';
            return;
        }

        // Show quarters section
        quartersBox.style.display = 'block';

        let visibleCount = 0;

        quarterCheckboxes.forEach(checkbox => {
            const label = checkbox.closest('.dropdown-option');
            const quarter = checkbox.value;
            let shouldShow = true;

            if (selectedYear > currentYear) {
                shouldShow = false;
            } else if (selectedYear === currentYear) {
                if (quarter === 'Q2' && currentMonth < 4) shouldShow = false;
                if (quarter === 'Q3' && currentMonth < 7) shouldShow = false;
                if (quarter === 'Q4' && currentMonth < 10) shouldShow = false;
            }

            if (shouldShow) {
                label.style.display = 'block';
                visibleCount++;
            } else {
                label.style.display = 'none';
                checkbox.checked = false;
            }
        });

        // Show/hide "Select All"
        if (visibleCount > 0) {
            checkAllWrapper.style.display = 'block';
        } else {
            checkAllWrapper.style.display = 'none';
            document.querySelector('.q-quarter-check-all').checked = false;
        }
    }

    // Event listener
    yearSelect.addEventListener('change', updateQuarters);

    // If year already selected on page load
    if (yearSelect.value) {
        updateQuarters();
    }
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Expand All
        document.getElementById('expandAll').addEventListener('click', function() {
            const accordions = document.querySelectorAll('.accordion-collapse');
            accordions.forEach(accordion => {
                new bootstrap.Collapse(accordion, { show: true });
            });
        });
        
        // Collapse All
        document.getElementById('collapseAll').addEventListener('click', function() {
            const accordions = document.querySelectorAll('.accordion-collapse');
            accordions.forEach(accordion => {
                new bootstrap.Collapse(accordion, { hide: true });
            });
        });

        // Store accordion state in sessionStorage when forms are submitted
        const forms = document.querySelectorAll('form[id$="Form"]');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const accordionId = this.id.replace('Form', '');
                sessionStorage.setItem('activeAccordion', accordionId);
            });
        });

        // Restore accordion state on page load
        const activeAccordion = sessionStorage.getItem('activeAccordion');
        if (activeAccordion) {
            const collapseElement = document.getElementById('collapse' + activeAccordion);
            if (collapseElement) {
                new bootstrap.Collapse(collapseElement, { show: true });
            }
            sessionStorage.removeItem('activeAccordion');
        }
    });
</script>
<script>
$(function () {
    // Initialize datepickers
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y",
        maxDate: "today",
        allowInput: true
    });

    // Initialize checkbox dropdowns
    function initializeCheckboxDropdowns() {
        $('[data-control="checkbox-dropdown"]').each(function() {
            const $dropdown = $(this);
            const $label = $dropdown.find('.dropdown-label');
            const $inputs = $dropdown.find('input[type="checkbox"]');
            
            function updateStatus() {
                const checked = $inputs.filter(':checked');
                const total = $inputs.length;
                
                if (checked.length === 0) {
                    $label.text('Select Options');
                } else if (checked.length === 1) {
                    $label.text(checked.closest('label').text().trim());
                } else if (checked.length === total) {
                    $label.text('All Selected');
                } else {
                    $label.text(`${checked.length} Selected`);
                }
            }
            
            $label.off('click').on('click', function(e) {
                e.preventDefault();
                $dropdown.toggleClass('on');
                
                // Close other dropdowns when opening one
                if ($dropdown.hasClass('on')) {
                    $('[data-control="checkbox-dropdown"]').not($dropdown).removeClass('on');
                }
            });
            
            $inputs.off('change').on('change', function() {
                updateStatus();
            });
            
            updateStatus();
        });
    }
    
    initializeCheckboxDropdowns();

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('[data-control="checkbox-dropdown"]').length) {
            $('[data-control="checkbox-dropdown"]').removeClass('on');
        }
    });

   function loadCommodities(prefix) {
    
    const selectedCategories = $(`.${prefix}-category-checkbox:checked`).map(function() {
        return $(this).val();
    }).get();
    let selectedCommodities = $(`input[name="${prefix}_commodities[]"]:checked`).map(function() {
        return $(this).val();
    }).get();


    // Use preselected from backend if nothing is currently selected
    if (selectedCommodities.length === 0 && window[`preselectedCommodities_${prefix}`]) {
        selectedCommodities = window[`preselectedCommodities_${prefix}`].map(String);
    }
     let pf_commodities = @json($pf_commodities ?? []);
    let ca_commodities = @json($ca_commodities ?? []);
    let q_commodities = @json($q_commodities ?? []);
    let ta_commodities = @json($ta_commodities ?? []);

    let commodities = [];

    if (prefix === 'pf') {
        commodities = pf_commodities;
    } else if (prefix === 'ca') {
        commodities = ca_commodities;
    } else if (prefix === 'q') {
        commodities = q_commodities;
    }else if (prefix === 'ta') {
        commodities = ta_commodities;
    }

 
    console.log("selectedCategories", selectedCategories);
    console.log("selectedCommodities", commodities);

    // $(`#${prefix}-commodities-container`).html('<small>Loading...</small>');

    if (selectedCategories.length > 0) {
        $.ajax({
            url: '{{ route("admin.get-commodities-by-categories") }}',
            type: 'GET',
            data: { 
                category_ids: selectedCategories,
                selected_commodities: selectedCommodities 
            },
           success: function (data) {
                console.log("selectedCategories", data);
                let html = '';

                if (data.length > 0) {
                    // Show loading temporarily
                    $(`#${prefix}-commodities-container`).html(`<div id="${prefix}-temp-loader"><small>Loading options...</small></div>`);

                    // Build the commodity checkboxes
                   data.forEach(function (commodity) {
                    console.log(commodity);
    const isChecked = commodities.includes(commodity.id.toString());
    const brandName = commodity.brand ? (commodity.brand.name || commodity.brand) : 'No Brand';
    const unitName = commodity.uom ? (commodity.uom.name || commodity.uom) : '';

    html += `<label class="dropdown-option d-block">
                <input type="checkbox" 
                    class="form-check-input ${prefix}-commodity-checkbox me-1"
                    name="${prefix}_commodities[]" 
                    value="${commodity.id}" ${isChecked ? 'checked' : ''}>
                ${commodity.name}${brandName ? ' (' + brandName + (unitName ? ', ' + unitName : '') + ')' : ''}
            </label>`;
});



                    // After 2 seconds, update HTML and prepend "Select All"
                    setTimeout(() => {
                        // Inject all checkbox options
                        $(`#${prefix}-commodities-container`).html(html);

                        // Add "Select All" at the top if not already present
                        if ($(`#${prefix}-commodities-container .${prefix}-commodity-check-all`).length === 0) {
                            const selectAllHTML = `<label class="dropdown-option d-block check-all">
                                <input type="checkbox" class="form-check-input ${prefix}-commodity-check-all me-1"> Select All
                            </label>`;

                            $(`#${prefix}-commodities-container`).prepend(selectAllHTML);
                        }

                        // Initialize dropdown handlers
                        initializeCheckboxDropdowns();
                        setupCheckAllHandlers();

                        // Update label count
                        const selectedCount = $(`input[name="${prefix}_commodities[]"]:checked`).length;
                        const totalCount = data.length;
                        $(`[data-type="${prefix}_commodity"] .dropdown-label`).text(
                            selectedCount > 0 ? `Selected (${selectedCount}/${totalCount})` : `Select Commodities (${totalCount})`
                        );
                    }, 2000);

                } else {
                    $(`#${prefix}-commodities-container`).html('<small>No commodities found for selected categories.</small>');
                }
            },
            error: function(xhr) {
                $(`#${prefix}-commodities-container`).html(
                    '<small class="text-danger">Error loading commodities</small>'
                );
            }
        });
    } else {
        $(`#${prefix}-commodities-container`).html('<small>Select categories first</small>');
        $(`[data-type="${prefix}_commodity"] .dropdown-label`).text('Select Commodities');
        $(`input[name="${prefix}_commodities[]"]`).prop('checked', false);
    }
}


    function setupCheckAllHandlers() {
    // Handle category Select All
    $('.pf-category-check-all, .ca-category-check-all, .q-category-check-all, .ta-category-check-all').on('change', function () {
        const prefix = $(this).hasClass('pf-category-check-all') ? 'pf' :
                      $(this).hasClass('ca-category-check-all') ? 'ca' :
                      $(this).hasClass('q-category-check-all') ? 'q' : 'ta';
        const $checkboxes = $(this).closest('.dropdown-list').find(`.${prefix}-category-checkbox`);
        $checkboxes.prop('checked', this.checked).trigger('change');

        if (this.checked) {
            loadCommodities(prefix);
        }
    });

    // Handle commodity Select All
    $('.pf-commodity-check-all, .ca-commodity-check-all, .q-commodity-check-all, .ta-commodity-check-all').on('change', function () {
        const prefix = $(this).hasClass('pf-commodity-check-all') ? 'pf' :
                      $(this).hasClass('ca-commodity-check-all') ? 'ca' :
                      $(this).hasClass('q-commodity-check-all') ? 'q' : 'ta';
        $(this).closest('.dropdown-list').find(`.${prefix}-commodity-checkbox`).prop('checked', this.checked).trigger('change');
    });

    // Handle quarter Select All (unchanged)
    $('.q-quarter-check-all').on('change', function () {
    $(this).closest('.dropdown-list').find('.q-quarter-checkbox:visible').prop('checked', this.checked).trigger('change');
});

    // Handle individual category changes
    $('.pf-category-checkbox, .ca-category-checkbox, .q-category-checkbox, .ta-category-checkbox').on('change', function () {
        const prefix = $(this).hasClass('pf-category-checkbox') ? 'pf' :
                      $(this).hasClass('ca-category-checkbox') ? 'ca' :
                      $(this).hasClass('q-category-checkbox') ? 'q' : 'ta';

        if (!this.checked) {
            $(this).closest('.dropdown-list').find(`.${prefix}-category-check-all`).prop('checked', false);
        }

        const allChecked = $(`.${prefix}-category-checkbox`).length === $(`.${prefix}-category-checkbox:checked`).length;
        $(this).closest('.dropdown-list').find(`.${prefix}-category-check-all`).prop('checked', allChecked);

        loadCommodities(prefix);
    });
}

// Bind individual category change events (again include ta)
$('.pf-category-checkbox, .ca-category-checkbox, .q-category-checkbox, .ta-category-checkbox').on('change', function () {

    
    const prefix = $(this).hasClass('pf-category-checkbox') ? 'pf' :
                  $(this).hasClass('ca-category-checkbox') ? 'ca' :
                  $(this).hasClass('q-category-checkbox') ? 'q' : 'ta';
            
    loadCommodities(prefix);
});

// Load commodities on page load
if ($('.pf-category-checkbox:checked').length > 0) loadCommodities('pf');
if ($('.ca-category-checkbox:checked').length > 0) loadCommodities('ca');
if ($('.q-category-checkbox:checked').length > 0) loadCommodities('q');
if ($('.ta-category-checkbox:checked').length > 0) loadCommodities('ta');

// Init
setupCheckAllHandlers();
});
</script>

@endpush