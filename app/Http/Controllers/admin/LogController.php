<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

use App\Helpers\helpers;
use App\Models\User;

class LogController extends Controller
{
    

    public function __construct (){
        $this->middleware('permission:audit_list', ['only' => ['index']]);
        $this->middleware('permission:audit_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:audit_edit', ['only' => ['edit', 'update', 'changeStatus']]);
        $this->middleware('permission:audit_delete', ['only' => ['delete']]);
    }

    public function index(){
        $title = 'Log';
        $data = Activity::with('subject', 'causer')->orderby('id', 'desc')->paginate(10);
        return view('admin.log.index', compact('title', 'data'));
    }


}
