<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\AboutController;
use App\Http\Controllers\frontend\FaqController;
use App\Http\Controllers\frontend\ContactController;
use App\Http\Controllers\frontend\LinkController;
use App\Http\Controllers\frontend\ProductController;
use App\Http\Controllers\frontend\StoreController;

Route::group(['as'=>'frontend.'], function(){
	Route::controller(HomeController::class)->group(function(){
		Route::get('/', 'index')->name('home');
		Route::get('blog-detail/{slug}', 'blog_detail')->name('blog-detail');
		Route::get('article-detail/{slug}','article_detail')->name('article-detail');
		Route::get('quick-links-detail/{slug}','quick_link')->name('quick.link');

		Route::get('notice','news_page')->name('notice.page');

		Route::get('notice-deatils/{slug}','news_deatils')->name('notice.deatils');
		Route::post('submit-feedback','submit_feedback')->name('submit-feedback');
	});

	Route::controller(AboutController::class)->group(function(){
		Route::get('about', 'index')->name('about');
	});

	Route::controller(ProductController::class)->group(function(){
		Route::get('products', 'index')->name('products');
	});

	Route::controller(StoreController::class)->group(function(){
		Route::get('stores', 'index')->name('stores');
		// Route::post('stores', 'index')->name('report.filter');
		Route::get('filter-stores', 'filter')->name('report.filter');
		Route::get('report/export/download', 'report_download')->name('report.download.export');
		Route::get('get/type/category', 'typeCategory')->name('type.category');
		Route::get('get/zone/market', 'typeMarkets')->name('zone.markets');
		Route::get('storess', 'storeMobile')->name('product.mobile.view');
		Route::get('store-report', 'exportReport')->name('export.products.report');
		Route::post('get-highlighted-dates', 'getHighlightedDates')->name('get.highlighted.dates');

	});

	Route::controller(FaqController::class)->group(function(){
		Route::get('faq', 'index')->name('faq');
		Route::get('/search-faq', 'searchFaq')->name('search.faq');
	});

	Route::controller(ContactController::class)->group(function(){
		Route::get('contact-us', 'index')->name('contact');
		Route::post('send-message','send_message')->name('send-message');

	});
	
	Route::controller(LinkController::class)->group(function(){
		Route::get('consumer-education-program', 'educationProgram')->name('consumer.education');
		Route::get('brochures', 'brochures')->name('publication.brochures');
		Route::get('articles', 'articles')->name('publication.articles');
		Route::get('presentations', 'presentations')->name('publication.presentations');

		Route::get('blogs', 'blogs')->name('publication.blog');

		Route::get('tips-and-advice', 'press_release')->name('tips-and-advice');
		Route::get('tips-advice/{slug}', 'press_release_detaails')->name('tips.advice.details');

		Route::get('tips-and-advice-for-consumers', 'tipsAdvice')->name('tips.advice');
		Route::get('consumer-protection-bill', 'consumerProtectionBill')->name('consumer.protection.bill');
		Route::get('public-health-act', 'publicHealthAct')->name('public.health.act');
		Route::get('image-gallery', 'imageGallery')->name('image.gallery');
	
		Route::get('question-of-the-week', 'faq')->name('question.week');
		Route::get('disclaimers', 'disclaimers')->name('disclaimers');
		Route::get('privacy-policy', 'privacy')->name('privacy');
		Route::get('terms-conditions', 'terms')->name('terms');

		Route::get('consumer_corner', 'consumerCorner')->name('consumer_corner');

		Route::get('lid-on-spending', 'lid')->name('lid-on-spending');
		Route::get('cellular-phones', 'cellular')->name('cellular-phones');
		Route::get('consumer-right-responsibilities', 'rights')->name('consumer-right-responsibilities');
		Route::get('backyard-gardening', 'backyard')->name('backyard-gardening');
		Route::get('weight-measure', 'weight')->name('weight-measure');
		Route::get('wise-spender', 'wise')->name('wise-spender');
		
		Route::get('complaint-form', 'complaintForm')->name('complaint.form');
		Route::post('complaint-form', 'complaint_form_process')->name('complaint.form.process');
		Route::get('complete-your-profile/{id}', 'complaint_verify_email')->name('complete-your-profile');
		Route::get('complaint-complete-form/{id}', 'complaint_complete_form')->name('complaint-complete-form');
		Route::post('complaint-complete-form-process', 'complaint_complete_form_process')->name('complaint-complete-form-process');
		Route::get('search-complaint', 'search_complaint')->name('search-complaint');
		Route::get('reload-captcha', 'reloadCaptcha');

	});
});

