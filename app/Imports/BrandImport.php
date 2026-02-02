<?php





namespace App\Imports;





use App\Models\Brand;


use Illuminate\Support\Facades\File;


use Maatwebsite\Excel\Concerns\ToModel;


use Maatwebsite\Excel\Concerns\WithHeadingRow;





class BrandImport implements ToModel, WithHeadingRow


{


    public function model(array $row){


        return new Brand([


            'name'        => $row['name'],
            'status'       => ($row['status'])?'1':'0',


        ]);


    }


}





