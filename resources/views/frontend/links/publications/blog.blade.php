@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Blogs</h2>
    </div>
  </div>
</div>
@if(!empty($blog) && count($blog)>0)
<section class="th-blog-wrapper space-top space-extra-bottom">
  <div class="container th-container">
    <div class="row">
      @foreach($blog as $values)
      <div class="col-lg-6 col-xl-4">
        <div class="th-blog blog-single has-post-thumbnail">
          <div class="blog-img">
            <a href="{{ route('frontend.blog-detail',['slug' => $values['id']]) }}">
              <img src="{{ asset('admin/images/news/'.$values['image']) }}" alt="">
            </a>
          </div>
          <div class="blog-content press">
            <div class="blog-meta">
              <a href="#">
                <i class="fa-light fa-user"></i>By Jonson </a>
              <a href="#">
                <i class="fa-regular fa-calendar"></i>{{ date('d M, Y',strtotime($values['created_at'])) }}</a>
            </div>
            <h2 class="blog-title">
              <a href="{{ route('frontend.blog-detail',['slug' => $values['id']]) }}">{{  \Illuminate\Support\str::limit(strip_tags($values['title']), 31) }}</a>
            </h2>
            <p>{{ \Illuminate\Support\str::limit(strip_tags($values['description']), 131) }}</p>
            <a href="{{ route('frontend.blog-detail',['slug' => $values['id']]) }}" class="th-btn border press">Read More</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section> 
@endif 
@endsection