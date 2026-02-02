<!-- Begin page -->

<div class="layout-wrapper">
    <!-- ========== Left Sidebar ========== -->
    <div class="main-menu">
        <!-- Brand Logo -->
        <div class="logo-box">
            <a href="{{route('admin.dashboard')}}" class="logo-dark">
                <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="dark logo" class="logo-lg"
                    height="100">
                <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="small logo" class="logo-sm"
                    height="50">
            </a>
        </div>
        <!--- Menu -->
        <div data-simplebar>
            <ul class="app-menu">
                <li class="menu-item">
                    <a href="{{route('admin.dashboard')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-house"></i></span>
                        <span class="menu-text"> Dashboards </span>
                    </a>
                </li>

                @can('users_list')

                <li
                    class="menu-item {{ request()->is('admin/user/filter') || request()->is('admin/user/filter/*') ||  request()->is('admin/user/view') || request()->is('admin/user/view/*') || request()->is('admin/user/list') ? 'active' : '' }}">
                    <a href="{{ route('admin.user.list') }}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="menu-text"> Users </span>

                     </a>
                  </li>
                  @endcan

                  <li class="menu-item">
                     <a href="{{route('admin.news.updates')}}" class="menu-link waves-effect">
                     <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                     <span class="menu-text"> Posts</span>
                     </a>
                  </li>

                  <li class="menu-item">
                     <a href="{{route('admin.enquiry.list')}}" class="menu-link waves-effect">
                     <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                     <span class="menu-text"> Enquiry List </span>
                     </a>
                  </li>

                  <li class="menu-item">
                     <a href="{{route('admin.faq.list')}}" class="menu-link waves-effect">
                     <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                     <span class="menu-text">FAQ </span>
                     </a>
                  </li>

                  <li class="menu-item">
                     <a href="{{route('admin.image.gallery.list')}}" class="menu-link waves-effect">
                     <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                     <span class="menu-text"> Image Gallery </span>
                     </a>
                  </li>


                  @can('zone_list')

                <li class="menu-item {{ request()->is('admin/zone/filter') || request()->is('admin/zone/filter/*') || request()->is('admin/zone/view') || request()->is('admin/zone/view/*') || request()->is('admin/zone/import') ? 'active' : '' }}">
                    <a href="{{route('admin.zone.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fas fa-th-large"></i></span>
                        <span class="menu-text"> Zone </span>
                    </a>
                </li>
                @endcan

                @can('market_list')

                <li class="menu-item {{ request()->is('admin/market/filter') || request()->is('admin/market/filter/*') || request()->is('admin/market/view') || request()->is('admin/market/view/*') || request()->is('admin/market/import') ? 'active' : '' }}">
                    <a href="{{route('admin.market.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-shop"></i></span>
                        <span class="menu-text"> Market </span>
                    </a>
                </li>
                @endcan


                @can('category_list')
                <li class="menu-item {{ request()->is('admin/category/filter') || request()->is('admin/category/filter/*') || request()->is('admin/category/view') || request()->is('admin/category/view/*') || request()->is('admin/category/import') ? 'active' : '' }}">
                    <a href="{{route('admin.category.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fas fa-th-large"></i>
                        </span>
                        <span class="menu-text"> Categories </span>
                    </a>
                </li>
                @endcan
                @can('brand_list')
                <li
                    class="menu-item {{ request()->is('admin/brand/filter') || request()->is('admin/brand/filter/*') || request()->is('admin/brand/view') || request()->is('admin/brand/view/*') || request()->is('admin/brand/import') ? 'active' : '' }}">
                    <a href="{{route('admin.brand.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-clipboard-list"></i></span>
                        <span class="menu-text"> Brands </span>
                    </a>
                </li>
                @endcan
                @can('uom_list')
                <li
                    class="menu-item {{ request()->is('admin/uom/filter') || request()->is('admin/uom/filter/*') || request()->is('admin/uom/view') || request()->is('admin/uom/view/*') || request()->is('admin/uom/import') ? 'active' : '' }}">
                    <a href="{{route('admin.uom.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-scale-balanced"></i></span>
                        <span class="menu-text"> UOM </span>
                    </a>
                </li>
                @endcan
                @can('commodity_list')

                <li
                    class="menu-item {{ request()->is('admin/commodity/filter') || request()->is('admin/commodity/filter/*') || request()->is('admin/commodity/view') || request()->is('admin/commodity/view/*') || request()->is('admin/commodity/import') ? 'active' : '' }}">
                    <a href="{{route('admin.commodity.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fas fa-th-large"></i></span>
                        <span class="menu-text"> Commodities </span>
                    </a>
                </li>

                @endcan

                @can('survey_list')

                <li class="menu-item">
                    <a href="{{route('admin.survey.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-square-poll-horizontal"></i></span>
                        <span class="menu-text"> Surveys </span>
                    </a>

                </li>
                @endcan

                @can('submit_survey_list')

                <li class="menu-item {{ request()->is('admin/submitted/survey/list') || request()->is('admin/submitted/survey/edit/*') ||request()->is('admin/submitted/survey/details/*') || request()->is('admin/submitted/survey-details/filter') || request()->is('admin/submitted/survey-details/filter/*') ? 'active' : '' }}">
                    <a href="{{route('admin.submitted.survey.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-file-export"></i></span>
                        <span class="menu-text"> Submitted Survey </span>
                    </a>
                </li>

                @endcan

                @can('report_list')
                  
                <li class="menu-item">
                    <a href="{{route('admin.report.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                        <span class="menu-text"> Report </span>
                    </a>
                </li>

                @endcan

                @can('roles_list')

                <li class="menu-item">
                    <a href="{{route('admin.role.index')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-square-poll-vertical"></i></span>
                        <span class="menu-text"> Roles </span>
                    </a>
                </li>

                @endcan


                @can('setting_list')

                <li class="menu-item">
                    <a href="{{route('admin.setting.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text"> Setting </span>
                    </a>
                </li>
                @endcan

                
                @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.banner.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text"> Banner </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.about.us')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text"> About US </span>
                     </a>
                  </li>
                 @endcan


                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.privacy.policy')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text"> Privacy Policy </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.terms.conditions')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Terms & Conditions </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.our.mission')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Our Mission </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.our.vision')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Our Vision </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.our.aim')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Our Aim </span>
                     </a>
                  </li>
                 @endcan


                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.consumer.protectio.bill')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Consumer Protection Bill  </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.consumer.education.kids')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Consumer Education For Kids Program
                        </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.public.health.acts')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Public Health Act </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.tips.advice')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Tips & Advice  </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item">
                     <a href="{{route('admin.disclaimer')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Disclaimer </span>
                     </a>
                  </li>
                 @endcan
                 
                 @can('setting_list')
                  <li class="menu-item {{ request()->is('admin/broachers/presentation/list') || request()->is('admin/broachers/presentation/edit/*') ||request()->is('admin/broachers/presentation/details/*') || request()->is('admin/broachers/presentation/filter') || request()->is('admin/broachers/presentation/filter/*') ? 'active' : '' }}">
                     <a href="{{route('admin.broachers.presentation.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Broachers & Presentation </span>
                     </a>
                  </li>
                 @endcan

                 @can('setting_list')
                  <li class="menu-item {{ request()->is('admin/announcement/list') || request()->is('admin/announcement/edit/*') ||request()->is('admin/announcement/details/*') || request()->is('admin/announcement/filter') || request()->is('admin/announcement/filter/*') ? 'active' : '' }}">
                     <a href="{{route('admin.announcement.list')}}" class="menu-link waves-effect">
                        <span class="menu-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="menu-text">Announcement </span>
                     </a>
                  </li>
                 @endcan

                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link waves-effect" data-bs-toggle="modal"
                        data-bs-target="#exampleModal1">
                        <span class="menu-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="menu-text"> Logout </span>
                    </a>
                </li>

            </ul>
        </div>
    </div>

    <!-- ============================================================== -->

    <!-- Modal -->

    <div class="modal fade home-modal" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="heading log-h mt-5">
                        <h2>Logout</h2>
                        <p>Are you sure, you want to Logout</p>
                    </div>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4 mb-5">
                        @csrf
                        <div class="row">
                            <div class="text-center">
                                <button type="button"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="btn btn-save">Yes, Logout</button>
                                <button type="button" data-bs-dismiss="modal"
                                    class="btn btn-save cancel-logout">Cancel</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>