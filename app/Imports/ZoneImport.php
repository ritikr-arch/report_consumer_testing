<?php



namespace App\Imports;

use App\Models\Zone;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;



class ZoneImport implements ToModel, WithHeadingRow

{

    public function model(array $row){

        return new Zone([
            'name'        => $row['name'],
            'status' => ($row['status'])?'1':'0', 

        ]);

    }

 

}



