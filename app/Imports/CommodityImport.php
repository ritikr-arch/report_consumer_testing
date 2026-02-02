<?php





namespace App\Imports;





use App\Models\Commodity;


use Illuminate\Support\Facades\File;


use Maatwebsite\Excel\Concerns\ToModel;


use Maatwebsite\Excel\Concerns\WithHeadingRow;





class CommodityImport implements ToModel, WithHeadingRow


{


    public function model(array $row)


    {

        // $imagePath = null;

        return new Commodity([
            'name'        => $row['name'],
            // 'image'       => $row['image'],

           
            'uom_id'      => $row['uom'],
            'brand_id'    => $row['brand'],
            // 'unit_value'  => $row['unit'],
            'category_id' => $row['category'],
            
            'status'      => ($row['status'])?'1':'0',

        ]);


    }


}





