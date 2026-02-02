<?php

namespace App\Exports;

use App\Models\Commodity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CommodityExport implements FromCollection, WithHeadings, WithMapping
{

    protected $filters;
    
    public function __construct(array $filters = []){
        $this->filters = $filters;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $query = Commodity::with('category', 'brand', 'uom')->orderBy('id', 'desc');

        // Apply filters if they exist.
        // This way if filters are not provided, the query returns all records.
        if (!empty($this->filters)) {
            if (!empty($this->filters['name'])) {
                $query->where('name', 'like', '%' . $this->filters['name'] . '%');
            }
            if (!empty($this->filters['unit'])) {
                $query->where('unit_value', $this->filters['unit']);
            }
            if (!empty($this->filters['category'])) {
                $query->where('category_id', $this->filters['category']);
            }
            if (!empty($this->filters['brand'])) {
                $query->where('brand_id', $this->filters['brand']);
            }
            if (!empty($this->filters['uom'])) {
                $query->where('uom_id', $this->filters['uom']);
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

        return $query->get();
        // return Commodity::with('category', 'brand', 'uom')->orderby('id', 'desc')->get();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array{
        return [
            'Commodity Name', 
            'Category',
            'Brand',
            'UOM',
            // 'Unit Value',
            'Status', 
            'Image'
        ];
    }

    /**
     * Map the data to match the column order.
     */
    public function map($commodity): array
    {
        return [
            $commodity->name,
            ($commodity->category)?ucfirst($commodity->category->name):'',
            ($commodity->brand)?ucfirst($commodity->brand->name):'',
            ($commodity->uom)?ucfirst($commodity->uom->name):'',
            // $commodity->unit_value,
            ($commodity->status==1)?'Active':'Deactive',
            url('admin/images/commodity').'/'.$commodity->image,
        ];
    }

}
