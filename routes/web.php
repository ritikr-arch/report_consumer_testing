<?php

use Illuminate\Support\Facades\Request;

use Maatwebsite\Excel\Row;



use App\Models\ConsumerEducationKids;



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;

use App\Http\Controllers\admin\CMSController;

use App\Http\Controllers\admin\FaqController;

use App\Http\Controllers\admin\UOMController;

use App\Http\Controllers\admin\NewsController;

use App\Http\Controllers\admin\UserController;

use App\Http\Controllers\admin\ZoneController;

use App\Http\Controllers\admin\BrandController;

use App\Http\Controllers\admin\StaffController;

use App\Http\Controllers\admin\BannerController;

use App\Http\Controllers\admin\MarketController;

use App\Http\Controllers\admin\ReportController;

use App\Http\Controllers\admin\SurveillanceReportController;

use App\Http\Controllers\admin\SurveyController;

use App\Http\Controllers\admin\EnquiryController;

use App\Http\Controllers\admin\ProfileController;

use App\Http\Controllers\admin\SettingController;

use App\Http\Controllers\admin\CategoryController;

use App\Http\Controllers\admin\CommodityController;



use App\Http\Controllers\admin\DisclaimerController;

use App\Http\Controllers\admin\ConsumerCornerController;

use App\Http\Controllers\admin\TipsAdviceController;

use App\Http\Controllers\admin\NoticesController;

use App\Http\Controllers\admin\TipsAndAdviceController;

use App\Http\Controllers\admin\ImageGalleryController;



use App\Http\Controllers\admin\DashboardController;



use App\Http\Controllers\admin\SubmittedSurveyController;

use App\Http\Controllers\admin\PublicHealthActsController;

use App\Http\Controllers\admin\BroachersPresentationController;

use App\Http\Controllers\admin\ComplaintFormController;

use App\Http\Controllers\admin\ConsumerEducationKidsController;

use App\Http\Controllers\admin\ConsumerProtectionBillController;

use App\Http\Controllers\admin\QuickLinkController;

use App\Http\Controllers\admin\AuditLogController;



use App\Http\Controllers\admin\FeedbackController;

use App\Http\Controllers\admin\UsefulController;

use App\Http\Controllers\admin\ComplaintCategoryController;

use App\Http\Controllers\admin\LogController;

use App\Http\Controllers\admin\ExcelTransformController;



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

    $data = request()->query('query');

    if($data){

        Artisan::call($data);

    }else{

        Artisan::call('migrate');

    }

    return "Table created successfully";

});



