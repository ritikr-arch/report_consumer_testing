<?php 
namespace App\Exports;

use App\Models\SubmittedSurvey;
use App\Models\Market;
use App\Models\Survey;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StoreReportExport implements FromView, WithDrawings, WithColumnFormatting
{
    protected $data, $maxMarketCount, $markets, $type;

    public function __construct($data, $maxMarketCount, $type)
    {
        $this->data = $data;
        $this->maxMarketCount = $maxMarketCount;
        $this->type = $type;

        $surveyIds = collect($data)->pluck('survey_id')->unique();
        $marketIds = SubmittedSurvey::whereIn('survey_id', $surveyIds)->pluck('market_id')->unique();
        $this->markets = Market::whereIn('id', $marketIds)->where('status', 1)->get();
    }

    public function view(): View
    {
        return view($this->type == 'medication' ? 'exports.medication_survey_export_excel' : 'exports.survey_export_excel', [
            'data' => $this->data,
            'maxMarketCount' => $this->maxMarketCount,
            'markets' => $this->markets,
            'type' => $this->type,
        ]);
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Company Logo');
        $drawing->setPath(public_path('admin/images/excel-logo.png'));
        $drawing->setHeight(70);
        $drawing->setOffsetX(50);

        $length = ($this->type == 'medication') ? ((count($this->markets) * 2) + 2) : (count($this->markets) + 3);
        $columnLetter = Coordinate::stringFromColumnIndex(ceil($length / 2));
        $drawing->setCoordinates($columnLetter . '1');

        return [$drawing];
    }

    public function columnFormats(): array
    {
        $formats = [];
        for ($i = 4; $i <= 3 + $this->maxMarketCount; $i++) {
            $columnLetter = Coordinate::stringFromColumnIndex($i);
            $formats[$columnLetter] = NumberFormat::FORMAT_NUMBER_00;
        }
        return $formats;
    }
}
