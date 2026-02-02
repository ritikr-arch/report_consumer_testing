@extends('frontend.layout.app')

@section('title', @$title)

@section('content')

<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
    <div class="container">
        <div class="breadcumb-content">
            <h2 class="breadcumb-title1">Tips and Advice for Consumers</h2>
         </div>
    </div>
</div>


 <!-- end -->

  <section class="tips-advice">
    <div class="container">
      <div class="row">
       
      @if(!empty($tipadice))
      {!! $tipadice->content !!}
      @endif

      </div>
    </div>
  </section>

@endsection