Route::get('clear-cache', function(){

    Artisan::call('cache:clear');

    Artisan::call('config:clear');

    Artisan::call('route:clear');

    Artisan::call('view:clear');

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

        Route::get('/dashboard-new', 'newDashboard')->name('dashboard.new');

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

        Route::get('user/export', 'exportUsers')->name('user.export');

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



        Route::get('brand/get-categories-by-type','getCategoriesByType')->name('brand.get-categories-by-type');

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



        Route::get('uom/get-categories-by-type','getBrandsByCategory')->name('uom.get-categories-by-type');

        Route::get('uom/get-uom-by-brand','getUomsByBrand')->name('uom.get-uom-by-brand');



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

        Route::get('commodity/get-brands-by-category','getBrandsByCategory')->name('commodity.get-brands-by-category');

        Route::get('commodity/get-uoms-by-brand', 'getUomsByBrand')->name('commodity.get-uoms-by-brand');



    });



    // SURVEY ROUTE

    Route::controller(SurveyController::class)->group(function(){

        Route::get('survey/list', 'index')->name('survey.list');

        Route::post('survey/save', 'save')->name('survey.save');

        Route::get('survey/edit/{id}', 'edit')->name('survey.edit');

        Route::post('survey/update', 'updateSurvey')->name('survey.update');

        Route::post('survey/update/status', 'updateStatus')->name('survey.update.status');

        Route::post('survey/zone/markets', 'getZoneMarket')->name('get.zones.market');

        Route::post('survey/type/category', 'getTypeCategory')->name('get.type.category');

        Route::get('survey/category/product/{id}', 'categoryProduct')->name('get.category.product');

        Route::get('survey/filter', 'filter')->name('survey.filter');

        Route::get('survey/delete/{id}', 'delete')->name('survey.delete');

        Route::get('survey/export', 'exportSurvey')->name('survey.export');

    });



    // REPORT ROUTE

    Route::controller(ReportController::class)->group(function(){

        Route::get('survey-report/list', 'index')->name('report.list');

        Route::get('survey-report/filter', 'filter')->name('report.filter');

        Route::get('survey-report/details/{id}', 'surveyReportDetails')->name('survey.report.details');

        Route::get('survey-report/filter/details', 'reportDetailsFilter')->name('report.detail.filter');

        // Route::get('report/search-price-data', 'searchPriceData')->name('search.report.price.data');

        Route::get('survey-report/report/export', 'exportSurveyReport')->name('submitted.survey.report.export');

        Route::get('survey-report/medication-report/export', 'exportMedicationSurveyReport')->name('submitted.survey.medication.report.export');

        Route::get('survey-report/price-analysis/report/{id}', 'exportPriceAnalysisReport')->name('submitted.survey.price.analysis.report');

        Route::get('export-price-analysis-report', 'export_price_analysis_report')->name('export.price.analysis.report');



    });



    // Surveillance Report ROUTE

    Route::controller(SurveillanceReportController::class)->group(function(){

        Route::get('surveillance-report/list', 'index')->name('surveillance.report.list');

        Route::get('surveillance-report-test/list', 'index_test')->name('surveillance.report.test.list');

        Route::get('surveillance-report/export', 'export')->name('surveillance.report.export');

        Route::get('get-commodities-by-categories', 'getCommoditiesByCategories')->name('get-commodities-by-categories');

        Route::post('price-fluctuations', 'Price_Fluctuations')->name('price.fluctuations');

        Route::post('comparative-average', 'comparativeAverage')->name('comparative.average');

        Route::post('price-fluctuations-per-quarter', 'PriceFluctuationsPerQuarter')->name('price.fluctuations.per.quarter');

        Route::post('total-average-price', 'totalAveragePriceReport')->name('reports.total.average');

        

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

        Route::get('survey/approve/{id}', 'updateSurveyStatus')->name('submitted.survey.approve');

        Route::get('approve/multiple-survey', 'approveSubmittedSurvey')->name('approve.survey.report');

        Route::get('survey/approval/{id}', 'approveSurvey')->name('approve.survey');

        Route::get('survey/publish', 'publishSurveys')->name('publish.survey');

        Route::post('survey/unpublish', 'unpublish')->name('survey.unpublish');

        Route::post('single/survey/publish', 'publishSingleSurvey')->name('single.survey.publish');



        Route::get('survey-report/preview/{id}', 'surveyReportpreview')->name('survey.report.preview');

        Route::get('import-survey', 'importSurvey')->name('import.survey');

        Route::post('import-surveys', 'importSurveys')->name('import.surveys');



        Route::get('submitted/survey/price-log/{id}', 'priceLog')->name('price.log');

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



        Route::post('banner/heading/save','BannerHeadingSeve')->name('banner.heading.seve');

        Route::get('banner/heading','banner_heading')->name('banner.heading');

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





    Route::controller(TipsAndAdviceController::class)->group(function(){

        Route::get('tips-advice', 'index')->name('tips.advice');

        Route::post('tips-advice-update', 'update')->name('tips.advice.update');



    });



    Route::controller(EnquiryController::class)->group(function(){

        Route::get('enquiry-list', 'index')->name('enquiry.list');

        Route::get('enquiry-filter', 'filter')->name('enquiry.filter');

        Route::get('enquiry-update', 'update')->name('enquiry.update');

        Route::get('enquiry-categories', 'enquiryCategories')->name('enquiry.category');

        Route::post('save-enquiry-category', 'saveCategory')->name('save.enquiry.category');

        Route::post('update-enquiry-category-status', 'updateStatus')->name('update.enquiry.category.status');

        Route::get('enquiry/category/edit/{id}', 'edit')->name('enquiry.category.edit');

        Route::get('enquiry/category/delete/{id}', 'delete')->name('enqiry.category.delete');

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

    

    Route::controller(NewsController::class)->group(function(){

        Route::get('news/list', 'index')->name('news.updates');

        Route::post('news/save', 'save')->name('news.save');

        Route::post('news-unique-slug', 'newsUniqueSlug')->name('news.unique.slug');

        Route::post('news/status/update', 'updateStatus')->name('news.update.status');

        Route::get('/news/edit/{id}', 'editNews')->name('news.edit');

        Route::get('news/view/{id}', 'view')->name('news.view');

        Route::get('news/filter', 'filter')->name('news.filter');

        Route::get('news/delete/{id}', 'delete')->name('news.delete');

    });



    Route::controller(FaqController::class)->group(function(){

        Route::get('faq/list', 'index')->name('faq.list');

        Route::post('faq/unique/slug', 'faqUniqueSlug')->name('faq.unique.slug');

        Route::post('faq/save', 'save')->name('faq.save');

        Route::get('/faq/edit/{id}', 'editFaq')->name('faq.edit');

        Route::get('/faq/view/{id}', 'view')->name('faq.view');

        Route::post('faq/status/update', 'updateStatus')->name('faq.update.status');

        Route::get('faq/filter', 'filter')->name('faq.filter');

        Route::get('faq/delete/{id}', 'delete')->name('faq.delete');

        

    });



    Route::controller(FaqController::class)->group(function(){

        Route::get('faq/type/list', 'type_index')->name('faq.type.list');

        Route::post('faq/type/save', 'type_save')->name('faq.type.save');

        Route::get('/faq/type/edit/{id}', 'type_editFaq')->name('faq.type.edit');

        Route::post('faq/type/status/update', 'type_updateStatus')->name('faq.type.update.status');

        Route::get('faq/type/delete/{id}', 'type_delete')->name('faq.type.delete');

    });



    Route::controller(ImageGalleryController::class)->group(function(){

        Route::get('image-gallery/list', 'index')->name('image.gallery.list');

        Route::post('image-gallery/unique-slug/update', 'uniqueSlug')->name('image.gallery.unique.slug');

        Route::post('image-gallery/save', 'save')->name('image.gallery.save');

        Route::get('image-gallery/edit/{id}', 'editImageGallery')->name('image.gallery.edit');

        Route::post('image-gallery/update', 'updateStatus')->name('image.gallery.update.status');

        Route::get('image-gallery/filter', 'filter')->name('image.gallery.filter');

        Route::get('image-gallery/delete/{id}', 'delete')->name('image.gallery.delete');

        Route::get('multiple-image-gallery/delete/{id}', 'deleteMultiImage')->name('delete.multiImage.gallery');

    });



    Route::controller(ConsumerCornerController::class)->group(function(){

        Route::get('consumer_corner/list','List')->name('consumer_corner.list');

        Route::post('consumer_corner/save','Save')->name('consumer_corner.save');

        Route::get('consumer_corner/edit/{id}','Edit')->name('consumer_corner.edit');

        Route::get('consumer_corner/delete/{id}','Delete')->name('consumer_corner.delete');

        Route::get('consumer_corner/view/{id}','view')->name('consumer_corner.view');

        Route::POST('consumer_corner/status/','updateStatus')->name('consumer_corner.status');

        Route::get('consumer_corner/filter','filter')->name('consumer_corner.filter');



    });



    Route::controller(TipsAdviceController::class)->group(function(){

        Route::get('tips_advice/list','List')->name('tips_advice.list');

        Route::post('tips_advice/save','Save')->name('tips_advice.save');

        Route::get('tips_advice/edit/{id}','Edit')->name('tips_advice.edit');

        Route::get('tips_advice/delete/{id}','Delete')->name('tips_advice.delete');

        Route::get('tips_advice/view/{id}','view')->name('tips_advice.view');

        Route::POST('tips_advice/status/','updateStatus')->name('tips_advice.status');

        Route::get('tips_advice/filter','filter')->name('tips_advice.filter');



    });



    Route::controller(NoticesController::class)->group(function(){

        Route::get('notices/list','List')->name('notices.list');

        Route::post('notices/save','Save')->name('notices.save');

        Route::get('notices/edit/{id}','Edit')->name('notices.edit');

        Route::get('notices/delete/{id}','Delete')->name('notices.delete');

        Route::get('notices/view/{id}','view')->name('notices.view');

        Route::POST('notices/status/','updateStatus')->name('notices.status');

        Route::get('notices/filter','filter')->name('notices.filter');



    });



    



    Route::controller(ComplaintFormController::class)->group(function(){



        Route::get('complaint-form/index','index')->name('complaint.form.index');

        Route::post('complaint-form/process','process')->name('complaint.form.process');

        Route::get('complaint-form/list','List')->name('complaint.form.list');

        Route::post('complaint-form/save','Save')->name('complaint.form.save');

        Route::post('complaint-form/assigned','assigneedTo')->name('complaint.form.assigned');

        Route::get('complaint-form/edit/{id}','Edit')->name('complaint.form.edit');

        Route::get('complaint-form/delete/{id}','Delete')->name('complaint.form.delete');

        Route::get('complaint-form/view/{id}','view')->name('complaint.form.view');

        Route::POST('complaint-form/status/','updateStatus')->name('complaint.form.status');

        Route::get('complaint-form/filter','filter')->name('complaint.form.filter');



        // route::get( Complaint  Form)



    });



    // CATEGORY ROUTE

    Route::controller(ComplaintCategoryController::class)->group(function(){

        Route::get('complaint/category/list', 'index')->name('complaint.category.list');

        Route::post('complaint/category/save', 'save')->name('complaint.category.save');

        Route::get('complaint/category/edit/{id}', 'edit')->name('complaint.category.edit');

        Route::get('complaint/category/view/{id}', 'view')->name('complaint.category.view');

        Route::get('complaint/category/delete/{id}', 'delete')->name('complaint.category.delete');

        Route::post('complaint/category/update/status', 'updateStatus')->name('complaint.category.update.status');

    

    });

    Route::controller(QuickLinkController::class)->group(function(){

        Route::get('quick-link/list', 'index')->name('quick.list');

        Route::post('quick-link/save', 'save')->name('quick.save');

        Route::post('quick-unique-slug', 'quickUniqueSlug')->name('quick.unique.slug');

        Route::post('quick-link/status/update', 'updateStatus')->name('quick.update.status');

        Route::get('/quick-link/edit/{id}', 'editQuick')->name('quick.edit');

        Route::get('quick-link/view/{id}', 'view')->name('quick.view');

        Route::get('quick-link/filter', 'filter')->name('quick.filter');

        Route::get('quick-link/delete/{id}', 'delete')->name('quick.delete');



    });



    Route::controller(FeedbackController::class)->group(function(){

        Route::get('feedback/list', 'index')->name('feedback.list');

        Route::get('feedback/filter', 'filter')->name('feedback.filter');

        Route::get('update/status', 'update')->name('update.read.status');

    });



    Route::controller(UsefulController::class)->group(function(){

        Route::get('useful-links', 'index')->name('useful.links');

        Route::post('get-useful-content','edit')->name('get-useful-content');

        Route::post('update-useful-content','update')->name('update-useful-content');

    });



    Route::controller(AuditLogController::class)->group(function(){

        Route::get('audit/logs', 'index')->name('audit.log');

        Route::get('audit/logs-details/{id}', 'details')->name('audit.log.details');

    });



    Route::controller(LogController::class)->group(function(){

        Route::get('log/list', 'index')->name('log.list');

        Route::get('log/filter', 'filter')->name('log.filter');

    });

    Route::controller(ExcelTransformController::class)->group(function(){
        Route::get('transform-excel', 'index')->name('transform.excel');
        Route::post('upload-excel', 'upload')->name('upload.excel');
    });



});