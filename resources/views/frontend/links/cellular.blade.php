@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">{{ $content['title'] }}</h2>
    </div>
  </div>
</div>
<section class="useful-consumer">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-xl-12">
        {!! $content['content'] !!}
      </div>
    </div>
  </div>
</section> 
@endsection