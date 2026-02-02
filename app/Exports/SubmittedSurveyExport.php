<?php



namespace App\Exports;



use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithMapping;



use App\Models\Zone;

use App\Models\Survey;

use App\Models\SubmittedSurvey;



class SubmittedSurveyExport implements FromCollection, WithHeadings, WithMapping

{



    protected $filters;

    

    public function __construct(array $filters = []){

        $this->filters = $filters;

    }





    /**

    * @return \Illuminate\Support\Collection

    */

    public function collection(){

        $latestIds = SubmittedSurvey::groupBy('survey_id')->selectRaw('MAX(id) as id')->pluck('id');

        $zone = Zone::where('status', '1')->orderby('id', 'desc')->get();

        $query = SubmittedSurvey::with('zone', 'survey', 'submitter');



        if (!empty($this->filters)) {



	        if(!empty($this->filters['survey_number'])){

                $survey_id = preg_replace('/[^a-zA-Z0-9]/', '', $this->filters['survey_number']);

                

                $suveryId = Survey::where('survey_id', $survey_id)->pluck('id');

                $query->where('survey_id', $suveryId);

	        }

	        if(!empty($this->filters['name'])){

	            $suverys = Survey::where('name', 'like', '%' . $this->filters['name'] . '%')->pluck('id');

	            $query->whereIn('survey_id', $suverys);

	        }

	        if(!empty($this->filters['zone'])){

	            $query->where('zone_id', $this->filters['name']);

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

	    }



        return $query->whereIn('id', $latestIds)->orderBy('id', 'desc')->get();



        // return Commodity::with('category', 'brand', 'uom')->orderby('id', 'desc')->get();

    }



    /**

     * Define the headings for the Excel sheet.

     */

    public function headings(): array{

        return [

            'Survey IDs', 

            'Survey Title', 

            'Zone',

            'Collected On',

            'Collected By',

            'Status',

            'Approved',

            'Published',

        ];

    }



    /**

     * Map the data to match the column order.

     */

    public function map($surveys): array

    {


        $today = date('Y-m-d');
        return [

            ($surveys->survey)?'#'.ucfirst($surveys->survey->survey_id):'',

            ($surveys->survey)?ucfirst($surveys->survey->name):'',

            ($surveys->zone)?ucfirst($surveys->zone->name):'',

             customt_date_format( $surveys->created_at),
           

            ($surveys->submitter)?ucfirst($surveys->submitter->name):'',

            $surveys->survey ? ($surveys->survey->is_complete == 1 ? 'Approved' : ($surveys->survey->end_date < $today ? 'Overdue' : 'In Progress')) : 'In Progress',

           (isset($surveys->survey) && $surveys->survey->is_approve == 1) ? 'Yes' : 'No',

            ($surveys->publish==1)?'Yes':'No',

        ];

    }



}

