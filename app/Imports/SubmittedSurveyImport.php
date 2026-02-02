<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\Shared\Date; // Add this at the top
use Carbon\Carbon;

use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithHeadingRow;

use App\Models\SubmittedSurvey;
use App\Models\Survey;

class SubmittedSurveyImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(Array $row){
        // dd($row);
        $surveyData = Survey::find($row['survey_id']);

        $data = array(
            'user_id' => @$row['submitted_by'],
            'zone_id' => $row['zone_id'],
            'survey_id' => $row['survey_id'],
            'market_id' => $row['market_id'],
            'category_id' => $row['category_id'],
            'commodity_id' => $row['commodity_id'],
            'unit_id' => $row['unit_id'],
            'brand_id' => $row['brand_id'],
            'amount' => $row['amount'],
            'amount_1' => @$row['amount_1'],
            'submitted_by' => @$row['submitted_by'],
            'status' => '1',
            'publish' => 1,
            'commodity_expiry_date' => $surveyData->start_date,
            'created_at' => $surveyData->start_date,
            'updated_at' => $surveyData->start_date,
            // 'commodity_expiry_date' => Carbon::instance(Date::excelToDateTimeObject($row['commodity_expiry_date']))->format('Y-m-d'),
            // 'created_at' => Carbon::instance(Date::excelToDateTimeObject($row['commodity_expiry_date']))->format('Y-m-d'),
            // 'updated_at' => Carbon::instance(Date::excelToDateTimeObject($row['commodity_expiry_date']))->format('Y-m-d'),
            'is_save' => 1,
            'is_submit' => 1,
        );

        // $result = SubmittedSurvey::create($data);
        $result = SubmittedSurvey::insert($data);
        if($result){
            Survey::where('id', $row['survey_id'])->update(['is_complete'=>1, 'is_approve'=>'1']);
        }
    }
}
