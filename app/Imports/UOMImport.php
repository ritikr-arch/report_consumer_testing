<?php

namespace App\Imports;

use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\UOM;

class UOMImport implements ToModel, WithHeadingRow
{    
	public function model(array $row)
	{        
		return new UOM(['name' => $row['name'],'status' => ($row['status'])?'1':'0']);    
	}
}