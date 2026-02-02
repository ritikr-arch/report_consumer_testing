@extends('frontend.layout.app')
@section('title', @$title)
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--==============================
Hero Area
==============================-->
<style>
  .carousel-caption {
    position: absolute;
    right: 15%;
    top: 9.25rem;
    left: 11%;
    padding-top: 1.25rem;
    padding-bottom: 1.25rem;
    color: #fff;
    text-align: left;
    width: 50%;
  }

  .carousel-caption h3,
  .carousel-caption p {
    color: #fff;
  }
  .cart-tax .tax-wrapper, .discount-code-wrapper .tax-wrapper {
    margin-top: 10px;
  }

  .new_67 {
    display: flex;
    align-items: center;
  }

  .new_67 img {
    height: 30px;
    margin-right: 5px;
  }

  .new_67 h5 {
    font-size: 14px;
    color: #212529;
    margin-bottom: 3px;
  }

  .recent-post-meta {
    display: flex;
    justify-content: space-between;
  }

  .recent-post-meta a {
    color: #958e8e !important;
    text-decoration: none;
    font-size: 12px;
  }

  .bg-white2 img {
    width: 30px;
  }

  .zone-child {
      width: 90%;
      display: flex;
      align-items: center;
      margin-top: 12px;
  }

  .box-key {
      display: flex;
      align-items: center;
      margin-right: 20px;
  }

  .color-box {
      width: 15px;
      height: 15px;
      margin-right: 5px;
      border-radius: 20px;
  }
  .loss-red {
      background-color: red !important;
      color: #fff;
  }

  .profit-green {
      background-color: green !important;
      color: #fff;
  }

      .store-price {
        display: flex;
        justify-content: space-between;
        padding: 7px 0;
        border-bottom: 1px solid #e9ecef;
      }
    .store-price2 {
        padding: 7px 0;
        border-bottom: 1px solid #e9ecef;
      }
    .card2 {
      box-shadow: 0px 10px 50px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(0, 0, 0, .125);
      border-radius: 8px;
  }

      .price-green {
        background-color: #28a745;
        color: white;
        padding: 2px 8px;
        border-radius: 5px;
        text-align: center;
      }

      .price-red {
        background-color: #dc3545;
        color: white;
        padding: 2px 8px;
        border-radius: 5px;
         text-align: center;
      }
    .search-store2 {
      background-color: var(--theme-color);
      min-width: auto;
      padding: 13px 18px;
      margin-top: 0px;
      position: relative;
      z-index: 2;
      overflow: hidden;
      vertical-align: middle;
      display: inline-block;
      text-transform: uppercase;
      text-align: center;
      border: none;
      color: var(--white-color);
      font-family: var(--body-font);
      font-size: 14px;
      font-weight: 600;
      line-height: 1;
      border-radius: 30px;
      -webkit-transition: all 0.4s ease-in-out;
      transition: all 0.4s ease-in-out;
  }
