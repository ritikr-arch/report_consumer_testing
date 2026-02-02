@extends('frontend.layout.app')
@section('title', @$title)
@section('content')

<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
    <div class="container">
        <div class="breadcumb-content">
            <h2 class="breadcumb-title1">Disclaimer</h2>
         </div>
    </div>
</div>


  <section class="tips-advice">
    <div class="container">
      <div class="row">
       
      @if(!empty($disclaimer))
      {!! $disclaimer->content !!}
      @endif

      </div>
    </div>
  </section>
@endsection