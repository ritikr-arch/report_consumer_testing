@php
    use App\Models\Setting;
    $setting = Setting::find('1');
@endphp
<style>
.current-menu-item > a {
    color: #00a258;
    font-weight: bold;
}

.header-layout5 .main-menu > ul > li.current-menu-item > a::after {
    color: #00a258 !important;
    font-weight: bold !important;
}


</style>
<div class="th-menu-wrapper">
    <div class="th-menu-area">
        <div class="mobile-logo">
            <a href="">
                <img src="{{ asset('admin/images/company_setting/' . $setting['company_image']) }}" alt=""
                    width="180px;">
            </a>
            <div class="close-menu">
                <button class="th-menu-toggle">
                    <i class="fal fa-times"></i>
                </button>
            </div>
        </div>
        <div class="th-mobile-menu">
    <ul>
        <li class="{{ request()->routeIs('frontend.home') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.home') }}">Home</a>
        </li>
        <li class="{{ request()->routeIs('frontend.about') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.about') }}">About us</a>
        </li>
        <li class="{{ request()->routeIs('frontend.stores') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.stores') }}">Price Collection</a>
        </li>
        <li class="menu-item-has-children
            {{ str_starts_with(request()->route()->getName(), 'frontend.publication.') ||
               str_starts_with(request()->route()->getName(), 'frontend.question.week') ||
               str_starts_with(request()->route()->getName(), 'frontend.tips.advice') ||
               str_starts_with(request()->route()->getName(), 'frontend.consumer.') ||
               request()->routeIs('frontend.lid-on-spending') ||
               request()->routeIs('frontend.cellular-phones') ||
               request()->routeIs('frontend.consumer-right-responsibilities') ||
               request()->routeIs('frontend.backyard-gardening') ||
               request()->routeIs('frontend.weight-measure') ||
               request()->routeIs('frontend.wise-spender') 
               ? 'current-menu-item' : '' }}">
            <a href="#">Useful Links</a>
            <ul class="sub-menu">
                <li><a href="https://play.google.com/store/games?device=windows" class="after-none">SKN Shopper App</a></li>

                <li class="menu-item-has-children">
                    <a href="#">Publications</a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('frontend.publication.brochures') }}">Brochures</a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.publication.articles') }}">Articles</a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.publication.presentations') }}">Presentations</a>
                        </li>
                        <li>
                            <a href="{{ route('frontend.image.gallery') }}">Gallery</a>
                        </li>
                    </ul>
                </li>


                <li class="menu-item-has-children">
                        <a href="#">Consumer Corner</a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('frontend.consumer.education') }}" class="after-none">Consumer Education for Kids Program</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.question.week') }}" class="after-none">Questions of the week</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.tips.advice') }}" class="after-none">Tips and advice for consumers</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.consumer.protection.bill') }}" class="after-none">Consumer Protection Bill</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.public.health.act') }}" class="after-none">Public Health Act</a>
                            </li>
                        </ul>
                    </li>


                <li>
                    <a href="{{ route('frontend.lid-on-spending') }}" class="after-none">Lid On Spending</a>
                </li>
                <li>
                    <a href="{{ route('frontend.cellular-phones') }}" class="after-none">Cellular Phones</a>
                </li>
                <li>
                    <a href="{{ route('frontend.consumer-right-responsibilities') }}" class="after-none">Consumer Rights & Responsibilities</a>
                </li>
                <li>
                    <a href="{{ route('frontend.backyard-gardening') }}" class="after-none">Backyard Gardening</a>
                </li>
                <li>
                    <a href="{{ route('frontend.weight-measure') }}" class="after-none">Weight Measure</a>
                </li>
                <li>
                    <a href="{{ route('frontend.wise-spender') }}" class="after-none">Wise Spender</a>
                </li>
            </ul>
        </li>
        <li class="{{ request()->routeIs('frontend.faq') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.faq') }}">FAQ</a>
        </li>
        <li class="{{ request()->routeIs('frontend.contact') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.contact') }}">Contact Us</a>
        </li>
        <li class="{{ request()->routeIs('frontend.complaint.form') ? 'current-menu-item' : '' }}">
            <a href="{{ route('frontend.complaint.form') }}">File a Complaint</a>
        </li>
    </ul>
