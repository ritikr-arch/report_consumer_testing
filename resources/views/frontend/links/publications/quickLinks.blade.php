@extends('frontend.layout.app')
@section('title', @$title)
@section('content')

<div class="breadcumb-wrapper background-image" style="background-image: url(' {{ asset('frontend/img/bread-crum.jpg') }} ') ">
    <div class="container">
        <div class="breadcumb-content">
            <h2 class="breadcumb-title1">

            @if(!empty($quickLink))
            {{ $quickLink->title }}
            @else
            Quick Links Details
            @endif
                    
            </h2>
         </div>
    </div>
</div>

  <section class="tips-advice">
    <div class="container">
         <div class="row">
            <div class="col-xl-12 mb-4">
              @if(!empty($quickLink['document']))
              <a href="{{ url('admin/docs/quick_link/'.$quickLink['document']) }}" target="_blank"><button type="button" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Download PDF</button></a>
              @endif
            </div>
         </div>
      <div class="row">
       
      @if(!empty($quickLink))
      {!! $quickLink->content !!}
      @endif

      </div>
   
    </div>
  </section>
@endsection