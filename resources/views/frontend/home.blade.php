@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
    <style>
        ul li {
            list-style-type: unset !important;
        }
        .quick-link-content {
    word-break: break-word;
    overflow-wrap: break-word;
}
img.new-icon{
   position: absolute !important;
    width: 58px !important;
    top: -21px !important;
    left: 74px !important;
    z-index: 19 !important; 
}
.th-btn.border.quick-press-btn.view-all:hover {
    background-color: transparent !important;
    color: white !important;
    border-color: white !important; /* Optional: if you want white border too */
}

@media (max-width: 768px) {
    .th-btn.border.quick-press-btn.view-all:hover {
        background-color: #00a258 !important;
        color: white !important;
    }
}



    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Start of Banner -->
    @if (!empty($banner) && count($banner) > 0)
        <div id="demo" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach ($banner as $index => $values)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        @if ($values['type'] == 'video')
                            <video id="myVideo" class="d-block w-100" autoplay muted playsinline webkit-playsinline loop>
                                <source src="{{ asset('admin/videos/banner/' . $values['video']) }}" type="video/mp4">

                            </video>
                        @else
                            <img src="{{ asset('admin/images/banner/' . $values['image']) }}" alt="" class="d-block"
                                style="width:100%">
                        @endif
                        <!--   @if (isset($heading) && $heading != '')
    <div class="carousel-caption">
                                <h3>{{ $heading->title }}</h3>
                                <p> {{ $heading->content }} </p>
                            </div>
    @endif -->

                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    @endif
    <!-- End of Banner -->

    <!-- Start of News Alert -->
    @if (!empty($news) && count($news) > 0)
        <div class="container1">
            <div class="headertext">Notice Alerts:</div>
            <div>
                <marquee style="width:100%;color: #fff;position: relative;" onmouseover="this.stop();"
                    onmouseout="this.start();" direction="left" behavior="scroll" scrollamount="7">
                    <img src="{{ asset('frontend/img/new-gif.webp') }}" class="marqu-img">
                    @foreach ($news as $values)
                        <a class="text-white" href="{{ route('frontend.notice.deatils', $values->id) }}"> <span>
                                {{ $values['title'] }}</span> </a>&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp;
                    @endforeach
                </marquee>
                <img src="{{ asset('frontend/img/new-gif.webp') }}" alt="new" class="new-icon">
            </div>
        </div>
    @endif
    <!-- End of News Alert -->

    <!-- Start of who we are -->
    <section class="space-mob service-area3 overflow-hidden space" id="service-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                    <div class="pe-xl-2">
                        <div class="title-area mb-20">
                            <h2 class="sec-title mb-2">{{ $about['title'] }}</h2>
                        </div>
                        <p class="text-justify">{!! Str::limit(ucfirst($about['content']), 510, '') !!}</p>
                        <!-- <p class="text-justify">{!! Str::limit(strip_tags(ucfirst($about['content'])), 800, '...') !!}</p> -->

                        <div class="btn-group mb-4 mb-lg-0">
                            <a href="{{ route('frontend.about') }}" class="th-btn border">Read More <i
                                    class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 who-we-img">
                    <img src="{{ asset('admin/images/cms/' . $about['image']) }}" alt="gray-pipe-with-water-coming">
                </div>
            </div>
        </div>
    </section>
    <!-- End of Who we are -->

    <!-- Quick Links -->
    @if (!empty($quick_link) && count($quick_link) > 0)
        <div class="container space four-box space-mob">
            <div class="row gy-4  justify-content-center">
                <div class="col-xl-12">
                    <div class="title-area mb-20 text-center">
                        <h2 class="sec-title mb-1">Quick Links</h2>
                        <p class="">Empowering consumers to make informed decisions while fostering accountability</p>
                    </div>
                    <div class="service-box-2_wrapp">

                        @foreach ($quick_link as $links)
                            @php

                                $randomColor = sprintf('#%02X%02X%02X', rand(200, 255), rand(200, 255), rand(200, 255));
                                $textColor = '#000000';
                            @endphp

                            <div class="service-box-2 "
                                style="background-color: {{ $randomColor }};  color: {{ $textColor }};">
                                <div class="service-box-2_content">
                                    <div class="service-box-2_icon">
                                        @if ($links->image != '')
                                            <img src="{{ asset('admin/images/quck_link/' . $links->image) }}"
                                                alt="Icon">
                                        @else
                                            <img src="{{ asset('frontend/img/shield.png') }}" alt="Icon">
                                        @endif
                                    </div>
                                    <h3 class="box-title"> {{ $links->title }}</h3>
                                    <p class="quick-link-content" style="height: 70px;">{!! Str::words(strip_tags(ucfirst($links->content)), 15, '...') !!}</p>
                                    @if (Str::length($links->content) > 140 || $links->document)
                                        <a href="{{ route('frontend.quick.link', $links->id) }}">
                                            <button class="th-btn border px-4 mb-4">Read More </button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- End of Quick Links -->

    <!-- Start of Our Press Releases and Updates -->
    <section class="space-mob1 recent-causes-area custom-top-padding custom-top-paddings bg-gray space" id="blog-sec">
        <div class="container">
            <div class="title-area text-center">
                <h2 class="sec-title">Consumer Insights & Updates</h2>
            </div>
            <div class="heading-lefts">
                <div class="row">
                    @if (!empty($announcement) && count($announcement) > 0)
                        <div class="col-lg-4">
                            <div class="cart-tax news-column-solo" id="pressroom-col">
                                <div class="news-header-url">
                                    <h4 class="news-column-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-substack" viewBox="0 0 16 16">
                                            <path
                                                d="M13 2.5a1.5 1.5 0 0 1 3 0v11a1.5 1.5 0 0 1-3 0v-.214c-2.162-1.241-4.49-1.843-6.912-2.083l.405 2.712A1 1 0 0 1 5.51 15.1h-.548a1 1 0 0 1-.916-.599l-1.85-3.49-.202-.003A2.014 2.014 0 0 1 0 9V7a2.02 2.02 0 0 1 1.992-2.013 75 75 0 0 0 2.483-.075c3.043-.154 6.148-.849 8.525-2.199zm1 0v11a.5.5 0 0 0 1 0v-11a.5.5 0 0 0-1 0m-1 1.35c-2.344 1.205-5.209 1.842-8 2.033v4.233q.27.015.537.036c2.568.189 5.093.744 7.463 1.993zm-9 6.215v-4.13a95 95 0 0 1-1.992.052A1.02 1.02 0 0 0 1 7v2c0 .55.448 1.002 1.006 1.009A61 61 0 0 1 4 10.065m-.657.975 1.609 3.037.01.024h.548l-.002-.014-.443-2.966a68 68 0 0 0-1.722-.082z" />
                                        </svg> Consumer Corner

                                    </h4>
                                </div>
								
                                <div class="tax-wrapper">
                                    @foreach ($announcement as $values)
                                    @php
                                        $createdAt = date('d-m-Y', strtotime($values['created_at']));
                                        $today = date('d-m-Y');
                                        $date1 = DateTime::createFromFormat('d-m-Y', $createdAt);
                                        $date2 = DateTime::createFromFormat('d-m-Y', $today);
                                        $diff = $date1->diff($date2);
                                        $daysDifference = $diff->days;
                                    @endphp
                                        <div class="new_67">
                                           @if ($date1 < $date2 && $daysDifference < 7)
                                            <span>
                                                <img src="{{ asset('new.png') }}" style="width: 30px; height: 30px;">
                                            </span>
                                            @endif 
											
                                            <div class="icon-image">
                                                <h5>
                                                   <a href="javascript:void(0);"
                                                        class="openContentModal"
                                                        data-type="{{ $values['type'] }}"
                                                        data-link="{{ asset('admin/images/consumer_corner/'.$values['link']) }}"
                                                        data-title="{{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}">
                                                            <div class="Link_urls_point">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}
                                                            </div>
                                                        </a>



                                                </h5>
                                                <div class="recent-post-meta">
                                                    <a>
                                                        <i class="fa fa-calendar"></i>
                                                       {{ date('d-m-Y', strtotime($values['created_at'])) }}
                                                    </a>
                                                    <ul class="table-list"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="quickLink">
                                    @endforeach
                                    <div class="course-btn home-page-btn-view-all">
                                        <a class="th-btn border quick-press-btn view-all"
                                            href="{{ route('frontend.consumer_corner') }}">View
                                            All </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($press) && count($press) > 0)
                        <div class="col-lg-4">
                            <div class="cart-tax news-column-solo" id="pressroom-col">
                                <div class="news-header-url">
                                    <h4 class="news-column-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z" />
                                        </svg> Tips and Advice

                                    </h4>
                                </div>
                                <div class="tax-wrapper">
                                    @foreach ($press as $values)
                                         @php
                                        $createdAt = date('d-m-Y', strtotime($values['created_at']));
                                        $today = date('d-m-Y');
                                        $date1 = DateTime::createFromFormat('d-m-Y', $createdAt);
                                        $date2 = DateTime::createFromFormat('d-m-Y', $today);
                                        $diff = $date1->diff($date2);
                                        $daysDifference = $diff->days;
                                    @endphp
                                        <div class="new_67">
                                            <!-- @if ($date1 < $date2 && $daysDifference < 7)
                                                <img src="{{ asset('new.gif') }}" style="width: 40px; height: 40px;">
                                            </span>
                                            @endif -->
                                            <div>
                                                <h5>
                                                    <a href="javascript:void(0);"
                                                        class="openContentModal"
                                                        data-type="{{ $values['type'] }}"
                                                        data-link="{{ asset('admin/images/tips_advice/'.$values['link']) }}"
                                                        data-title="{{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}">
                                                            <div class="Link_urls_point">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}
                                                            </div>
                                                        </a>
                                                </h5>
                                                <div class="recent-post-meta">
                                                    <a>
                                                        <i class="fa fa-calendar"></i>
                                                         {{ date('d-m-Y', strtotime($values['created_at'])) }}
                                                    </a>
                                                    <ul class="table-list"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="quickLink">
                                    @endforeach
                                    <div class="course-btn home-page-btn-view-all">
                                        <a class="th-btn border quick-press-btn view-all"
                                            href="{{ route('frontend.tips-and-advice') }}">View All </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!empty($news) && count($news) > 0)
                        <div class="col-lg-4">
                            <div class="cart-tax news-column-solo" id="pressroom-col">
                                <div class="news-header-url">
                                    <h4 class="news-column-header">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-substack" viewBox="0 0 16 16">
                                            <path
                                                d="M15 3.604H1v1.891h14v-1.89ZM1 7.208V16l7-3.926L15 16V7.208zM15 0H1v1.89h14z" />
                                        </svg> Notices
                                    </h4>
                                </div>
                                <div class="tax-wrapper">
                                    @foreach ($news as $values)
                                         @php
                                        $createdAt = date('d-m-Y', strtotime($values['created_at']));
                                        $today = date('d-m-Y');
                                        $date1 = DateTime::createFromFormat('d-m-Y', $createdAt);
                                        $date2 = DateTime::createFromFormat('d-m-Y', $today);
                                        $diff = $date1->diff($date2);
                                        $daysDifference = $diff->days;
                                    @endphp
                                        <div class="new_67">
                                            <!-- @if ($date1 < $date2 && $daysDifference < 7)
                                                <img src="{{ asset('new.gif') }}" style="width: 40px; height: 40px;">
                                            </span>
                                            @endif -->
                                       <div>

                                                <h5>
                                                    <a href="javascript:void(0);"
                                                        class="openContentModal"
                                                        data-type="{{ $values['type'] }}"
                                                        data-link="{{ asset('admin/images/notices/'.$values['link']) }}"
                                                        data-title="{{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}">
                                                            <div class="Link_urls_point">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($values['title']), 90) }}
                                                            </div>
                                                        </a>
                                                </h5>
                                                <div class="recent-post-meta">
                                                    <a>
                                                        <i class="fa fa-calendar"></i>
                                                          {{ date('d-m-Y', strtotime($values['created_at'])) }}
                                                    </a>
                                                    <ul class="table-list"></ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="quickLink">
                                    @endforeach
                                    <div class="course-btn home-page-btn-view-all">
                                        <a class="th-btn border quick-press-btn view-all"
                                            href="{{ route('frontend.notice.page') }}">View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    <!-- End of Our Press, Releases and Blogs -->

    <!-- Start of Blog -->
    @if (!empty($blog) && count($blog) > 0)
        <section class="space-mob1 space overflow-hidden ">
            <div class="container">
                <div class="title-area text-center">
                    <h2 class="sec-title">Our Blog </h2>
                </div>
                <div class="row slider-shadow th-carousel" id="blogSlide1" data-slide-show="3" data-lg-slide-show="2"
                    data-md-slide-show="2" data-sm-slide-show="1" data-arrows="true">
                    @foreach ($blog as $values)
                        <div class="col-md-6 col-xl-4" >
                            <div class="blog-card style2 wow fadeInUp" style="height:400px !important;"> 
                                <div class="blog-img">
                                    <img src="{{ asset('admin/images/news/' . $values['image']) }}" alt="">
                                    <div class="blog-card_wrapper">
                                        <span
                                            class="blog-card_date">{{ date('d', strtotime($values['created_at'])) }}</span>
                                        <span
                                            class="blog-card_month">{{ date('M', strtotime($values['created_at'])) }}</span>
                                    </div>
                                </div>
                                <div class="blog-card-content">
                                    <h3 class="box-title">
                                        <a
                                            href="{{ route('frontend.blog-detail', ['slug' => $values['id']]) }}">{{ \Illuminate\Support\str::limit(strip_tags($values['title']), 60)."..." }}</a>
                                    </h3>
                                    <a href="{{ route('frontend.blog-detail', ['slug' => $values['id']]) }}"
                                        class="th-btn border">Read More</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <!-- End of Blog -->

    <!-- Start of file and track -->
    <div class="about-compnay-two section-spacing">
        <div class="overlay">
            <div class="container">
                <div class="row">
                    <div class="section-heading text-center mb-lg-4">
                        <h3 class="mb-1">File and Track Your Consumer Complaints with Ease</h3>
                        <p class="">We’re here to ensure your voice is heard and your rights are protected.</p>
                    </div>
                </div>
                <div class="row no-gutters">
                    <div class="col-lg-12 col-12 text">
                        <div class="service-box-2_wrapp">
                            <div class="service-box-2">
                                <div class="service-box-2_content text-center">
                                    <img src="{{ asset('frontend/img/submit.png') }}" alt="submit" width="50px"
                                        class="mb-2">
                                    <h4 class="box-title">Submit</h4>
                                    <p class="service-box-2_text mb-0">Fill out the complaint form with all necessary
                                        details.</p>
                                </div>
                            </div>
                            <div class="service-box-2">
                                <div class="service-box-2_content text-center">
                                    <img src="{{ asset('frontend/img/acknowledge.png') }}" alt="submit" width="50px"
                                        class="mb-2">
                                    <h4 class="box-title">Acknowledgment</h4>
                                    <p class="service-box-2_text mb-0">Receive a confirmation email with your complaint ID.
                                    </p>
                                </div>
                            </div>
                            <div class="service-box-2">
                                <div class="service-box-2_content text-center">
                                    <img src="{{ asset('frontend/img/research.png') }}" alt="submit" width="50px"
                                        class="mb-2">
                                    <h4 class="box-title">Investigation</h4>
                                    <p class="service-box-2_text mb-0">Your case will be reviewed by our team.</p>
                                </div>
                            </div>
                            <div class="service-box-2">
                                <div class="service-box-2_content text-center">
                                    <img src="{{ asset('frontend/img/resolute.png') }}" alt="submit" width="50px"
                                        class="mb-2">
                                    <h4 class="box-title">Resolution</h4>
                                    <p class="service-box-2_text mb-0">We will contact you with the outcome or further
                                        steps.</p>
                                </div>
                            </div>
                        </div>
                        <div class="news-column-solo text-center mt-4">
                            <a href="{{ route('frontend.complaint.form') }}">

                                <button class="th-btn border py-3 px-4">File a Complaint </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- Modal -->
