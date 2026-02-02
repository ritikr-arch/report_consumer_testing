@extends('frontend.layout.app') 
@section('title', @$title) 
@section('content')
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url('frontend/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Questions of the week</h2>
    </div>
  </div>
</div>
@if(!empty($faq) && count($faq)>0)
<div class="faq-sec overflow-hidden space">
  <div class="container">
    @php
    $faqChunks = $faq->chunk(ceil(count($faq) / 2));
    @endphp
    <div class="row">
      <div class="col-lg-6">
        <div class="accordion-area accordion" id="faqAccordion">
        @foreach($faqChunks[0] as $index => $values)
          <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
            <div class="accordion-header" id="collapse-item-{{ $values['id'] }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $values['id'] }}" aria-expanded="false" aria-controls="collapse-1">{!! $values['title'] !!}</button>
            </div>
            <div id="collapse-{{ $values['id'] }}" class="accordion-collapse collapse" aria-labelledby="collapse-item-{{ $values['id'] }}" data-bs-parent="#faqAccordion" style="">
              <div class="accordion-body">
                <p class="faq-text">{!! $values['description'] !!}</p>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-6">
        <div class="accordion-area accordion" id="faqAccordion">
        @foreach($faqChunks[1] as $index => $values)
          <div class="accordion-card {{ $index == 0 ? 'active' : '' }}">
            <div class="accordion-header" id="collapse-item-{{ $values['id'] }}">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $values['id'] }}" aria-expanded="false" aria-controls="collapse-1">{!! $values['title'] !!}</button>
            </div>
            <div id="collapse-{{ $values['id'] }}" class="accordion-collapse collapse" aria-labelledby="collapse-item-{{ $values['id'] }}" data-bs-parent="#faqAccordion" style="">
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
@endif 
@endsection