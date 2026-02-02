<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\helpers;
use App\Models\PublishLog;
use App\Models\Survey;
use App\Models\SubmittedSurvey;
use App\Models\User;

class AuditLogController extends Controller
{
    

    public function __construct (){
        $this->middleware('permission:audit_list', ['only' => ['index']]);
        $this->middleware('permission:audit_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:audit_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:audit_delete', ['only' => ['delete']]);
    }

    public function index(){
        $title = 'Audit Log';
        $data = Survey::whereHas('surveyLog')
    ->with([
        'zone',
        'surveyLog.user:id,name,title'   // Add 'title' here
    ])
    ->orderBy('id', 'desc')
    ->paginate(10);

        return view('admin.audit.index', compact('title', 'data'));
    }

    public function details($id){
        $title = 'Audit Log Details';
        $survey = Survey::where('id', $id)->first();
        $data = PublishLog::with('user:id,name,title')->where('survey_id', $id)->orderby('id', 'desc')->paginate(10);
        return view('admin.audit.details', compact('title', 'data', 'survey'));
    }


}