<div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true" style="z-index: 1050;">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 600px;">
    <div class="modal-content rounded shadow" id="modal-content" style="position: relative; top: -25px;">

      <!-- Modal Header -->
      <!-- <div class="modal-header" style="background-color: #f8f9fa; position: relative;"> -->
        <!-- <p class="modal-title mb-0 pe-5" id="contentModalLabel" style="font-weight: bold;"></p> -->

        <!-- Close Button -->
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
          style="width: 30px; height: 30px; position: absolute; right: -5px; top: -5px; background: #bd1e2d; z-index: 9999; padding: 0; border-radius: 50%;">
          <i class="fa fa-close" style="position: relative; top: 1px;"></i>
        </button>
      <!-- </div> -->

      <!-- Modal Body -->
      <div class="modal-body p-0" style="height: calc(100% - 56px); overflow: hidden;">
        <div class="d-flex align-items-center justify-content-center h-100 w-100" id="modalContentWrapper">
          <!-- Dynamic content goes here -->
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Script -->
<script>
$(document).on('click', '.openContentModal', function () {
    const link = $(this).data('link');
    const title = $(this).data('title');
    const extension = link.split('.').pop().toLowerCase();

    // Set modal title
    $('#contentModalLabel').text(title);

    let contentHtml = '';

    // Reset height first
    $('#modal-content').css('height', '');

    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(extension)) {
        $('#modal-content').css('height', '660px');
        contentHtml = `<img src="${link}" class="img-fluid shadow" style="height: 100%; width: 100%;">`;

    } else if (['mp4', 'webm', 'ogg'].includes(extension)) {
        $('#modal-content').css('height', '338px');
        contentHtml = `
            <video controls class="shadow" style="max-height: 100%; max-width: 100%;">
                <source src="${link}" type="video/${extension}">
                Your browser does not support the video tag.
            </video>`;

    } else {
        $('#modal-content').css('height', '500px'); // default/fallback height
        contentHtml = `<iframe src="${link}" width="100%" height="100%" frameborder="0" class="rounded shadow"></iframe>`;
    }

    $('#modalContentWrapper').html(contentHtml);
    $('#contentModal').modal('show');
});

</script>


        <!-- End of file and track -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('myVideo');
                // Detect iOS
                const isIos = /iPhone|iPad|iPod/i.test(navigator.userAgent);
                // Detect if running as a standalone PWA (not in Safari browser)
                const isStandalone = window.navigator.standalone === true;

                // If iOS and in standalone mode (PWA)
                if (isIos && isStandalone && video) {
                    alert("inside the if")
                    // Wait for the first user touch, then play
                    document.addEventListener('touchstart', function() {
                        video.play().catch(err => {
                            console.warn('Autoplay failed on iOS:', err);
                        });
                    }, {
                        once: true
                    });
                } else {
                    // Fallback: Try autoplay immediately on non-iOS or browser mode
                    if (video) {
                        video.play().catch(err => {
                            console.warn('Autoplay failed:', err);
                        });
                    }
                }
            });
        </script>

    @endsection
