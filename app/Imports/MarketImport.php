<?php



namespace App\Imports;



use Illuminate\Support\Facades\File;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;



use App\Models\Zone;

use App\Models\Market;



class MarketImport implements ToModel, WithHeadingRow

{

    public function model(array $row)

    {
        $zone  = Zone::find($row['zone_id']);

        return new Market([

            'name'         => $row['name'],
            'status'       => ($row['status'])?'1':'0',
            // 'image'        => $row['image'],
            'zone_id'      => $zone->id,
            'zone_name'    => $zone->name,
            'zone_details' => json_encode(@$zone),

        ]);

    }

}



