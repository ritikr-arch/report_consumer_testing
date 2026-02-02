<?php 
use App\Models\SubmittedSurvey;
use App\Models\Market;
use App\Models\Survey;
?>
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
        	<tr><td colspan="100%">&nbsp;</td></tr>
        	<tr><td colspan="100%">&nbsp;</td></tr>
        	<tr><td colspan="100%">&nbsp;</td></tr>
        	<tr><td colspan="100%">&nbsp;</td></tr>

            <tr>
                <th colspan="{{ 2 + ($maxMarketCount*2) }}" style="text-align: center;">
                    <strong>{{priceCollectionHeading()}}</strong>
                </th>
            </tr>
            
            <tr>
                <th colspan="{{ 2 + ($maxMarketCount*2) }}" style="text-align: center;">
                    <strong>A Look At The {{($type)?ucwords($type):'Super Market'}} Prices</strong>
                </th>
            </tr>

            <tr><td>&nbsp;</td></tr>

            @foreach ($data as $surveyName => $data)
                <?php
                $surverID = $data['survey_id'];
                $surveyData = Survey::with('surveyType', 'zone')->find($data['survey_id']);
                $marketIds = SubmittedSurvey::where('survey_id', $surverID)->pluck('market_id')->toArray();
                $marketIds = array_unique($marketIds);
                $markets = Market::where('status', '1')->whereIn('id', $marketIds)->orderBy('name', 'asc')->get();
                
                ?>

                <thead>
                    <!-- Table Header -->
                    <tr class="">
                    	<th colspan="{{ 2 + ($maxMarketCount*2) }}" style="text-align: center; background: #ff9900;">
                            <strong>Price Collected on: {{ customt_date_format($surveyData->start_date) }}</strong>
                        </th>
                        
                    </tr>

                    <tr bgcolor="#f1f1f1" class="zone-tbl-head">
                        <th class="sticky-1" align="center" rowspan="2">
                            <div class="width1"><strong>Commodities</strong></div>
                        </th>

                        <th class="sticky-2" align="center" rowspan="2">
                            <div class="width1"><strong>Content</strong></div>
                        </th>
                        @if (isset($markets) && count($markets) > 0)
                            @foreach ($markets as $markettt)
                                <th align="center" colspan="2" class="text-center" style="text-align: center;">
                                    <strong>{{ ucfirst($markettt->name) }} (in $)</strong>
                                </th>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                    @if (isset($markets) && count($markets) > 0)
                        @foreach ($markets as $market)
                            <th class="text-center">
                                <strong>Generic</strong>
                            </th>
                            <th class="text-center">
                                <strong>Original</strong>
                            </th>
                        @endforeach
                    @endif
                    </tr>
                </thead>

                @foreach ($data['categories'] as $categoryName => $categoryData)
                    <tr>
                        <td colspan="{{ 2 + (count($markets)*2) }}" class="bg-light fw-bold" style="text-align: center;">
                            <strong>{{ strtoupper($categoryName) }}</strong>
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
                                    {{ $marketPrice && $marketPrice->amount ? $marketPrice->amount : '-' }}
                                   
                                </td>

                                <td  class="text-center">
                                    {{ @$marketPrice->amount_1 ?? '-' }} 
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