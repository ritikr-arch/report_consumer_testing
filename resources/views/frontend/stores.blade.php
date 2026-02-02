@extends('frontend.layout.app')
@section('title', @$title)
@section('content')


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
  .card.survey-card {
    box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
    border-radius: 12px;
    overflow: hidden;
  }

  .th-btn.border.view-btn {
    min-width: 66px;
    padding: 10px 13px !important;
    width: 80px;
    font-size: 13px;
  }

  .bg-green {
    background-color: #055005;
    color: #fff;
  }

  .bg-green .card-title {
    color: #fff;
  }

  .card.survey-card strong {
    font-weight: bolder;
    color: #363736;
    width: 126px;
    display: inline-block;
    font-weight: 500;
  }

  .card.survey-card p {
    font-size: 14px;
  }

  .card.shadow th {
    color: #fff;
  }

  thead.table-dark th {
    background-color: #055005 !important;
  }

  .stores-wrap select {
    height: 46px;
  }

  h6.card-title {
    font-weight: 500;
  }

  .date {
    background-color: #e3f2fd !important;
    border: 1px dashed #8dcbfd;
    padding: 2px 4px;
    border-radius: 4px;
  }
</style>
<!-- breadcrumb -->
<div class="breadcumb-wrapper background-image" style="background-image: url('assets/img/bread-crum.jpg');">
  <div class="container">
    <div class="breadcumb-content">
      <h2 class="breadcumb-title">Stores</h2>
    </div>
  </div>
</div>
<!--  <section class="stores-wrap"><div class="container"><div class="row"><div class="title-area text-center mb-3"><h2 class="sec-title">Supermarkets</h2></div><div class="card-container"><a href="{{route('frontend.products')}}"><div class="card crd1"><h3>Basseterre</h3></div></a><a href="{{route('frontend.products')}}"><div class="card crd2"><h3>Rural East</h3></div></a><a href="{{route('frontend.products')}}"><div class="card crd3"><h3>Rural West</h3></div></a></div></div></div></section>  -->
<section class="stores-wrap pb-0">
  <div class="container">
  <h2 class="text-center">Survey Information</h2>
    <div class="row">
      <div class="col-md-8"></div>
      <div class="col-md-2">
        <label for="sel1" class="form-label">Select Market</label>
        <select class="form-select form-select-sm" id="sel1" name="sellist1">
          <option>All</option>
          <option>Food Basket</option>
          <option>Hardware and Building Materials</option>
          <option>Furniture and Appliances</option>
          <option>Medication</option>
        </select>
      </div>
      <div class="col-md-2">
        <label for="sel1" class="form-label">Select Survey</label>
        <select class="form-select form-select-sm" id="sel1" name="sellist1">
          <option>All</option>

          <option>Basseterre</option>
          <option>Rural East</option>
          <option>Rural East</option>

        
        </select>
      </div>
    </div>
  </div>
</section>
<div class="container mt-4">

  <div class="row mt-4">
    <!-- Card 1 -->
    <div class="col-md-12 mx-auto mb-4">
      <div class="card survey-card">
        <!--      <div class="card-header d-flex align-items-center justify-content-between bg-green"><h6 class="card-title mb-2">Basseterre</h6><span><i class="fa-regular fa-eye"></i></span></div> -->
        <div class="card-body">


        @if(!empty($data) && count($data)>0)
            @foreach($data  as $value)
              <div class="bg-light p-3">
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="card-title mb-2">
                    <i class="fa-solid fa-location-dot"></i> {{$value->zone->name ?? 'NA'}}
                  </h6>
                  <a href="{{route('frontend.products')}}">
                    <i class="fa-regular fa-eye"></i> View </a>
                </div>

                <p class="mb-1">
                  <strong>Duration:</strong>
                  <span class="date">
                    <i class="fa-regular fa-calendar-days"></i>  {{ date('d M, Y ',strtotime($value->start_date)) }} - <i class="fa-regular fa-calendar-days"> </i>  {{ date('d M, Y ',strtotime($value->start_date)) }} </span>
                </p>
                <p class="mb-1">
                  <strong>Survey Name:</strong>{{$value->name}}
                </p>
              
              </div>
              <hr style="border-top: 1px dotted #666;background-color: transparent;">

              @endforeach

              @if (isset($data)) 
              {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }}
               @endif

            @endif


        </div>
      </div>
      <!-- Card 3 -->
    </div>
  </div>

</div>  

@endsection