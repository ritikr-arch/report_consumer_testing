@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content') 
<style>
    ul li {
    list-style-type: unset !important;
}
.title-area img {
  float: right;
  max-width: 50%;
  margin-right: 15px;
  margin-bottom: 10px;
}

.img-box1{
  margin-bottom: 3px !important;
} 





</style>
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">About Us</h2>
    </div>
  </div>
</div>
<div class="about-sec overflow-hidden space shape-mockup-wrap" id="about-sec">
  <div class="container">
    <div class="row">
      <div class="col-xl-12 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
        <div class="img-box1">
          <div class="img1">
            <img src="{{asset('frontend/img/iStock-1070418964-1.jpg')}}" alt="About">
          </div>
          <div class="img2 global-img movingX">
          
          @if(!empty($about) )

          <img src="{{asset('/admin/images/cms/'. $about->image)}}" alt="About">

          @else
            <img src="{{asset('frontend/img/about2.jpg')}}" alt="About">
          @endif

          </div>
          <div class="th-experience jump">
            <h3 class="experience-year">
              <span class="counter-number">25</span>+
            </h3>
            <p class="experience-text">Years of Advocating for Consumer Rights</p>
          </div>
        </div>
      
     
        <div class="wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">

        @if(!empty($about) )
          <div class="title-area mb-25">
            <span class="sub-title style1">About Us</span>
            <h2 class="sec-title mb-20">{{$about->title}}</h2>
            {!! $about->content !!}
          </div>
         @else
          
         <p> No Data Found! </p>
          @endif

        </div>
      </div>
    </div>
  </div>
  <div class="shape-mockup" style="top: -15%; left: -3%;">
    <img src="{{asset('/frontend/img/shape/shape_1.png')}}" alt="shape">
  </div>
</div>

<!-- Start of Our mission, vision, aim -->
<section id="advertisers" class="advertisers-service-sec pt-5 pb-lg-5" style="background-color:#f7f7f7;">
  <div class="width-figma-design">
    <div class="container">
      <div class="row">
        <div class="text-center">
          <h2 class="">Our Mission, Vision &amp; Aim</h2>
        </div>
      </div>
      <div class="row mt-lg-3 mt-md-4 row-cols-1 row-cols-sm-1 row-cols-md-3 justify-content-center">
        <div class="col mb-lg-3">
          <div class="service-card">
            <div class="icon-wrapper">
              <img src="{{ asset('admin/images/cms/'.$mission['image']) }}" alt="icon-img">
            </div>
            <h3>{{ $mission['title'] }}</h3>
            <p>{!! $mission['content'] !!}</p>
          </div>
        </div>
        <div class="col mb-lg-3">
          <div class="service-card">
            <div class="icon-wrapper">
              <img src="{{ asset('admin/images/cms/'.$vision['image']) }}" alt="icon-img">
            </div>
            <h3>{{ $vision['title'] }}</h3>
            <p>{!! $vision['content'] !!}</p>
          </div>
        </div>
        <div class="col mb-lg-3">
          <div class="service-card">
            <div class="icon-wrapper">
              <img src="{{ asset('admin/images/cms/'.$aim['image']) }}" alt="icon-img">
            </div>
            <h3>{{ $aim['title'] }}</h3>
            <p>{!! $aim['content'] !!}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End of our mission, vision, aim -->

<!-- Start of FAQ -->
<!-- @if(!empty($faq) && count($faq)>0)
<div class="faq-sec overflow-hidden space">
  <div class="container">
    <div class="row">
      <div class="title-area text-center mb-3">
        <h2 class="sec-title">Frequently Asked Have Any Questions?</h2>
      </div>
    </div>
       @php
    $faqChunks = $faq->chunk(ceil(count($faq) / 2));
    @endphp
    <div class="row">
      <div class="col-lg-6">
        <div class="accordion-area accordion" id="faqAccordion1">
          @foreach($faqChunks[0] as $index => $values)
            <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
              <div class="accordion-header" id="collapse-item-1-{{ $values['id'] }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#collapse-1-{{ $values['id'] }}" 
                        aria-expanded="false" 
                        aria-controls="collapse-1-{{ $values['id'] }}">
                  {!! $values['title'] !!}
                </button>
              </div>
              <div id="collapse-1-{{ $values['id'] }}" 
                   class="accordion-collapse collapse" 
                   aria-labelledby="collapse-item-1-{{ $values['id'] }}" 
                   data-bs-parent="#faqAccordion1" 
                   style="">
                <div class="accordion-body">
                  <p class="faq-text">{!! $values['description'] !!}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="col-lg-6">
        <div class="accordion-area accordion" id="faqAccordion2">
          @foreach($faqChunks[1] as $index => $values)
            <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
              <div class="accordion-header" id="collapse-item-2-{{ $values['id'] }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#collapse-2-{{ $values['id'] }}" 
                        aria-expanded="false" 
                        aria-controls="collapse-2-{{ $values['id'] }}">
                  {!! $values['title'] !!}
                </button>
              </div>
              <div id="collapse-2-{{ $values['id'] }}" 
                   class="accordion-collapse collapse" 
                   aria-labelledby="collapse-item-2-{{ $values['id'] }}" 
                   data-bs-parent="#faqAccordion2" 
                   style="">
                <div class="accordion-body">
                  <p class="faq-text">{!! $values['description'] !!}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div> 
@endif -->
<!-- End of FAQ -->
@endsection