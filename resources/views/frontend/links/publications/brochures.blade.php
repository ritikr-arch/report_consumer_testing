@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ')">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Brochures</h2>
    </div>
  </div>
</div>
@if(!empty($brouchers) && count($brouchers)>0)
<section class="th-blog-wrapper space-top space-extra-bottom">
  <div class="container">
    <div class="row">
      @foreach($brouchers as $values)
      <div class="col-lg-6 col-xl-4">
        <div class="th-blog blog-single has-post-thumbnail">
          <div class="blog-img">
            <a href="{{ asset('admin/images/broacher/'.$values['image']) }}">
              <img src="{{ asset('admin/images/broacher/'.$values['image']) }}" alt="">
            </a>
          </div>
          <div class="blog-content">
            <h2 class="blog-title">
              <a href="#">{{ $values['title'] }}</a>
            </h2>
            <a href="{{ asset('admin/images/broacher/'.$values['document']) }}" class="th-btn border" download>Download &nbsp; <i class="fas fa-download"></i>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section> 
@endif 
@endsection