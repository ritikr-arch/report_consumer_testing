<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CategoryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $imagePath = null;

        // Check if the image column exists and is not empty
        // if (!empty($row['image'])) {
        //     // Path inside the public folder
        //     $imageDirectory = 'admin/images/category/'; // Folder inside public
        //     $imageName = $row['image']; // Image name from Excel sheet
        //     $fullImagePath = public_path($imageDirectory . $imageName);

        //     // Verify if the image exists in the public folder
        //     if (File::exists($fullImagePath)) {
        //         $imagePath = $imageDirectory . $imageName; // Store relative path
        //     } else {
        //         // Handle missing images (optional: set a default image)
        //         $imagePath = 'admin/images/category/default.jpg'; // Default image
        //     }
        // }

        return new Category([
            'name'        => $row['name'],
            'type_id'       => $row['type_id'], 
            'status'       => ($row['status'])?'1':'0',
           
        ]);
    }
}