</style>
@dd($survey->categories)
<?php 
use App\Models\SubmittedSurvey;
?>
<!-- Carousel -->
<div class="container">
  <div class="title-area mb-0 pb-0 mt-4">
    <h5 class="sec-title mb-3 mgtt-20 font-30">Consumer Affairs Department ST. Kitts &amp; Nevis</h5>
    <h4 class="sec-title mb-0  text-success">A Look at Medication </h4>
  </div>
  <div class="row mt-4">
    <div class="col-md-3 col-6 mb-3">
      <label for=""> Type </label>
      <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
      <select name="type[]" id="type">
        <!-- <option value=""> Select Type</option> -->
        <option value="1">Food Basket </option>
        <option value="3">Furniture and Appliances </option>
        <option value="2">Hardware and Building Materials </option>
        <option value="4" selected="">Medication </option>
      </select>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <label for=""> Category </label>
      <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
      <select name="type[]" id="type">
        <!-- <option value=""> Select Type</option> -->
        <option value="1">Analgesics </option>
        <option value="3">Antidiabetic Agents </option>
        <option value="2">Cholesterol Lowering Drug </option>
        <option value="4" selected="">Hypotensive Agents </option>
      </select>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <label for=""> Zone </label>
      <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
      <select name="type[]" id="type">
        <!-- <option value=""> Select Type</option> -->
        <option value="1">Basseterre </option>
        <option value="3">Basseterre East </option>
        <option value="2">Basseterre West </option>
        <option value="4" selected="">Medication </option>
      </select>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <label for=""> Store </label>
      <!--  <select name="type[]" id="type" multiple multiselect-search="true" multiselect-select-all="true"> -->
      <select name="type[]" id="type">
        <!-- <option value=""> Select Type</option> -->
        <option value="1">ESSENTIAL PLUS </option>
        <option value="3">MERIDIAN MEDICAL </option>
        <option value="2">PHARMCARRE </option>
        <option value="4" selected="">VALU MART </option>
      </select>
    </div>
    <div class="col-md-3 col-12 mb-3">
      <label for=""> Date </label>
      <input type="date" class="form-control">
    </div>
    <div class="col-md-3 col-12 mb-3">
      <label for=""> &nbsp; </label>
      <button type="button" class="search-store2 w-100">
        <i class="far fa-search"></i> Search </button>
    </div>
  </div>
  <div class="card2 mt-4">
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 col-12">
          <div class="zone-child">
            <div class="box-key">
              <div class="color-box loss-red"></div>
              <span>Highest Price</span>
            </div>
            <div class="box-key">
              <div class="color-box profit-green"></div>
              <span>Lowest Price</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    @if(isset($survey->categories) && count($survey->categories)>0)
        @foreach($survey->categories as $key=>$category) 
          <?php 
            $surveyId = $category->survey_id;
            $submittedSurvey = SubmittedSurvey::with(['commodity', 'market', 'zone', 'brand', 'unit'])->where(['survey_id'=>$surveyId])->get();
          ?>
            <h5 class="text-center mt-4">{{ ucfirst($category->surveyCategoriesss->name)}}</h5>
            <div class="card2">
                <div class="py-3">
                    <div class="row">
                        <div class="col-md-12 col-12">

                                <div class="store-price px-3">
                                    <span>Commodities</span>
                                    <span></span>
                                </div>
                                <div class="store-price px-3">
                                  <span>Brand</span>
                                  <span>Distinction</span>
                                </div>
                                <div class="store-price px-3">
                                  <span>Unit</span>
                                  <span>800 gm</span>
                                </div>
                                <div class="store-price2 px-3">
                                  <h6 class="text-center mb-0 w-100">B'S enterprice</h6>
                                </div>
                                <div class="store-price2 px-3">
                                  <div class="price-red">$124.00</div>
                                </div>
                                <div class="store-price2 px-3">
                                  <h6 class="text-center mb-0">Dollor Stretcher</h6>
                                </div>
                                <div class="store-price2 px-3">
                                  <div class="price-green w-100">$1.00</div>
                                </div>
                                <div class="store-price2 px-3">
                                  <h6 class="text-center mb-0">Ring Food</h6>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
  @endif
  
   
</div>
<!--==============================
            Footer Area
==============================-->
<script>
  const ctx = document.getElementById('priceChart').getContext('2d');
  const priceChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Milk', 'Butter', 'Biscuit', 'Carrot', 'Fish', 'Honey', 'Soap', 'Chicken', 'Bread'],
      datasets: [{
        label: 'May',
        data: [120, 50, 200, 90, 100, 150, 80, 200, 100],
        backgroundColor: 'rgba(54, 162, 235, 0.9)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }, {
        label: 'June',
        data: [100, 80, 300, 70, 200, 250, 120, 250, 180],
        backgroundColor: 'rgba(232, 106, 28, 0.97)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 1
      }, {
        label: 'July',
        data: [200, 100, 250, 120, 150, 200, 90, 300, 200],
        backgroundColor: 'rgba(6, 159, 49, 0.9)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top'
        },
        tooltip: {
          mode: 'index',
          intersect: false
        }
      },
      scales: {
        x: {
          beginAtZero: true
        },
        y: {
          beginAtZero: true
        }
      }
    }
  });
  </script>
@endsection
