<?php 
use App\Models\Setting;
$data =  Setting::first();
?>

<div class="layout-wrapper">

    <!-- ========== Left Sidebar ========== -->
    <div class="main-menu">
        <!-- Brand Logo -->
        <div class="logo-box">
            <a href="{{route('admin.dashboard')}}" class="logo-dark">

            @if($data->company_image != '' )
            <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="dark logo" class="logo-lg ps-4" height="50">
                <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="small logo" class="logo-sm" height="50">
            @else
                <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="dark logo" class="logo-lg"
                    height="100">
                <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="small logo" class="logo-sm" height="50">
            @endif

            </a>
        </div>
        <!--- Menu -->
        <div data-simplebar>
            <ul class="app-menu">
                @canany(['dashboard_view','dashboard_list'])    
                <li class="menu-item">
                    <a href="{{route('admin.dashboard')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
                        <span class="menu-text"> Dashboard </span>
                    </a>
                </li>
               @endcanany
               <!-- @canany(['dashboard_view'])    
                <li class="menu-item">
                    <a href="{{route('admin.dashboard.new')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
                        <span class="menu-text"> Dashboard-2 </span>
                    </a>
                </li>
               @endcanany -->
                @php
                    $surveyActive = request()->is('admin/survey/list') ||
                                    request()->is('admin/survey/*') ||
                                    request()->is('admin/submitted/survey/*') ||
                                    request()->is('admin/submitted/survey-details/*') ||
                                    request()->is('admin/zone/*') ||
                                    request()->is('admin/market/*') ||
                                    request()->is('admin/category/*') ||
                                    request()->is('admin/brand/*') ||
                                    request()->is('admin/uom/*') ||
                                    request()->is('admin/commodity/*') ||
                                    request()->is('admin/survey-report/preview/*');
                @endphp

                @canany(['survey_list', 'submit_survey_list', 'zone_list', 'market_list', 'category_list', 'brand_list', 'uom_list', 'commodity_list'])
                <li class="menu-item {{ $surveyActive ? 'active' : '' }}">
                    <a href="#menuSurvey" data-bs-toggle="collapse" class="menu-link waves-effect {{ $surveyActive ? '' : 'collapsed' }}">
                        <span class="menu-icon"><i data-lucide="copy"></i></span>
                        <span class="menu-text"> Survey Management </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ $surveyActive ? 'show' : '' }}" id="menuSurvey">
                        <ul class="sub-menu">
                            @can('survey_list') 
                            <li class="menu-item {{ request()->is('admin/survey/list') || request()->is('admin/survey/filter') ? 'active' : '' }}">
                                <a href="{{route('admin.survey.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Survey </span>
                                </a>

                            </li>
                            @endcan

                            @can('submit_survey_list')
                            <li
                                 class="menu-item {{ request()->is('admin/submitted/survey/list') || request()->is('admin/submitted/survey/edit/*') ||request()->is('admin/submitted/survey/details/*') || request()->is('admin/submitted/survey/filter') || request()->is('admin/submitted/survey-details/filter/*') || request()->is('admin/submitted/survey-details/filter') || request()->is('admin/survey-report/preview/*') ? 'active' : '' }}">
                                <a href="{{route('admin.submitted.survey.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>

                                    <span class="menu-text"> Submitted Survey </span>
                                </a>
                            </li>
                            @endcan

                            @can('zone_list')

                            <li
                                class="menu-item {{ request()->is('admin/zone/list') || request()->is('admin/zone/filter/*') || request()->is('admin/zone/view') || request()->is('admin/zone/view/*') || request()->is('admin/zone/import') ? 'active' : '' }}">
                                <a href="{{route('admin.zone.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Zone </span>
                                </a>

                            </li>
                            @endcan

                            @can('market_list')
                            <li
                                class="menu-item {{ request()->is('admin/market/list') || request()->is('admin/market/filter') || request()->is('admin/market/view') || request()->is('admin/market/view/*') || request()->is('admin/market/import') ? 'active' : '' }}">
                                <a href="{{route('admin.market.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Stores </span>
                                </a>
                            </li>

                            @endcan


                            @can('category_list')
                            <li
                                class="menu-item {{ request()->is('admin/category/list') || request()->is('admin/category/filter') || request()->is('admin/category/view') || request()->is('admin/category/view/*') || request()->is('admin/category/import') ? 'active' : '' }}">
                                <a href="{{route('admin.category.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Categories </span>
                                </a>
                            </li>
                            @endcan

                            @can('brand_list')
                            <li
                                class="menu-item {{ request()->is('admin/brand/list') || request()->is('admin/brand/filter') || request()->is('admin/brand/view') || request()->is('admin/brand/view/*') || request()->is('admin/brand/import') ? 'active' : '' }}">
                                <a href="{{route('admin.brand.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Brands </span>
                                </a>
                            </li>
                            @endcan

                            @can('uom_list')
                            <li
                                class="menu-item {{ request()->is('admin/uom/list') || request()->is('admin/uom/filter') || request()->is('admin/uom/view') || request()->is('admin/uom/view/*') || request()->is('admin/uom/import') ? 'active' : '' }}">
                                <a href="{{route('admin.uom.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> UOM </span>
                                </a>
                            </li>
                            @endcan


                            @can('commodity_list')

                            <li
                                class="menu-item {{ request()->is('admin/commodity/list') || request()->is('admin/commodity/filter') || request()->is('admin/commodity/view') || request()->is('admin/commodity/view/*') || request()->is('admin/commodity/import') ? 'active' : '' }}">
                                <a href="{{route('admin.commodity.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Commodities </span>
                                </a>

                            </li>

                            <li class="menu-item">
                                <a href="{{route('admin.transform.excel')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Transeform Excel </span>
                                </a>

                            </li>

                            @endcan


                        </ul>
                    </div>
                </li>
                {{-- <li class="menu-item {{ request()->is('admin/market/filter') || request()->is('admin/market/filter/*') || request()->is('admin/market/view') || request()->is('admin/market/view/*') || request()->is('admin/market/import') ? 'active' : '' }}">
                    <a href="#menuSurvey" data-bs-toggle="collapse" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="copy"></i></span>
                        <span class="menu-text"> Survey Managment </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuSurvey">
                        <ul class="sub-menu">

                            @can('survey_list') 
                            <li class="menu-item">
                                <a href="{{route('admin.survey.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Survey </span>
                                </a>

                            </li>
                            @endcan

                            @can('submit_survey_list')
                            <li
                                 class="menu-item {{ request()->is('admin/submitted/survey/list') || request()->is('admin/submitted/survey/edit/*') ||request()->is('admin/submitted/survey/details/*') || request()->is('admin/submitted/survey-details/filter') || request()->is('admin/submitted/survey-details/filter/*') ? 'active' : '' }}">
                                <a href="{{route('admin.submitted.survey.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>

                                    <span class="menu-text"> Submitted Survey </span>
                                </a>
                            </li>
                            @endcan

                            @can('zone_list')

                            <li
                                class="menu-item {{ request()->is('admin/zone/filter') || request()->is('admin/zone/filter/*') || request()->is('admin/zone/view') || request()->is('admin/zone/view/*') || request()->is('admin/zone/import') ? 'active' : '' }}">
                                <a href="{{route('admin.zone.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Zone </span>
                                </a>

                            </li>
                            @endcan

                            @can('market_list')
                            <li
                                class="menu-item {{ request()->is('admin/market/filter') || request()->is('admin/market/filter/*') || request()->is('admin/market/view') || request()->is('admin/market/view/*') || request()->is('admin/market/import') ? 'active' : '' }}">
                                <a href="{{route('admin.market.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Stores </span>
                                </a>
                            </li>

                            @endcan


                            @can('category_list')
                            <li
                                class="menu-item {{ request()->is('admin/category/filter') || request()->is('admin/category/filter/*') || request()->is('admin/category/view') || request()->is('admin/category/view/*') || request()->is('admin/category/import') ? 'active' : '' }}">
                                <a href="{{route('admin.category.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Categories </span>
                                </a>
                            </li>
                            @endcan

                            @can('brand_list')
                            <li
                                class="menu-item {{ request()->is('admin/brand/filter') || request()->is('admin/brand/filter/*') || request()->is('admin/brand/view') || request()->is('admin/brand/view/*') || request()->is('admin/brand/import') ? 'active' : '' }}">
                                <a href="{{route('admin.brand.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Brands </span>
                                </a>
                            </li>
                            @endcan

                            @can('uom_list')
                            <li
                                class="menu-item {{ request()->is('admin/uom/filter') || request()->is('admin/uom/filter/*') || request()->is('admin/uom/view') || request()->is('admin/uom/view/*') || request()->is('admin/uom/import') ? 'active' : '' }}">
                                <a href="{{route('admin.uom.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> UOM </span>
                                </a>
                            </li>
                            @endcan


                            @can('commodity_list')

                            <li
                                class="menu-item {{ request()->is('admin/commodity/filter') || request()->is('admin/commodity/filter/*') || request()->is('admin/commodity/view') || request()->is('admin/commodity/view/*') || request()->is('admin/commodity/import') ? 'active' : '' }}">
                                <a href="{{route('admin.commodity.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Commodities </span>
                                </a>

                            </li>

                            @endcan

                        </ul>
                    </div>
                </li> --}}
                @endcanany

                @can('report_list')
                 <li class="menu-item">
                    <a href="#menuExpagesSettings" data-bs-toggle="collapse" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                        <span class="menu-text"> Report  </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuExpagesSettings">
                        <ul class="sub-menu">
                <li class="menu-item">
                    <a href="{{route('admin.report.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                        <span class="menu-text">Survey Report </span>
                    </a>

                </li>
                 <li class="menu-item">
                    <a href="{{route('admin.surveillance.report.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                        <span class="menu-text">Surveillance Report </span>
                    </a>
                </li>
                </ul>
                </div>
                </li>
                @endcan

                @can('report_list')
                <li class="menu-item">
                    <a href="{{route('admin.log.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                        <span class="menu-text"> Log </span>
                    </a>

                </li>
                @endcan
            
               
                @php
                    $enquiryActive = request()->is('admin/enquiry-list') ||
                                    request()->is('admin/enquiry/*') ||
                                    request()->is('admin/enquiry-categories') ||
                                    request()->is('admin/enquiry-categories/*') ||
                                    request()->is('admin/enquiry-filter'); // Added this line
                @endphp

                            @canany(['enquiry_list','enquiry_view','contact_categories_list','contact_categories_view'])
                            <li class="menu-item {{ $enquiryActive ? 'active' : '' }}">
                                <a href="#menuEnquiryManagement" data-bs-toggle="collapse"
                                class="menu-link waves-effect {{ $enquiryActive ? 'active' : 'collapsed'}}"
                                aria-expanded="{{ $enquiryActive ? 'true' : 'false' }}">
                                    <span class="menu-icon"><i class="fa-solid fa-file"></i></span>
                                    <span class="menu-text"> Contact Us Management </span> 
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse {{ $enquiryActive ? 'show' : '' }}" id="menuEnquiryManagement">
                                    <ul class="sub-menu">

                                        @canany(['enquiry_list','enquiry_view'])
                                        <li class="menu-item {{ request()->is('admin/enquiry-list') || request()->is('admin/enquiry-filter') ? 'active' : '' }}">
                                            <a href="{{route('admin.enquiry.list')}}" class="menu-link waves-effect">
                                                <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                                <span class="menu-text">Contact Us</span>
                                            </a>
                                        </li>
                                        @endcanany

                                        @canany(['contact_categories_list','contact_categories_view'])
                                        <li class="menu-item {{ request()->is('admin/enquiry-categories') || request()->is('admin/enquiry-categories/*') ? 'active' : '' }}">
                                            <a href="{{route('admin.enquiry.category')}}" class="menu-link waves-effect">
                                                <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                                <span class="menu-text">Categories</span>
                                            </a>
                                        </li>
                                        @endcanany

                                    </ul>
                                </div>
                            </li>
                            @endcanany

               
               
                @canany(['complaint_form_list','complaint_form_view','complaint_category_list','complaint_category_view'])
                  <li class="menu-item">
                    <a href="#menuComplaintManagement" data-bs-toggle="collapse" class="menu-link waves-effect">
                    <span class="menu-icon"><i class="fa-solid fa-file"></i></span>
                        <span class="menu-text"> Complaint Management  </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuComplaintManagement">
                        <ul class="sub-menu">
                @canany(['complaint_form_list','complaint_form_view'])
                <li
                    class="menu-item {{ request()->is('admin/complaint-form/list') || request()->is('admin/complaint-form/edit/*') ||request()->is('admin/complaint-form/details/*') || request()->is('admin/complaint-form/filter') || request()->is('admin/complaint-form/filter/*') ? 'active' : '' }}">
                    <a href="{{route('admin.complaint.form.list')}}" class="menu-link waves-effect">
                        
                         <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                        <span class="menu-text">Complaint List</span>
                    </a>
                </li>
                @endcanany
                 @canany(['complaint_category_list','complaint_category_view'])
                  <li
                    class="menu-item {{ request()->is('admin/complaint/category/list') || request()->is('admin/complaint/category/view') || request()->is('admin/complaint/category/view/*') ? 'active' : '' }}">
                    <a href="{{route('admin.complaint.category.list')}}" class="menu-link waves-effect">
                         <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>
                 @endcanany
                </ul>
                </div>
                 @endcanany

                @can('feedback_list')
                <li
                    class="menu-item">
                    <a href="{{route('admin.feedback.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-clipboard"></i></span>
                        <span class="menu-text">Feedback</span>
                    </a>
                </li>
                @endcan

                @canany(['banner_list', 'setting_edit', 'about_us_edit', 'privacy_policy_edit', 'terms_condition_edit', 'our_mission_edit', 'our_vision_edit', 'our_aim_edit', 'consumer_protection_bill_edit', 'consumer_education_edit', 'public_health_edit', 'tips_advice_edit', 'disclaimer_edit', 'announcement_list', 'faq_list', 'publication_list', 'gallery_list', 'post_list', 'quick_links_list', 'useful_links_edit'])
                <li class="menu-item">
                    <a href="#menuExpages" data-bs-toggle="collapse" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="copy"></i></span>
                        <span class="menu-text"> CMS </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuExpages">
                        <ul class="sub-menu">

                            @can('banner_list')
                            <li class="menu-item">
                                <a href="{{route('admin.banner.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Banner </span>
                                </a>
                            </li>
                            @endcan

                            @can('setting_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.setting.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Company Setting</span>
                                </a>
                            </li>
                            @endcan

                            @can('about_us_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.about.us')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> About US </span>
                                </a>
                            </li>
                            @endcan

                            @can('privacy_policy_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.privacy.policy')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Privacy Policy </span>
                                </a>
                            </li>
                            @endcan

                            @can('terms_condition_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.terms.conditions')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Terms & Conditions </span>
                                </a>
                            </li>
                            @endcan

                            @can('our_mission_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.our.mission')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Our Mission </span>
                                </a>
                            </li>
                            @endcan

                            @can('our_vision_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.our.vision')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Our Vision </span>
                                </a>
                            </li>
                            @endcan

                            @can('our_aim_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.our.aim')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Our Aim </span>
                                </a>
                            </li>
                            @endcan

                            @can('consumer_protection_bill_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.consumer.protectio.bill')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Consumer Protection Bill </span>
                                </a>
                            </li>
                            @endcan

                            @can('consumer_education_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.consumer.education.kids')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Consumer Education For Kids Program
                                    </span>
                                </a>
                            </li>
                            @endcan

                            @can('public_health_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.public.health.acts')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Public Health Act </span>
                                </a>
                            </li>
                            @endcan

                            @can('tips_advice_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.tips.advice')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Tips and Advice for Consumer</span>
                                </a>
                            </li>
                            @endcan

                            @can('disclaimer_edit')
                            <li class="menu-item">
                                <a href="{{route('admin.disclaimer')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Disclaimer </span>
                                </a>
                            </li>
                            @endcan

                           

                            @can('faq_list')
                            <li class="menu-item">
                                <a href="{{route('admin.faq.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">FAQ </span>
                                </a>
                            </li>
                            @endcan

                            @can('publication_list')
                            <li
                                class="menu-item {{ request()->is('admin/broachers/presentation/list') || request()->is('admin/broachers/presentation/edit/*') ||request()->is('admin/broachers/presentation/details/*') || request()->is('admin/broachers/presentation/filter') || request()->is('admin/broachers/presentation/filter/*') ? 'active' : '' }}">
                                <a href="{{route('admin.broachers.presentation.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Publication </span>
                                </a>
                            </li>
                            @endcan

                            @can('gallery_list')
                            <li class="menu-item">
                                <a href="{{route('admin.image.gallery.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Gallery </span>
                                </a>
                            </li>
                            @endcan

                            @can('post_list')
                            <li class="menu-item">
                                <a href="{{route('admin.news.updates')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Posts</span>
                                </a>
                            </li>
                            @endcan

                            @can('quick_links_list')
                            <li class="menu-item">
                                <a href="{{route('admin.quick.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Quick Links </span>
                                </a>
                            </li>
                            @endcan

                            @can('useful_links_edit')
                            <li class="menu-item">
                                <a href="{{ route('admin.useful.links') }}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text"> Useful Links </span>
                                </a>
                            </li>
                            @endcan

                        </ul>
                    </div>
                </li>
                @endcanany


                    
                    @canany(['consumer_corner_list','consumer_corner_view','tips_and_advice_list','tips_and_advice_view','notices_list','notices_view'])
                    <li class="menu-item">
                    <a href="#Insights" data-bs-toggle="collapse" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="bar-chart-2"></i></span>
                        <span class="menu-text">Insights & Updates</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="Insights">
                        <ul class="sub-menu">
                         @canany(['consumer_corner_list','consumer_corner_view'])
                            <li
                                class="menu-item {{ request()->is('admin/consumer_corner/list') || request()->is('admin/consumer_corner/edit/*') ||request()->is('admin/consumer_corner/details/*') || request()->is('admin/consumer_corner/filter') || request()->is('admin/consumer_corner/filter/*') ? 'active' : '' }}">
                                <a href="{{route('admin.consumer_corner.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Consumer Corner</span>
                                </a>
                            </li>
                            @endcanany
                            @canany(['tips_and_advice_list','tips_and_advice_view'])
                             <li
                                class="menu-item {{ request()->is('admin/tips_advice/list') || request()->is('admin/tips_advice/edit/*') ||request()->is('admin/tips_advice/details/*') || request()->is('admin/tips_advice/filter') || request()->is('admin/tips_advice/filter/*') ? 'active' : '' }}">
                                <a href="{{route('admin.tips_advice.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Tips and Advice</span>
                                </a>
                            </li>
                            @endcanany
                            @canany(['notices_list','notices_view'])
                             <li
                                class="menu-item {{ request()->is('admin/notices/list') || request()->is('admin/notices/edit/*') ||request()->is('admin/notices/details/*') || request()->is('admin/notices/filter') || request()->is('admin/notices/filter/*') ? 'active' : '' }}">
                                <a href="{{route('admin.notices.list')}}" class="menu-link waves-effect">
                                    <span class="menu-icon"><i class="fa-solid fa-arrow-right"></i></span>
                                    <span class="menu-text">Notices</span>
                                </a>
                            </li>
                            @endcanany
                             </ul>
                    </div>
                             @endcanany

                
            @canany(['users_list','users_view','List','View','audit_list','audit_view'])
            <li class="menu-item">
                    <a href="#menuExpagesSettings" data-bs-toggle="collapse" class="menu-link waves-effect">
                        <span class="menu-icon"><i data-lucide="settings"></i></span>
                        <span class="menu-text"> Settings  </span> 
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuExpagesSettings">
                        <ul class="sub-menu">
                @canany(['users_list','users_view'])
                <li
                    class="menu-item {{ request()->is('admin/user/filter') || request()->is('admin/user/filter/*') ||  request()->is('admin/user/view') || request()->is('admin/user/view/*') || request()->is('admin/user/list') ? 'active' : '' }}">
                    <a href="{{ route('admin.user.list') }}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="menu-text"> User Managment </span>

                    </a>
                </li>
                @endcanany
                @canany(['List','View'])
                <li class="menu-item">
                    <a href="{{route('admin.role.index')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-user-tie"></i></span>
                        <span class="menu-text"> Role </span>
                    </a>
                </li>
                @endcanany
                  @canany(['audit_list','audit_view']) 
                <li class="menu-item">
                    <a href="{{route('admin.audit.log')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-user-tie"></i></span>
                        <span class="menu-text"> Audit Log </span>
                    </a>
                </li>
              @endcanany
                </ul>
                </div>
            </li>
           @endcanany

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link waves-effect" data-bs-toggle="modal"
                        data-bs-target="#exampleModal1">
                        <span class="menu-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="menu-text"> Logout </span>
                    </a>
                </li>

            </ul>
        </div>


        <div class="modal fade home-modal" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="heading log-h mt-3">
                            <h2>Logout</h2>
                            <p>Are you sure, you want to Logout?</p>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4 mb-3">
                            @csrf
                            <div class="text-center">
                                <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="btn btn-danger">Yes, Logout</button>
                                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>