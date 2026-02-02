<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping
{

    protected $filters;
    
    public function __construct(array $filters = []){
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $query = User::select('name', 'email', 'status', 'created_at');

        if (!empty($this->filters)) {
            if (!empty($this->filters['name'])) {
                $query->where('name', 'like', '%' . $this->filters['name'] . '%');
            }
            if($this->filters['email']){
                $query->where('email', 'like', '%' .$this->filters['email']. '%');
            }
            if (isset($this->filters['status']) && $this->filters['status'] !== '') {
                $query->where('status', $this->filters['status']);
            }
            
        }
        return $query->orderby('id', 'desc')->get();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array{
        return [
            'Name', 
            'Email Id',
          
            'status',
            'created_at'
        ];
    }

    /**
     * Map the data to match the column order.
     */

    public function map($user): array
    {
        return [
            $user->name,
            $user->email,
           
            ($user->status==1)?'Active':'Deactive',
            $user->created_at
           
        ];
    }

}
