<?php



namespace App\Exports;



use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;



use App\Models\Zone;

use App\Models\User;

use App\Models\UOM;

use App\Models\Brand;

use App\Models\Survey;

use App\Models\Market;

use App\Models\Category;

use App\Models\Commodity;

use App\Models\SubmittedSurvey;



class SubmittedSurveyDetailsExport implements FromCollection, WithHeadings, WithMapping

{



    protected $filters;



    protected $id;

    

    public function __construct(array $filters = [], $id){

        $this->filters = $filters;

        $this->id = $id;

    }


    /**

    * @return \Illuminate\Support\Collection

    */

    public function collection(){



    	$id = $this->id;

    	$query = SubmittedSurvey::with('user', 'zone', 'survey', 'market', 'category', 'commodity', 'unit', 'brand', 'submitter', 'updater');

    	if (!empty($this->filters)) {



    		    if(!empty($this->filters['survey_number'])){

    		        // $query->where('survey_number', $this->filters['survey_number']);

    		    }

    		    if(!empty($this->filters['name'])){

    		        $suverys = Survey::where('name', 'like', '%' . $this->filters['name'] . '%')->pluck('id');

    		        $query->whereIn('survey_id', $suverys);

    		    }





	    	if(!empty($this->filters['commodity'])){

	    	    $query->where('commodity_id', $this->filters['commodity']);

	    	}

	    	if(!empty($this->filters['amount'])){

	    	    $query->where('amount', $this->filters['amount']);

	    	}

	    	if(!empty($this->filters['assignee'])){

	    	    $query->where('user_id', $this->filters['assignee']);

	    	}

	    	if(!empty($this->filters['market'])){

	    	    $query->where('market_id', $this->filters['market']);

	    	}

	    	if(!empty($this->filters['category'])){

	    	    $query->where('category_id', $this->filters['category']);

	    	}

	    	if(!empty($this->filters['unit'])){

	    	    $query->where('unit_id', $this->filters['unit']);

	    	}

	    	if(!empty($this->filters['brand'])){

	    	    $query->where('brand_id', $this->filters['brand']);

	    	}

	    	if(!empty($this->filters['collected_buy'])){

	    	    $query->where('submitted_by', $this->filters['collected_buy']);

	    	}

	    	if(!empty($this->filters['updated_by'])){

	    	    $query->where('updated_by', $this->filters['updated_by']);

	    	}



    	    if(!empty($this->filters['status']) && ($this->filters['status'] === '0') || (!empty($this->filters['status']) && $this->filters['status'] === '1') ){

    	        $query->where('status', $this->filters['status']);

    	    }

    	    if((!empty($this->filters['publish']) && $this->filters['publish'] === '0') || (!empty($this->filters['publish']) && $this->filters['publish'] === '1') ){

    	        // $query->where('is_publish', $this->filters['publish']);

    	    }

    	    if(!empty($this->filters['start_date'])){

    	        $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($this->filters['start_date'])));

    	    }

    	    if(!empty($this->filters['end_date'])){

    	        $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($this->filters['end_date'])));

    	    }

    	    if(!empty($this->filters['collected_date'])){

	    	    $query->whereDate('collected_date', date('Y-m-d', strtotime($this->filters['collected_date'])));

	    	}

	    }



    	return $query->where('survey_id', $id)->orderby('id', 'desc')->get();

    }



    /**

     * Define the headings for the Excel sheet.

     */

    public function headings(): array{

        return [

            'Commodity',

            'Zone',

            'Store',

            'Category',

            'Amount',

            'Collected Date',

            'Collected By',
        ];

    }



    /**

     * Map the data to match the column order.

     */

    public function map($value): array
    {

        return [

            ($value->commodity)?ucfirst($value->commodity->name):'',

            ($value->zone)?ucfirst($value->zone->name):'',

            ($value->market)?ucfirst($value->market->name):'',

            ($value->category)?ucfirst($value->category->name):'',

            $value->amount,

             customt_date_format( $value->created_at),
           

            ($value->submitter)?$value->submitter->name:'',
        ];

    }



}

