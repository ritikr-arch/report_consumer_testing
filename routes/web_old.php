<?php

use App\Http\Controllers\admin\AnnouncementController;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

use App\Http\Controllers\admin\CMSController;
use App\Http\Controllers\admin\UOMController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\ZoneController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\BroachersPresentationController;
use App\Http\Controllers\admin\MarketController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\SurveyController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CommodityController;

use App\Http\Controllers\admin\ConsumerEducationKidsController;
use App\Http\Controllers\admin\DashboardController;

use App\Http\Controllers\admin\SubmittedSurveyController;
use App\Http\Controllers\admin\ConsumerProtectionBillController;
use App\Http\Controllers\admin\DisclaimerController;
use App\Http\Controllers\admin\PublicHealthActsController;
use App\Http\Controllers\admin\TipsAndAdviceController;
use App\Models\Announcement;
use App\Models\ConsumerEducationKids;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('migration', function(){
    Artisan::call('migrate');
    return "Table created successfully";
});

Route::get('clear-cache', function(){
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return "Cache cleared successfully";
});

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/home', function () {
    return redirect()->route('admin.dashboard');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware'=>'auth', 'prefix'=>'admin', 'as'=>'admin.'], function(){

    // ROLE ROUTE
    
    Route::controller(RoleController::class)->group(function () {
        Route::get('role/list', 'index')->name('role.index');
        Route::get('role/create', 'create')->name('role.create');
        Route::post('role/create', 'store')->name('role.store');
        Route::get('role/show/{id}', 'show')->name('role.show');
        Route::get('role/edit/{id}', 'edit')->name('role.edit');
        Route::post('role/update/{id}', 'update')->name('role.update');
        Route::get('role/delete/{id}', 'delete')->name('role.delete');
        Route::get('modules', 'module_list')->name('modules');
        Route::get('module/create', 'module_create')->name('module.create');
        Route::Post('module/store', 'module_store')->name('module.store');
        Route::get('module/edit/{id}', 'module_edit')->name('module.edit');
        Route::post('module/update/{id}', 'module_update')->name('module.update');
        Route::post('module/delete/{id}', 'module_delete')->name('module.delete');
    });

    // STAFF ROUTE
    Route::controller(StaffController::class)->group(function () {
        Route::get('staff/list','index')->name('staff.index');
        Route::get('staff/create','create')->name('staff.create');
        Route::post('staff/store','store')->name('staff.store');
        Route::get('staff/edit/{id}','edit')->name('staff.edit');
        Route::post('staff/update/{id}','update')->name('staff.update');
        Route::post('staff/delete/{id}', 'delete')->name('staff.delete');
        Route::post('staff/changestatus', 'changeStatus')->name('staff.status.change');
    });

    // DASHBOARD ROUTE

    Route::controller(DashboardController::class)->group(function(){
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // USER ROUTE
    Route::controller(UserController::class)->group(function(){
        Route::get('user/list', 'index')->name('user.list');
        Route::post('user/save', 'save')->name('user.save');
        Route::get('user/edit/{id}', 'edit')->name('user.edit');
        Route::post('user/update/status', 'updateStatus')->name('user.update.status');
        Route::get('user/delete/{id}', 'delete')->name('user.delete');
        Route::get('user/filter', 'filter')->name('user.filter');
        Route::get('user/view/{id}', 'user_view')->name('user.view');
    });

    // MARKET ROUTE
    Route::controller(MarketController::class)->group(function(){
        Route::get('market/list', 'index')->name('market.list');
        Route::post('market/save', 'save')->name('market.save');
        Route::get('market/edit/{id}', 'edit')->name('market.edit');
        Route::get('market/view/{id}', 'view')->name('market.view');
        Route::get('market/delete/{id}', 'delete')->name('market.delete');
        Route::post('market/update/status', 'updateStatus')->name('market.update.status');
        Route::get('market/filter', 'filter')->name('market.filter');
        Route::get('market/import', 'import')->name('import.market');
        Route::post('market/importt', 'importMarket')->name('market.import.post');
        Route::get('market/export', 'exportMarket')->name('market.export');
    });

    // CATEGORY ROUTE
    Route::controller(CategoryController::class)->group(function(){
        Route::get('category/list', 'index')->name('category.list');
        Route::post('category/save', 'save')->name('category.save');
        Route::get('category/edit/{id}', 'edit')->name('category.edit');
        Route::get('category/view/{id}', 'view')->name('category.view');
        Route::get('category/delete/{id}', 'delete')->name('category.delete');
        Route::post('category/update/status', 'updateStatus')->name('category.update.status');
        Route::get('category/filter', 'filter')->name('category.filter');
        Route::get('category/import', 'import')->name('import.category');
        Route::post('category/importt', 'importCategort')->name('category.import.post');
        Route::get('category/export', 'exportCategory')->name('category.export');
    });

    // BRAND ROUTE
    Route::controller(BrandController::class)->group(function(){
        Route::get('brand/list', 'index')->name('brand.list');
        Route::post('brand/save', 'save')->name('brand.save');
        Route::get('brand/edit/{id}', 'edit')->name('brand.edit');
        Route::get('brand/view/{id}', 'view')->name('brand.view');
        Route::get('brand/delete/{id}', 'delete')->name('brand.delete');
        Route::post('brand/update/status', 'updateStatus')->name('brand.update.status');
        Route::get('brand/filter', 'filter')->name('brand.filter');
        Route::get('brand/export', 'exportBrand')->name('brand.export');
        Route::get('brand/import', 'import')->name('import.brand');
        Route::post('brand/importt', 'importBrand')->name('brand.import.post');
    });

    // UOM ROUTE
    Route::controller(UOMController::class)->group(function(){
        Route::get('uom/list', 'index')->name('uom.list');
        Route::post('uom/save', 'save')->name('uom.save');
        Route::get('uom/edit/{id}', 'edit')->name('uom.edit');
        Route::get('uom/view/{id}', 'view')->name('uom.view');
        Route::get('uom/delete/{id}', 'delete')->name('uom.delete');
        Route::post('uom/update/status', 'updateStatus')->name('uom.update.status');
        Route::get('uom/filter', 'filter')->name('uom.filter');
        Route::get('uom/export', 'exportUOM')->name('uom.export');
        Route::get('uom/import', 'import')->name('import.uom');
        Route::post('uom/importt', 'importUOM')->name('uom.import.post');
    });

    // COMMODITY ROUTE
    Route::controller(CommodityController::class)->group(function(){
        Route::get('commodity/list', 'index')->name('commodity.list');
        Route::post('commodity/save', 'save')->name('commodity.save');
        Route::get('commodity/edit/{id}', 'edit')->name('commodity.edit');
        Route::get('commodity/view/{id}', 'view')->name('commodity.view');
        Route::get('commodity/delete/{id}', 'delete')->name('commodity.delete');
        Route::post('commodity/update/status', 'updateStatus')->name('commodity.update.status');
        Route::get('commodity/filter', 'filter')->name('commodity.filter');
        Route::get('commodity/export', 'exportcommodity')->name('commodity.export');
        Route::get('commodity/import', 'import')->name('import.commodity');
        Route::post('commodity/importt', 'importCommodity')->name('commodity.import.post');
    });

    // SURVEY ROUTE
    Route::controller(SurveyController::class)->group(function(){
        Route::get('survey/list', 'index')->name('survey.list');
        Route::post('survey/save', 'save')->name('survey.save');
        Route::get('survey/edit/{id}', 'edit')->name('survey.edit');
        Route::post('survey/update', 'updateSurvey')->name('survey.update');
        Route::post('survey/update/status', 'updateStatus')->name('survey.update.status');
        Route::get('survey/zone/markets/{id}', 'getZoneMarket')->name('get.zones.market');
        Route::get('survey/category/product/{id}', 'categoryProduct')->name('get.category.product');
        Route::get('survey/filter', 'filter')->name('survey.filter');
        Route::get('survey/delete/{id}', 'delete')->name('survey.delete');
        Route::get('survey/export', 'exportSurvey')->name('survey.export');
    });

    // REPORT ROUTE
    Route::controller(ReportController::class)->group(function(){
        Route::get('report/list', 'index')->name('report.list');
    });

    // SETTING ROUTE
    Route::controller(SettingController::class)->group(function(){
        Route::get('setting/list', 'index')->name('setting.list');
        Route::Post('setting/update', 'updateSetting')->name('setting.update');
    });

    Route::controller(ZoneController::class)->group(function(){
        Route::get('zone/list', 'index')->name('zone.list');
        Route::post('zone/save', 'save')->name('zone.save');
        Route::get('zone/edit/{id}', 'edit')->name('zone.edit');
        Route::get('zone/view/{id}', 'view')->name('zone.view');
        Route::get('zone/delete/{id}', 'delete')->name('zone.delete');
        Route::post('zone/update/status', 'updateStatus')->name('zone.update.status');
        Route::get('zone/filter', 'filter')->name('zone.filter');
        Route::get('zone/export', 'exportZone')->name('zone.export');
        Route::get('zone/import', 'import')->name('import.zone');
        Route::post('zone/importt', 'importZone')->name('zone.import.post');
    });

    Route::controller(SubmittedSurveyController::class)->group(function(){
        Route::get('submitted/survey/list', 'index')->name('submitted.survey.list');
        Route::get('submitted/survey/filter', 'filter')->name('submitted.survey.filter');
        Route::get('submitted/survey/details/{id}', 'surveyDetail')->name('submitted.survey.details');
        Route::get('submitted/survey/export', 'exportSubmittedSurvey')->name('submitted.survey.export');
        Route::get('submitted/survey-details/filter', 'filterSurveyDetails')->name('submitted.survey.details.filter');
        Route::get('category/commodity/{id}', 'getCategoryCommodity')->name('get.category.commodity');
        Route::get('survey/details/export/{id}', 'exportSurveyDetails')->name('submitted.details.export');
        Route::get('submitted/survey/update/status/{id}', 'updateStatus')->name('submitted.survey.update.status');
        Route::get('submitted/survey/publish/{id}', 'publishSurvey')->name('approve.submitted.survey');
        Route::get('submitted/survey/edit/{id}', 'editSubmittedSurvey')->name('edit.submitted.survey');
        Route::post('submitted/survey/update', 'updateSubmittedSurvey')->name('update.submitted.survey');
    });

    Route::controller(ProfileController::class)->group(function(){
        Route::get('profile-view', 'profile_view')->name('profile.view');
        Route::post('profile-update', 'profile_update')->name('profile.update');
        Route::get('change-password', 'change_password')->name('change.password');
        Route::post('password-update', 'update_password')->name('password.update');
    });

    // CMS ROUTE
    Route::controller(CMSController::class)->group(function(){
        Route::get('cms/about-us','about_us')->name('about.us');
        Route::Post('cms/about-update','about_update')->name('about.update');

        Route::get('cms/privacy-policy','Privacy_Policy')->name('privacy.policy');
        Route::Post('cms/privacy-policy-update','Privacy_Policy_update')->name('privacy.policy.update');
        
        Route::get('cms/terms-conditions', 'terms_conditions')->name('terms.conditions');
        Route::post('cms/terms-conditions-update', 'terms_conditions_update')->name('terms.conditions.update');

        Route::get('cms/our-mission', 'our_mission')->name('our.mission');
        Route::post('cms/our-mission-update', 'our_mission_update')->name('our.mission.update');

        Route::get('cms/our-vision', 'our_vision')->name('our.vision');
        Route::post('cms/our-vision-update', 'our_vision_update')->name('our.vision.update');

        Route::get('cms/our-Aim', 'our_aim')->name('our.aim');
        Route::post('cms/our-aim-update', 'our_aim_update')->name('our.aim.update');

    });

    // SLIDER ROUTE    
    Route::controller(BannerController::class)->group(function(){
        Route::get('banner/list','sliderList')->name('banner.list');
        Route::post('banner/save','saveSlider')->name('banner.save');
        Route::get('banner/edit/{id}','editSlider')->name('banner.edit');
        Route::get('banner/delete/{id}','deleteSlider')->name('banner.delete');
        Route::get('banner/view/{id}','view')->name('banner.view');
        Route::get('banner/filter','filter')->name('banner.filter');
        Route::POST('banner/filter','updateStatus')->name('banner.update.status');


    }); 

    Route::controller (ConsumerProtectionBillController::class)->group(function(){
        Route::get('consumer-protection-bill', 'index')->name('consumer.protectio.bill');
        Route::post('consumer-protection-bill-update', 'update')->name('consumer.protectio.bill.update');

    });

    Route::controller(ConsumerEducationKidsController::class)->group(function(){
        Route::get('consumer-education-kids', 'index')->name('consumer.education.kids');
        Route::post('consumer-education-kids-update', 'update')->name('consumer.education.kids.update');
    });
    Route::controller(PublicHealthActsController::class)->group(function(){
        Route::get('public-health-acts', 'index')->name('public.health.acts');
        Route::post('public-health-acts-update', 'update')->name('public.health.acts.update');
    });

 
    Route::controller(DisclaimerController::class)->group(function(){
        Route::get('disclaimer', 'index')->name('disclaimer');
        Route::post('disclaimer-update', 'update')->name('disclaimer.update');
    });

    Route::controller(BroachersPresentationController::class)->group(function(){
        Route::get('broachers-presentation/list','broacherList')->name('broachers.presentation.list');
        Route::post('broachers-presentation/save','broacherSave')->name('broachers.presentation.save');
        Route::get('broachers-presentation/edit/{id}','broachersedit')->name('broachers.presentation.edit');
        Route::get('broachers-presentation/delete/{id}','broachersDelete')->name('broachers.presentation.delete');
        Route::get('broachers-presentation/view/{id}','view')->name('broachers.presentation.view');
        Route::POST('broachers-presentation/status/','updateStatus')->name('broachers.presentation.status');
        Route::get('broachers-presentation/filter','filter')->name('broachers.presentation.filter');

    });

    Route::controller(AnnouncementController::class)->group(function(){
        Route::get('announcement/list','List')->name('announcement.list');
        Route::post('announcement/save','Save')->name('announcement.save');
        Route::get('announcement/edit/{id}','Edit')->name('announcement.edit');
        Route::get('announcement/delete/{id}','Delete')->name('announcement.delete');
        Route::get('announcement/view/{id}','view')->name('announcement.view');
        Route::POST('announcement/status/','updateStatus')->name('announcement.status');
        Route::get('announcement/filter','filter')->name('announcement.filter');

    });

});