</div>

    </div>
</div>
<div class="popup-search-box d-none d-lg-block">
    <button class="searchClose">
        <i class="fal fa-times"></i>
    </button>
    <form action="#">
        <input type="text" placeholder="What are you looking for?">
        <button type="submit">
            <i class="fal fa-search"></i>
        </button>
    </form>
</div>
<header class="th-header  header-layout5">
    <div class="header-top">
        <div class="container th-container">
            <div class="row justify-content-center justify-content-md-between align-items-center gy-2">
                <div class="col-auto d-none d-lg-inline-block d-md-inline-block">
                    <span class="header-notice pe-1">
                        <i class="fa fa-phone" aria-hidden="true"></i>&nbsp; {{ $setting['phone'] }}
                        &nbsp;&nbsp;|&nbsp;&nbsp; </span>
                    <span class="header-notice pe-1">
                       <a href="mailto:{{ $setting['email_address'] }}" style="color:#ffffff;"> <i class="far fa-envelope"></i>&nbsp;{{ $setting['email_address'] }}</span></a>
                </div>
                <div class="col-auto">
                    <div class="header-links">
                        <ul>
                            <li class="d-none d-lg-inline-block">
                                <ul class="high-last">
                                    <li>
                                        <div class="font-change-change  grid-align-center">
                                            <a href="javascript:void(0);" title="Increase font size" id="btn-increase"
                                                style="font-size: 15px;"> +A </a>
                                            <a class="mx-2" href="javascript:void(0);" title="Reset font size"
                                                id="btn-origs" style="font-size: 15px;"> A </a>
                                            <a href="javascript:void(0);" title="Decrease font size" id="btn-decrease"
                                                style="font-size: 15px;"> -A </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="header-social">
                                    <!-- <span class="social-title">Follow Us On:</span> -->
                                    <a href="{{ $setting['social_fb'] }}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="{{ $setting['social_twitter'] }}">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="{{ $setting['linked_in'] }}">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="{{ $setting['social_instagram'] }}">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </div>
                            </li>
                            <a href="{{ route('login') }}">
                                <li style="color:#fff; cursor:pointer;">Login</li>
                            </a>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Menu -->
   <div class="sticky-wrapper">
    <div class="menu-area">
        <div class="container th-container">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <div class="header-logo">
                        <a href="{{ route('frontend.home') }}">
                            <img src="{{ asset('admin/images/company_setting/' . $setting['company_image']) }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-auto">
                    <nav class="main-menu d-none d-lg-inline-block">
                        <ul>
                            <li class="{{ request()->routeIs('frontend.home') ? 'current-menu-item' : '' }}">
                                <a href="{{ route('frontend.home') }}">Home</a>
                            </li>
                            <li class="{{ request()->routeIs('frontend.about') ? 'current-menu-item' : '' }}">
                                <a href="{{ route('frontend.about') }}">About us</a>
                            </li>
                            <li class="{{ request()->routeIs('frontend.stores') ? 'current-menu-item' : '' }}">
                                <a href="{{ route('frontend.stores') }}">Price Collection</a>
                            </li>
                            <li class="menu-item-has-children {{ str_starts_with(request()->route()->getName(), 'frontend.publication.') ||
               str_starts_with(request()->route()->getName(), 'frontend.question.week') ||
               str_starts_with(request()->route()->getName(), 'frontend.tips.advice') ||
               str_starts_with(request()->route()->getName(), 'frontend.consumer.') ||
               request()->routeIs('frontend.lid-on-spending') ||
               request()->routeIs('frontend.cellular-phones') ||
               request()->routeIs('frontend.consumer-right-responsibilities') ||
               request()->routeIs('frontend.backyard-gardening') ||
               request()->routeIs('frontend.weight-measure') ||
               request()->routeIs('frontend.wise-spender') 
               ? 'current-menu-item' : '' }}">
                                <a href="#">Useful Links</a>
                                <ul class="sub-menu">
                                    <li><a href="https://play.google.com/store/games?device=windows" class="after-none">SKN Shopper App</a></li>

                                    <li class="menu-item-has-children">
                                        <a href="#">Publications</a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="{{ route('frontend.publication.brochures') }}">Brochures</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.publication.articles') }}">Articles</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.publication.presentations') }}">Presentations</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.image.gallery') }}">Gallery</a>
                                            </li>
                                        </ul>
                                    </li>


                                    <li class="menu-item-has-children">
                                        <a href="#">Consumer Corner</a>
                                        <ul class="sub-menu">
                                            <li>
                                                <a href="{{ route('frontend.question.week') }}" class="after-none">Questions of the week</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.tips.advice') }}" class="after-none">Tips and advice for consumers</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.consumer.education') }}" class="after-none">Consumer Education for Kids Program</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.consumer.protection.bill') }}" class="after-none">Consumer Protection Bill</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('frontend.public.health.act') }}" class="after-none">Public Health Act</a>
                                            </li>
                                        </ul>
                                    </li>


                                    <li>
                                        <a href="{{ route('frontend.lid-on-spending') }}" class="after-none">Lid On Spending</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.cellular-phones') }}" class="after-none">Cellular Phones</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.consumer-right-responsibilities') }}" class="after-none">Consumer Rights & Responsibilites</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.backyard-gardening') }}" class="after-none">Backyard Gardening</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.weight-measure') }}" class="after-none">Weight Measure</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('frontend.wise-spender') }}" class="after-none">Wise Spender</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ request()->routeIs('frontend.faq') ? 'current-menu-item' : '' }}">
                                <a href="{{ route('frontend.faq') }}">FAQ</a>
                            </li>
                            <li class="{{ request()->routeIs('frontend.contact') ? 'current-menu-item' : '' }}">
                                <a href="{{ route('frontend.contact') }}">Contact Us</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-auto">
                    <div class="header-button">
                        <a href="{{ route('frontend.complaint.form') }}" class="th-btn border">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
                                <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
                            </svg> File a Complaint
                        </a>
                        <button class="icon-btn th-menu-toggle d-inline-block d-lg-none">
                            <i class="far fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</header>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // $(document).ready(function () {
        //     function isiOS() {
        //         return /iPhone|iPad|iPod/i.test(navigator.userAgent);
        //     }

        //     let currentPath = window.location.pathname;
        //     let isHomePage = (currentPath === "/" || currentPath === "/home");

        //     // Adjust if your homepage route is named differently (like /home or /dashboard)

        //     if (isiOS() && !isHomePage && $("#custom-back-button").length === 0) {
        //         let backButton = $("<button id='custom-back-button'>←</button>");

        //         backButton.css({
        //             "position": "fixed",
        //             "top": "35px",
        //             "left": "20px",
        //             "background": "red",
        //             "color": "white",
        //             "padding": "12px 18px",
        //             "border-radius": "49px",
        //             "border": "none",
        //             "font-size": "16px",
        //             "box-shadow": "0px 2px 5px rgba(0,0,0,0.2)",
        //             "cursor": "pointer",
        //             "z-index": "9999",
        //             "height": "50px"
        //         });

        //         backButton.click(function () {
        //             window.history.back();
        //         });

        //         // Make sure this class exists in your HTML
        //         if ($(".mobile-sticky-menu-r").length) {
        //             $(".mobile-sticky-menu-r").append(backButton);
        //         } else {
        //             $("body").append(backButton);
        //         }
        //     }
        // });
    </script>
