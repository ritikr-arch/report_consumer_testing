<table>
    <tbody>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>&nbsp;</td></tr>

        <tr>
            <th colspan="{{ 3 + $maxMarketCount }}" style="text-align: center;">
                <strong>{{ priceCollectionHeading() }}</strong>
            </th>
        </tr>

        <tr>
            <th colspan="{{ 3 + $maxMarketCount }}" style="text-align: center;">
                <strong>A Look At The {{ ($type) ? ucwords($type) : 'Super Market' }} Prices</strong>
            </th>
        </tr>

        <tr><td>&nbsp;</td></tr>

        @foreach ($data as $surveyId => $surveyGroup)
            @php
                $surveyData = \App\Models\Survey::with('zone')->find($surveyGroup['survey_id']);
                $markets = \App\Models\SubmittedSurvey::where('survey_id', $surveyId)
                    ->pluck('market_id')->unique();
                $markets = \App\Models\Market::whereIn('id', $markets)->where('status', 1)->orderBy('name')->get();
            @endphp

            <tr>
                <th colspan="{{ 3 + $maxMarketCount }}" style="text-align: center; background: #ff9900;">
                    <strong>
                        Price Collected on: {{ customt_date_format($surveyData->start_date) }}
                        @if($surveyData->zone)
                            ({{ ucfirst($surveyData->zone->name) }})
                        @endif
                    </strong>
                </th>
            </tr>

            <tr>
                <th><strong>Commodities</strong></th>
                <th><strong>Brand</strong></th>
                <th><strong>Unit</strong></th>
                @foreach ($markets as $market)
                    <th style="text-align: center;"><strong>{{ ucfirst($market->name) }} (in $)</strong></th>
                @endforeach
            </tr>

            @foreach ($surveyGroup['categories'] as $categoryName => $categoryData)
                <tr>
                    <td colspan="{{ 3 + $maxMarketCount }}" style="text-align: center;">
                        <strong>{{ strtoupper($categoryName) }}</strong>
                    </td>
                </tr>

                @foreach ($categoryData->groupBy('commodity_id') as $commodityId => $commodityGroup)
                    @php
                        $firstRow = $commodityGroup->first();
                        $commodityName = $firstRow->commodity->name ?? 'N/A';
                        $uniquePrices = $commodityGroup->pluck('amount')->unique()->filter();
                        $min = $uniquePrices->min();
                        $max = $uniquePrices->max();
                        $isSame = $uniquePrices->count() <= 1;
                    @endphp
                    <tr>
                        <td>{{ $commodityName }}</td>
                        <td>{{ !empty($firstRow->brand->name) && strtolower($firstRow->brand->name) !== 'no name' ? $firstRow->brand->name : 'N/A' }}</td>
                        <td>{{ $firstRow->unit->name ?? 'N/A' }}</td>

                        @foreach ($markets as $market)
                            @php
                                $marketPrice = $commodityGroup->where('market_id', $market->id)->first();
                                $price = $marketPrice->amount ?? null;
                                $color = '';
                                if ($price && !$isSame) {
                                    if ($price == $min) $color = 'color: green; font-weight: bold;';
                                    elseif ($price == $max) $color = 'color: red; font-weight: bold;';
                                }
                            @endphp
                            <td style="{{ $color }}">
                                {{ $price ? number_format($price, 2, '.', '') : '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
