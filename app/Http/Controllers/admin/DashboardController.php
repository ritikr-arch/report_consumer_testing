<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;

use App\Models\Zone;
use App\Models\User;
use App\Models\UOM;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Survey;
use App\Models\Market;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\Feedback;
use App\Models\SubmittedSurvey;
use App\Models\Notification;
use App\Models\CustomerComplaint;


class DashboardController extends Controller
{


    public function index(){
        $currentDate = date('Y-m-d');
        $title = 'Admin Dashboard';
        $surveys = Survey::count();
        // $completedSurvey = Survey::where('is_complete',1)->whereHas('submittedSurveys', function ($query) {
        //         $query->where('publish', 1);
        //     })->count();

        $latestIds = SubmittedSurvey::groupBy('survey_id')
            ->selectRaw('MAX(id) as id')
            ->pluck('id');

        $completedSurvey = SubmittedSurvey::where('publish', 1)
            ->whereIn('id', $latestIds)
            ->count();

        $pendingSurvey = Survey::where('is_complete', 0)
    ->whereDate('end_date', '>=', $currentDate)
    ->whereIn('id', function ($query) use ($latestIds) {
        $query->select('survey_id')
              ->from('submitted_surveys')
              ->whereIn('id', $latestIds);
    })
    ->count();

        // $pendingSurvey = Survey::where(['is_complete'=>0])
        //                         ->whereDate('end_date', '<=', $currentDate)->count();
        // $overdueSurvey = Survey::whereIn('id', $latestIds)->whereDate('end_date', '<', $currentDate)->count();

       $overdueSurvey = Survey::where('is_complete', 0)
    ->whereDate('end_date', '<', $currentDate)
    ->whereIn('id', function ($query) use ($latestIds) {
        $query->select('survey_id')
              ->from('submitted_surveys')
              ->whereIn('id', $latestIds);
    })
    ->count();

        $recentSubmittedSurvey = SubmittedSurvey::with('market:id,name', 'commodity:id,name,image', 'survey:id')
                                                    ->where(['is_submit'=>1])->orderby('id', 'desc')
                                                    ->limit(5)->get();

        $publishSubmittedSurvey = Survey::with('submittedSurveys:id,publish', 'zone:id,name')
                        ->whereHas('submittedSurveys', function ($query) {
                            $query->where('publish', 1);
                        })
                        ->orderBy('id', 'desc')
                        ->limit(5)
                        ->get();

        $recentNotification = Notification::orderby('id', 'desc')->limit(5)->get();

        $categories = Category::orderby('name', 'asc')->get();

        $categorySurvey = Category::whereHas('submittedSurveys', function($query){
            $query->where('is_submit', 1);
        })->with('submittedSurveys.unit', 'submittedSurveys.commodity')->orderBy('name', 'asc')->limit(4)->get();

        $totalComplaints = CustomerComplaint::count();
        $newComplaints = CustomerComplaint::where('status', '0')->count();
        $resolvedComplaints = CustomerComplaint::where('status', '1')->count();
        $inprogressComplaints = CustomerComplaint::where('status', '2')->count();
        $dismissedComplaints = CustomerComplaint::where('status', '3')->count();
        $closedComplaints = CustomerComplaint::where('status', '4')->count();
        

        $feedback = Feedback::count();
        $enquiries = Enquiry::count();

        $complianceOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Compliance Officer');
        })->count();

        $investigationOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Investigation Officer');
        })->count();

        $chiefinvestigationOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Chief Investigation Officer');
        })->count();

        $markets = Market::count();
        $commodities = Commodity::count();

        return view('admin.dashboard', compact('title', 'surveys', 'completedSurvey', 'pendingSurvey', 'overdueSurvey', 'publishSubmittedSurvey', 'recentSubmittedSurvey', 'recentNotification', 'categories', 'categorySurvey', 'totalComplaints', 'newComplaints', 'inprogressComplaints', 'resolvedComplaints', 'dismissedComplaints','closedComplaints', 'feedback', 'enquiries', 'complianceOfficers', 'investigationOfficers', 'chiefinvestigationOfficers', 'commodities', 'markets'));
    }

    public function newDashboard(){
        $currentDate = date('Y-m-d');
        $title = 'Admin Dashboard';
        $surveys = Survey::count();
        $completedSurvey = Survey::where('is_complete',1)
        

            ->whereHas('submittedSurveys', function ($query) {
                $query->where('publish', 1);
            })->count();


        $pendingSurvey = Survey::where(['is_complete'=>0])
                                ->whereDate('end_date', '<=', $currentDate)->count();
        $overdueSurvey = Survey::where(['is_complete'=>0])
                                ->whereDate('end_date', '<=', $currentDate)->count();

        $recentSubmittedSurvey = SubmittedSurvey::with('market:id,name', 'commodity:id,name,image', 'survey:id')
                                                    ->where(['is_submit'=>1])->orderby('id', 'desc')
                                                    ->limit(5)->get();

        $publishSubmittedSurvey = Survey::with('submittedSurveys:id,publish', 'zone:id,name')
                        ->whereHas('submittedSurveys', function ($query) {
                            $query->where('publish', 1);
                        })
                        ->orderBy('id', 'desc')
                        ->limit(5)
                        ->get();

        $recentNotification = Notification::orderby('id', 'desc')->limit(5)->get();

        $categories = Category::orderby('name', 'asc')->get();

        $categorySurvey = Category::whereHas('submittedSurveys', function($query){
            $query->where('is_submit', 1);
        })->with('submittedSurveys.unit', 'submittedSurveys.commodity')->orderBy('name', 'asc')->limit(4)->get();

        $totalComplaints = CustomerComplaint::count();
        $newComplaints = CustomerComplaint::where('status', '0')->count();
        $resolvedComplaints = CustomerComplaint::where('status', '1')->count();
        $inprogressComplaints = CustomerComplaint::where('status', '2')->count();
        $dismissedComplaints = CustomerComplaint::where('status', '3')->count();

        $feedback = Feedback::count();
        $enquiries = Enquiry::count();

        $complianceOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Compliance Officer');
        })->count();

        $investigationOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Investigation Officer');
        })->count();

        $chiefinvestigationOfficers = User::whereHas('roles', function($query){
            $query->where('name', 'Chief Investigation Officer');
        })->count();

        $markets = Market::count();
        $commodities = Commodity::count();

        return view('admin.dashboard-amit', compact('title', 'surveys', 'completedSurvey', 'pendingSurvey', 'overdueSurvey', 'publishSubmittedSurvey', 'recentSubmittedSurvey', 'recentNotification', 'categories', 'categorySurvey', 'totalComplaints', 'newComplaints', 'inprogressComplaints', 'resolvedComplaints', 'dismissedComplaints', 'feedback', 'enquiries', 'complianceOfficers', 'investigationOfficers', 'chiefinvestigationOfficers', 'commodities', 'markets'));
    }


}

