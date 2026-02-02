@extends('frontend.layout.app') 
@section('News Detail', @$title) 
@section('content') 
<style>

  .blog-details .blog-img img{
    object-fit: contain;
    height: 480px !important;
  }

  .blog-single .blog-audio, .blog-single .blog-img, .blog-single .blog-video {
    position: relative;
    overflow: hidden;
    background-color: #f1f1f1;
}

a.text-inherit {
    font-size: 16px;
}
</style>
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">{{ $newsdetails['title'] }}</h2>
    </div>
  </div>
</div>
<!-- end -->

<section class="th-blog-wrapper blog-details space-top space-extra-bottom">
  <div class="container">
    <div class="row">
      <div class="col-xxl-7 col-lg-7">
        <div class="post-66 post type-post status-publish format-standard has-post-thumbnail hentry category-pool-cleaning tag-renovations tag-swimming-pool th-blog blog-single has-post-thumbnail">

          <div class="blog-img">
            <img fetchpriority="high" width="793" height="400" src="{{ asset('admin/images/notices/'.$newsdetails['link']) }}" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async">
          </div>
          <!-- End Post Thumbnail -->
          <div class="blog-content">
            <div class="blog-meta">
              <a href="#">
                <i class="fa-light fa-user"></i>By Admin </a>
              <a href="#">
                <i class="fa-regular fa-calendar"></i>{{ date('d-m-Y', strtotime($newsdetails['created_at'])) }}</a>
            </div>
            <p>{!! $newsdetails['content'] !!}</p>
          </div>
        </div>
      </div>
      
      @if(!empty($news) && count($news)>0)
      <div class="col-xxl-5 col-lg-5">
        <aside class="sidebar-area ps-lg-4">
          <div class="widget ps-lg-4">
            <h3 class="widget_title">Recent Notices</h3>
            <div class="recent-post-wrap">

               @foreach($news as $values)
               <div class="recent-post">
                  <div class="media-img">
                     <a href="{{ route('frontend.notice.deatils',['slug' => $values['id']]) }}">
                        <img src="{{ asset('admin/images/notices/'.$values['link']) }}" alt="">
                     </a>
                  </div>
                  <div class="media-body">
                     <h4 class="post-title">
                       <a class="text-inherit" href="{{ route('frontend.notice.deatils',['slug' => $values['id']]) }}">{{ $values['title'] }}</a>
                     </h4>
                     <div class="recent-post-meta">
                       <a href="#">
                         <i class="far fa-calendar-days"></i>{{  customt_date_format( $values['created_at']) }}</a>
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