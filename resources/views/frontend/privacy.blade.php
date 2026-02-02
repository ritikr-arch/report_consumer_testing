@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title1">Privacy Policy</h2>
    </div>
  </div>
</div>

<section class="disclaimer">
  <div class="container">
    <div class="row">
      @if(!empty($privacy))
      {!! $privacy->content !!}
      @endif
    </div>
  </div>
</section>
@endsection