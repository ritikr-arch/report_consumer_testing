@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<style>

 	canvas{

        max-width: 100%;

        height: 300px !important;

    }

</style> 

<div class="px-3">

   <!-- Start Content-->

   <div class="container-fluid">

      <div class="row mt-3">

         <div class="col-xl-12">

            <div class="card">

               <div class="card-body">

                  <div class="col-12 col-md-12 col-lg-12 report-tbll d-flex align-items-center">
                     <div class="col-4">
                        <form action="{{route('admin.report.list')}}" method="get" class="d-flex align-items-center me-2">
                            <select name="zone" class="form-control me-2">
                                @if(isset($zones) && count($zones) > 0)
                                    @foreach($zones as $zone)
                                        <option {{(request()->zone == $zone->id)?'selected':''}} value="{{$zone->id}}">{{ ucfirst($zone->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="submit" class="btn btn-success">Search</button>
                        </form>
                     </div>
                     <div class="col-8">
                        <h4>
                           <strong style="margin-left: 25%;" class="text-danger flex-grow-1 text-center">{{ strtoupper($zoneName) }}</strong>
                        </h4>
                     </div>
                  </div>

                  <div class="row align-items-center d-flex mb-3">

                     <div class="col-xl-6">

                        <h4 class="header-title mb-0 font-weight-bold">
                           Report
                        </h4>

                     </div>
                     

                     <div class="col-6 col-md-2 col-lg-2">

                        <label>From</label>

                        <input type="date" class="form-control">

                     </div>

                     <div class="col-6 col-md-2 col-lg-2">

                     <label>To</label>

                        <input type="date" class="form-control">

                     </div>

                     <div class="col-12 col-md-2 col-lg-2 report-export-btn">

                     <label>&nbsp;</label>

                        <button><img src="{{asset('admin/img/export.png')}}" alt="">Export Report As</button>

                     </div>

                  </div>

                  <canvas id="reportChart"></canvas>                 

               </div>

            </div>

         </div>

      </div>

      <!-- end row-->

      <div class="row">

         <div class="col-xl-12">

            <div class="card">

               <div class="card-body">

                  <div class="row align-items-center d-flex mb-3">

                     <div class="col-xl-8">

                        <h4 class="header-title mb-0 font-weight-bold">

                           Data

                        </h4>

                     </div> 

                     <div class="col-12 col-md-2 col-lg-2 report-tbll">

                        <p>Price Analysis</p>

                     </div>

                     <div class="col-6 col-md-2 col-lg-2 report-tbll">

                     <p>Availability</p>

                     </div>

                  </div>

                  {{-- <div class="col-12 col-md-12 col-lg-12 report-tbll d-flex align-items-center">
                     <div class="col-4">
                        <form action="{{route('admin.report.list')}}" method="get" class="d-flex align-items-center me-2">
                            <select name="zone" class="form-control me-2">
                                @if(isset($zones) && count($zones) > 0)
                                    @foreach($zones as $zone)
                                        <option {{(request()->zone == $zone->id)?'selected':''}} value="{{$zone->id}}">{{ ucfirst($zone->name) }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <button type="submit" class="btn btn-success">Search</button>
                        </form>
                     </div>
                     <div class="col-8">
                        <h4>
                           <strong style="margin-left: 25%;" class="text-danger flex-grow-1 text-center">{{ strtoupper($zoneName) }}</strong>
                        </h4>
                     </div>
                  </div> --}}



                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>

                           <tr class="border-b">
                              <th>Commodities</th>

                              <th>Brand</th>

                              <th>Unit</th>
                              @if(isset($markets) && count($markets)>0)
                                 @foreach($markets as $market)
                                    <th style="min-width: 150px;">{{(ucfirst($market->name))}}</th>
                                 @endforeach
                              @endif
                           </tr>

                        </thead>

                        <tbody>
                           @if(isset($categories) && count($categories)>0)
                           @foreach($categories as $category)
                              <tr>
                                  <td colspan="{{ 3 + count($markets) }}" style="background-color: #ddd; text-align: center; font-weight: bold;">
                                      {{ $category->name }}
                                  </td>
                              </tr>
                              @if(count($category->commodities)>0)
                                 @foreach($category->commodities as $commodity)
                                    <tr>
                                      <td>{{ $commodity->name }}</td>
                                      <td>{{ ($commodity->brand)?$commodity->brand->name:'' }}</td>
                                      <td>{{ ($commodity->uom)?$commodity->uom->name:'' }}</td>
                                       @foreach($markets as $market)
                                           @php
                                               $priceKey = $commodity->id . '-' . $market->id;
                                               $price = $prices[$priceKey][0]->amount ?? $priceKey;
                                           @endphp
                                           <td style="color: {{ is_numeric($price) ? ($price == collect($prices)->min('price') ? 'green' : 'red') : 'black' }}">
                                               {{ is_numeric($price) ? '$' . number_format($price, 2) : '-' }}
                                           </td>
                                       @endforeach             
                                   </tr>
                                 @endforeach
                              @endif
                           @endforeach
                           @endif
                           {{-- <tr>

                              <td style="color:#000!important; font-weight:500;">01</td>

                              <td style="color:#000!important; font-weight:500;">Chicken (Frozen)</td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Per lb<br/>Per lb<br/>Per lb<br/>Per lb </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>20%<br/>37%<br/>18%<br/>42% </td>

                           </tr>

                           <tr>

                              <td style="color:#000!important; font-weight:500;">02</td>

                              <td style="color:#000!important; font-weight:500;">Meals (Frozen)</td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Per lb<br/>Per lb<br/>Per lb<br/>Per lb </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>20%<br/>37%<br/>18%<br/>42% </td>

                           </tr>

                           <tr>

                              <td style="color:#000!important; font-weight:500;">02</td>

                              <td style="color:#000!important; font-weight:500;">Meats (Salted and in Brine)</td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum<br/>Lorem Ipsum </td>

                              <td>Per lb<br/>Per lb<br/>Per lb<br/>Per lb </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>$6.48<br/>$6.48<br/>$6.48<br/>$6.48 </td>

                              <td>20%<br/>37%<br/>18%<br/>42% </td>

                           </tr> --}}

                        </tbody>

                     </table>

                  </div>

               </div>

            </div>

         </div>

      </div>



   </div>

   <!-- container -->

</div>

</div>

</div>
<?php
$cat = [];
foreach ($categories as $key => $value) {
   array_push($cat, ucwords($value->name));
}
?>
@endsection

@push('scripts')

<script>
   var category = @json($cat);
   // var category = ['Milk', 'Butter', 'Biscuit', 'Carrot', 'Fish', 'Honey', 'Soap', 'Chicken', 'Bread'];
	const ctx = document.getElementById('reportChart').getContext('2d');

	console.log(ctx);

    const reportChart = new Chart(ctx, {

        type: 'bar',

        data: {

            labels: category,

            datasets: [

                { 

                    label: 'May', 

                    data: [50, 70, 60, 30, 40, 30, 20, 50, 45], 

                    backgroundColor: 'blue', 

                    

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                },

                { 

                    label: 'June', 

                    data: [30, 50, 90, 20, 70, 60, 10, 90, 30], 

                    backgroundColor: 'orange', 

                    // borderRadius: 50, 

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                },

                { 

                    label: 'July', 

                    data: [60, 50, 80, 40, 60, 50, 30, 70, 65], 

                    backgroundColor: 'green', 

                    

                    barPercentage: 0.5, 

                    categoryPercentage: 0.5 

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {

                    position: 'top',

                    align: 'start',

                    labels: {

                        usePointStyle: true,

                        pointStyle: 'circle',

                        font: {

                            size: 16 // Increase the font size for legend labels (May, June, July)

                        }

                    }

                }

            },

            scales: {

                x: {

                    ticks: {

                        font: {

                            size: 14 // Increase font size for X-axis labels (e.g., Milk, Butter)

                        }

                    }

                },

                y: {

                    beginAtZero: true,

                    max: 100,

                    ticks: {

                        font: {

                            size: 14 // Increase font size for Y-axis labels

                        }

                    }

                }

            }

        }

    });

</script>

@endpush