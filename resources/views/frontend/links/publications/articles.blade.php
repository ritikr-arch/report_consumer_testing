@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<style>
  h2.blog-title {
    height: 35px;
}
</style>
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Articles</h2>
    </div>
  </div>
</div>
@if(!empty($article) && count($article)>0)
<section class="th-blog-wrapper space-top space-extra-bottom">
  <div class="container th-container">
    <div class="row">
      @foreach($article as $values)
      <div class="col-lg-6 col-xl-4" >
        <div class="th-blog blog-single has-post-thumbnail" style="height:540px;">
          <div class="blog-img">
            <a href="{{ route('frontend.article-detail',['slug' => $values['id']]) }}">
              <img src="{{ asset('admin/images/news/'.$values['image']) }}" alt="">
            </a>
          </div>
          <div class="blog-content">
            <div class="blog-meta">
              <a href="#">
                <i class="fa-light fa-user"></i>By Admin </a>
              <a href="#">
                <i class="fa-regular fa-calendar"></i>{{  customt_date_format( $values['created_at']) }}</a>
            </div>
            <h2 class="blog-title">
              <a href="{{ route('frontend.article-detail',['slug' => $values['id']]) }}">{!!  \Illuminate\Support\str::limit(strip_tags($values['title']), 65) !!}</a>
            </h2>
            <p style="height: 70px;">{!! \Illuminate\Support\str::limit(strip_tags($values['description']), 131) !!}</p>
            <a href="{{ route('frontend.article-detail',['slug' => $values['id']]) }}" class="th-btn border">Read More</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section> 
@endif 
@endsection