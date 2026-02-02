@extends('frontend.layout.app') @section('title', @$title) @section('content') <div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">Consumer Protection Bill</h2>
    </div>
  </div>
</div>
<!-- end -->
<section class="useful-consumer">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-xl-12">
        <div class="pe-xl-5">
          <div class="about_images mb-4 mb-lg-3">
          @if(@$protection->image != '')
         <img class="" src="{{asset('admin/images/cms/'.@$protection['image'])}}" alt="">
         @else
            <img class="" src="{{asset('frontend/img/consumer-protection.jpg')}}" alt="">
            @endif
          </div>
          <div class="title-area mb-20">
            <span class="sub-title style1">Consumer Rights & Protection</span>
            <h2 class="sec-title">{{ $protection['title'] }}</h2>
          </div>
          <p>{!! $protection['content'] !!}</p>
        </div>
      </div>
    </div>
  </div>
</section> 
@endsection