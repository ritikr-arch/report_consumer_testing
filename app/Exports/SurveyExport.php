<?php

namespace App\Exports;

use App\Models\Survey;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyExport implements FromCollection, WithHeadings, WithMapping
{

    protected $filters;
    
    public function __construct(array $filters = []){
        $this->filters = $filters;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(){
        $query = Survey::with('zone', 'markets.surveyMarketss', 'categories.surveyCategoriesss', 'surveyors.surveySurveyorsss');

        // Apply filters if they exist.
        // This way if filters are not provided, the query returns all records.
        $request = $this->filters;
        if (!empty($this->filters)) {
            if (!empty($this->filters['survey_id'])) {
                $survey_id = preg_replace('/[^a-zA-Z0-9]/', '', $this->filters['survey_id']);
                $query->where('survey_id', $survey_id);
            }
            if (!empty($this->filters['name'])) {
                $query->where('name', 'like', '%' . $this->filters['name'] . '%');
            }
            if (!empty($this->filters['zone'])) {
            	$query->where('zone_id', $this->filters['zone']);
            } 
            if (!empty($this->filters['market'])) {
            	$query->whereHas('markets', function ($q) use ($request) {
            	    $q->where('market_id', $this->filters['market']);
            	});
            }
            if (!empty($this->filters['category'])) {
            	$query->whereHas('categories', function ($q) use ($request) {
            	    $q->where('category_id', $this->filters['category']);
            	});
            }
            if (!empty($this->filters['surveyors'])) {
            	$query->whereHas('surveyors', function ($q) use ($request) {
            	    $q->where('surveyor_id', $this->filters['surveyors']);
            	});
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
        return $query->orderBy('id', 'desc')->get();
    }

    /**
     * Define the headings for the Excel sheet.
     */
    public function headings(): array{
        return [
            'Survey ID', 
            'Survey Title', 
            'Type',
            'Zone',
            'Stores',
            'Categories',
            'Compilance Officer',
            
            'Investigation Officer',
            'Chief Investigation Officer',
            'Status'
        ];
    }

    /**
     * Map the data to match the column order.
     */
    public function map($survey): array
    {

    	$marketNames = [];
	    if (!empty($survey->markets) && $survey->markets->count()) {
	        foreach ($survey->markets as $surveyMarket) {
	        	if (!empty($surveyMarket->surveyMarketss->name)) {
	                $marketNames[] = $surveyMarket->surveyMarketss->name;
	            }
	        }
	    }

	    $categoryNames = [];
	    if (!empty($survey->categories) && $survey->categories->count()) {
	        foreach ($survey->categories as $surveyCate) {
	        	if (!empty($surveyCate->surveyCategoriesss->name)) {
	                $categoryNames[] = $surveyCate->surveyCategoriesss->name;
	            }
	        }
	    }

	    $surveyorNames = [];
	    if (!empty($survey->surveyors) && $survey->surveyors->count()) {
	        foreach ($survey->surveyors as $surveySurvey) {
	        	if (!empty($surveySurvey->surveySurveyorsss->name)) {
	                $surveyorNames[] = $surveySurvey->surveySurveyorsss->name;
	            }
	        }
	    }

        return [
            '#'.$survey->survey_id,
            $survey->name,
             $survey->type->isNotEmpty() ? $survey->type->pluck('name')->implode(', ') : '',
            ($survey->zone)?ucfirst($survey->zone->name):'',
            !empty($marketNames) ? implode(', ', $marketNames) : '', 
            !empty($categoryNames) ? implode(', ', $categoryNames) : '', 
            !empty($surveyorNames) ? implode(', ', $surveyorNames) : '',
            $survey->investigationOfficer->isNotEmpty() ? $survey->investigationOfficer->pluck('name')->implode(', ') : '',
            $survey->chiefofficer->isNotEmpty() ? $survey->chiefofficer->pluck('name')->implode(', ') : '',
            ($survey->status==1)?'Active':'Deactive',
        ];
    }

}
