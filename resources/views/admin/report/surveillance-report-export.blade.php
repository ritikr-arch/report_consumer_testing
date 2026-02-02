             @php 
    $typeName = '';
    if ($typeId == 1) {
        $typeName = "Food Basket";
    } elseif ($typeId == 2) {
        $typeName = "Hardware and Building Materials";
    } elseif ($typeId == 3) {
        $typeName = "Furniture and Appliances";
    } elseif ($typeId == 4) {
        $typeName = "Medication";
    }
@endphp

<table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 14px;">
    <thead>
        <tr>
            <th colspan="9" style="background-color: #0070C0; color: white; font-size: 20px; font-weight: bold; padding: 8px; text-align: center;">
                Surveillance Price Report  {{ ($typeName) ? 'for '.$typeName : ''  }}
            </th>
        </tr>

        <tr>
            <th colspan="9" style="background-color: #F2F2F2; font-size: 14px; padding: 6px; text-align: center;">
              Surveillance Price Report Duration: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'N/A' }} 
                to 
                {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'N/A' }}
            </th>
        </tr>
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
        @foreach($grouped as $categoryName => $commodityGroups)
            @php $firstCategoryRow = true; @endphp
            @foreach($commodityGroups as $commodityData)
                <tr>
                    @if($firstCategoryRow)
                        <td rowspan="{{ $commodityGroups->count() }}" style="border: 1px solid #000; padding: 6px; font-weight: bold; background-color: #F2F2F2; vertical-align: top;">
                            {{ $categoryName }}
                        </td>
                        @php $firstCategoryRow = false; @endphp
                    @endif
                    <td style="border: 1px solid #000; padding: 6px;">{{ $commodityData['commodityName'] }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">{{ $commodityData['brandName'] }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">{{ $commodityData['unitName'] }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">${{ number_format($commodityData['max'], 2) }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">${{ number_format($commodityData['min'], 2) }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">${{ number_format($commodityData['median'], 2) }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">${{ number_format($commodityData['avg'], 2) }}</td>
                    <td style="border: 1px solid #000; padding: 6px;">{{ number_format($commodityData['availability'], 2) }}%</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
