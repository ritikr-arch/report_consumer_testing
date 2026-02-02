@extends('frontend.layout.app') 
@section('Blog Detail', @$title) 
@section('content') 
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">{{ $blog['title'] }}</h2>
    </div>
  </div>
</div>
<!-- end -->

<section class="th-blog-wrapper blog-details space-top space-extra-bottom">
  <div class="container">
    <div class="row">
      <div class="col-xxl-8 col-lg-7">
        <div class="post-66 post type-post status-publish format-standard has-post-thumbnail hentry category-pool-cleaning tag-renovations tag-swimming-pool th-blog blog-single has-post-thumbnail">

          <div class="blog-img">
            <img fetchpriority="high" width="793" height="400" src="{{ asset('admin/images/news/'.$blog['image']) }}" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async">
          </div>
          <!-- End Post Thumbnail -->
          <div class="blog-content">
            <div class="blog-meta">
              <a href="#">
                <i class="fa-light fa-user"></i>By Admin </a>
              <a href="#">
                <i class="fa-regular fa-calendar"></i>{{ date('d-m-Y', strtotime($blog['created_at'])) }}</a>
            </div>
            <p>{!! $blog['description'] !!}</p>
          </div>
        </div>
      </div>
      @if(!empty($blogs) && count($blogs)>0)
      <div class="col-xxl-4 col-lg-5">
        <aside class="sidebar-area">
          <div class="widget ">
            <h3 class="widget_title">Recent Blogs</h3>
            <div class="recent-post-wrap">

               @foreach($blogs as $values)
               <div class="recent-post">
                  <div class="media-img">
                     <a href="{{ route('frontend.blog-detail',['slug' => $values['id']]) }}">
                        <img src="{{ asset('admin/images/news/'.$values['image']) }}" alt="">
                     </a>
                  </div>
                  <div class="media-body">
                     <h4 class="post-title">
                       <a class="text-inherit" href="{{ route('frontend.blog-detail',['slug' => $values['id']]) }}">{{ $values['title'] }}</a>
                     </h4>
                     <div class="recent-post-meta">
                       <a href="#">
                         <i class="far fa-calendar-days"></i>{{customt_date_format($values['created_at'])}}</a>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
          </div>
        </aside>
      </div>
      @endif
    </div>
  </div>
</section> 
@endsection