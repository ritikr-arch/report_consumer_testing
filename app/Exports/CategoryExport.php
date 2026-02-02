<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection, WithHeadings, WithMapping
{

    protected $filters;
    
    public function __construct(array $filters = []){
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $query = Category::select('name', 'type_id', 'status', 'image', 'created_at');
        if (!empty($this->filters)) {
            if (!empty($this->filters['name'])) {
                $query->where('name', 'like', '%' . $this->filters['name'] . '%');
            }
            if (isset($this->filters['status']) && $this->filters['status'] !== '') {
                $query->where('status', $this->filters['status']);
            }
            if (!empty($this->filters['start_date'])) {
                $query->whereDate('created_at', '>=', $this->filters['start_date']);
            }
            if (!empty($this->filters['end_date'])) {
                $query->whereDate('created_at', '<=', $this->filters['end_date']);
            }
        }
        return $query->orderby('id', 'desc')->get();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array{
        return [
            'Category Name', 
            'Type_id',
            'Status', 
            'Image'
        ];
    }

    /**
     * Map the data to match the column order.
     */

    public function map($category): array
    {
        return [
            $category->name,
            $category->type_id,
            ($category->status==1)?'Active':'Deactive',
            url('admin/images/category').'/'.$category->image,
        ];
    }

}
