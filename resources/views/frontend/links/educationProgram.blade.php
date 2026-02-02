@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">Consumer Education For Kids Program</h2>
    </div>
  </div>
</div>

<section class="useful-consumer">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-xl-12">
        <div class="pe-xl-5">
          <div class="about_images mb-4 mb-lg-3">
          @if(@$education->image != '')
         <img class="" src="{{asset('admin/images/cms/'.@$education['image'])}}" alt="">
         @else
            <img class="" src="{{asset('frontend/img/consumer-edu.webp')}}" alt="">
            @endif
          </div>
          <div class="title-area mb-20">
            <span class="sub-title style1">Consumer Awareness & Education</span>
            <h2 class="sec-title">{{ $education['title'] }}</h2>
          </div>
          <p>{!! $education['content'] !!}</p>
        </div>
      </div>
    </div>
  </div>
</section> 
@endsection