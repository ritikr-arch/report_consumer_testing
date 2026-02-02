@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<div class="px-3">

    <!-- Start Content-->

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-xl-5">
                                <h4 class="header-title mb-0 font-weight-bold">
                                    Submitted Survey Report
                                </h4>
                            </div>
                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">

                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>

                                    <a class="btn btn-secondary btn-sm" href="{{route('admin.submitted.details.export',array_merge(['id' => $id], request()->query()))}}" title=""><i class="fas fa-file-download"></i> Export</a>

                                </div>
                            </div>
                            
                        </div>

                        {{-- <div class="col-12 col-md-12 col-lg-12">
                            <h5>
                                <span>
                                    <strong>{{(isset($data[0]) && $data['0']->survey)?ucfirst($data['0']->survey->name):''}}</strong>
                                </span> - 
                                <span>
                                    <strong>{{(isset($data[0]) && $data['0']->zone)?ucfirst($data['0']->zone->name):''}}</strong>
                                </span>
                            </h5>
                        </div> --}}

                        <div class="row align-items-center d-flex mb-3">                   
                           <div class="col-12 col-md-6 col-lg-6 report-tbll text-end">
                              <p><strong>{{(isset($data[0]) && $data['0']->survey)?ucfirst($data['0']->survey->name):'No Name'}}</strong></p>
                           </div>
                           <div class="col-6 col-md-6 col-lg-6 report-tbll">
                            <p><strong>{{(isset($data[0]) && $data['0']->zone)?ucfirst($data['0']->zone->name):'No Name'}}</strong></p>
                           </div>
                        </div>


                        <form action="{{route('admin.submitted.survey.details.filter')}}" method="get">
                            <hr>
                            <div id="dropdown" class="dropdown-container-filter fil-comm">
                                <input type="hidden" name="sid" value="{{$id}}">
                                <div class="name-input">
                                    <input type="text" class="form-control" name="amount" id="amount"
                                        placeholder="Amount" value="{{ request('amount') }}">
                                </div>
                                <!-- <select class="form-select" name="assignee" aria-label="Default select example">
                                    <option value="" selected="">Assignee</option>
                                    @if(isset($assignies) && count($assignies)>0)
                                    @foreach($assignies as $aKey=>$asnValue)

                                    <option {{ request('assignee') == $asnValue->id ? 'selected' : '' }}
                                        value="{{$asnValue->id}}">{{ucfirst($asnValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select> -->

                                <select class="form-select" name="commodity" id="commodity"
                                    aria-label="Default select example">
                                    <option value="" selected="">Commodity</option>
                                    @if(isset($commodities) && count($commodities)>0)

                                    @foreach($commodities as $comKey=>$commodityValue)
                                    <option {{ request('commodity') == $commodityValue->id ? 'selected' : '' }}
                                        value="{{$commodityValue->id}}">{{ucfirst($commodityValue->name)}} ({{($commodityValue->uom)?$commodityValue->uom->name:'' }})</option>

                                    @endforeach

                                    @endif

                                </select>

                               <!--  <select class="form-select" name="availability" aria-label="Default select example">

                                    <option value="" selected="">Availability</option>

                                    <option value="high" {{ request('availability') == 'high' ? 'selected' : '' }}>High
                                    </option>

                                    <option value="low" {{ request('availability') == 'low' ? 'selected' : '' }}>Low
                                    </option>

                                    <option value="moderate"
                                        {{ request('availability') == 'moderate' ? 'selected' : '' }}>Moderate</option>

                                </select> -->

                                <select class="form-select" id="market" name="market"
                                    aria-label="Default select example">

                                    <option value="" selected="">Store</option>

                                    @if(isset($markets) && count($markets)>0)

                                    @foreach($markets as $mKey=>$mktValue)

                                    <option {{ request('market') == $mktValue->id ? 'selected' : '' }}
                                        value="{{$mktValue->id}}">{{ucfirst($mktValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select>

                                <select class="form-select" id="category" name="category"
                                    aria-label="Default select example">

                                    <option value="" selected="">Category</option>

                                    @if(isset($categories) && count($categories)>0)

                                    @foreach($categories as $catKey=>$catValue)

                                    <option {{ request('category') == $catValue->id ? 'selected' : '' }}
                                        value="{{$catValue->id}}">{{ucfirst($catValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select>

                                <select class="form-select" name="unit" aria-label="Default select example">

                                    <option value="" selected="">Unit</option>

                                    @if(isset($units) && count($units)>0)

                                    @foreach($units as $unitKey=>$unitValue)

                                    <option {{ request('unit') == $unitValue->id ? 'selected' : '' }}
                                        value="{{$unitValue->id}}">{{ucfirst($unitValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select>

                                <select class="form-select" name="brand" aria-label="Default select example">

                                    <option value="" selected="">Brand</option>

                                    @if(isset($brands) && count($brands)>0)

                                    @foreach($brands as $catKey=>$brandValue)

                                    <option {{ request('brand') == $brandValue->id ? 'selected' : '' }}
                                        value="{{$brandValue->id}}">{{ucfirst($brandValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select>

                                <select class="form-select" name="collected_buy" aria-label="Default select example">

                                    <option value="" selected="">Collected By</option>

                                    @if(isset($assignies) && count($assignies)>0)

                                    @foreach($assignies as $ctrKey=>$coltrrValue)

                                    <option {{ request('collected_buy') == $coltrrValue->id ? 'selected' : '' }}
                                        value="{{$coltrrValue->id}}">{{ucfirst($coltrrValue->name)}}</option>

                                    @endforeach

                                    @endif

                                </select>

                                <div class="filter-date">
                                    <input  type="text" value="{{ request('collected_date') }}"  name="collected_date"
                                        class="form-control"  placeholder="Collected Date" id="collected_date" autocomplete="off">
                                </div>

                                <div class="filter-date">
                                    <input type="text" value="{{ request('start_date') }}" name="start_date"
                                        class="form-control"  placeholder="Start Date" id="start_date" autocomplete="off">

                                </div>

                                <div class="filter-date">
                                    <input  type="text" value="{{ request('end_date') }}"  name="end_date"
                                        class="form-control"  placeholder="End Date" id="end_date" autocomplete="off">

                                </div>

                                <button type="submit" class="d-flex searc-btn">Search</button>

                                <a type="button" class="btn btn-secondary btn-sm" href="{{route('admin.submitted.survey.details', $id)}}">Reset</a>

                            </div>

                        </form>
<br>
                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th style="min-width: 10px;" scope="row">S.No.</th>

                                        {{-- <th style="min-width: 180px;">Survey Title</th> --}}

                                        <th style="min-width: 210px;">Commodity</th>

                                        {{-- <th style="min-width: 100px;">Image</th>

                                        <th style="min-width: 150px;">Zone</th>

                                        <th style="min-width: 150px;">Survey assignee</th> --}}

                                        <th style="min-width: 130px;">Store</th>

                                        <th style="min-width: 150px;">Category</th>

                                        {{-- <th style="min-width: 150px;">Unit</th>

                                        <th style="min-width: 150px;">Brand</th> --}}

                                        <th style="min-width: 80px;">Amount 
                                            <span style="font-size: 11px;">(Generic)</span>
                                        </th>

                                        <th style="min-width: 80px;">Amount
                                            <span style="font-size: 11px;">(Orignal)</span>
                                        </th>

                                        {{-- <th style="min-width: 100px;">Availability</th> --}}

                                        <th style="min-width: 140px;">Collected Date</th>

                                        <th style="min-width: 140px;">Collected By</th>

                                        {{-- <th style="min-width: 150px;">Updated By</th> --}}

                                        {{-- <th style="min-width: 100px;"><input type="checkbox" name="" id="selectedAll" class="" value="1"> Status</th> --}}

                                        {{-- <th style="min-width: 100px;">Publish</th>

                                        <th style="min-width: 50px;">Action</th> --}}

                                    </tr>

                                </thead>

                                <tbody>

                                    @if(isset($data) && count($data)>0)

                                    @foreach($data as $key=>$value)

                                    <tr>

                                        <td>{{$key+1}}</td>

                                        {{-- <td>{{($value->survey)?ucfirst($value->survey->name):''}}</td> --}}

                                        <td>
                                            <?php 
                                                $imageURL = '';
                                                if($value->commodity_image){
                                                    $imageURL = 'submittedSurveyImage/'.$value->commodity_image;
                                                }else{
                                                    $imageURL = 'admin/images/commodity.jpeg';
                                                }
                                            ?>
                                            <img data-bs-toggle="modal" data-bs-target="#myModal" class="modal-image" src="{{ asset($imageURL) }}" alt="commodity image" style="border-radius: 50%;" height="30" width="30">
                                            {{($value->commodity)?ucfirst($value->commodity->name):''}}
                                        </td>
                                        
                                        {{-- <td>{{($value->zone)?ucfirst($value->zone->name):''}}</td>

                                        <td>{{($value->user)?ucfirst($value->user->name):''}}</td> --}}

                                        <td>{{($value->market)?ucfirst($value->market->name):''}}</td>

                                        <td>
                                            {{($value->category)?ucfirst($value->category->name):''}}<br>
                                            {{($value->unit)?ucfirst($value->unit->name):''}} {{($value->brand)?'-':''}} 
                                            {{($value->brand)?ucfirst($value->brand->name):''}}
                                        </td>

                                        {{-- <td>{{($value->unit)?ucfirst($value->unit->name):''}}</td>

                                        <td>{{($value->brand)?ucfirst($value->brand->name):''}}</td> --}}

                                        <td class="admin_amnt">{{($value->amount)?'$'.$value->amount:'-'}}</td>
                                        <td class="admin_amnt">{{($value->amount_1)?'$'.$value->amount_1:'-'}}</td>

                                        {{-- <td>{{$value->availability}}</td> --}}

                                        <td> {{  customt_date_format( $value->created_at) }}</td>

                                        <td>{{($value->submitter)?$value->submitter->name:''}}</td>

                                        {{-- <td>{{($value->updater)?$value->updater->name:'N/A'}}</td>

                                        <td class="active-bt">
                                            <a href="javascript:void(0)" class="($value->status == '1')?'statuss':''"
                                                data-id="{{$value->id}}" title="Status">
                                                @if($value->status != '1')
                                                    <input type="checkbox" name="selected[]" value="{{$value->id}}">
                                                @endif
                                                {{($value->status == '1')?'Approved':'Pending'}}
                                            </a>
                                        </td> --}}

                                        {{-- <td class="active-bt">
                                            <a href="javascript:void(0)" data-id="{{$value->id}}"
                                                class="{{ ($value->publish=='0')?'publish':''}}"
                                                title="Publish">{{($value->publish == '1')?'Yes':'No'}}</a>
                                        </td> --}}

                                        {{-- <td>
                                            @if($value->status == '0')
                                            <div class="action-btn">
                                                <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"
                                                    href="{{route('admin.edit.submitted.survey', $value->id)}}">
                                                    <img src="{{asset('admin/img/edit-2.png')}}" alt="edit">
                                                </a>
                                            </div>
                                            @endif
                                        </td> --}}

                                    </tr>
                                    @endforeach

                                    @endif

                                </tbody>

                            </table>
                            
                            @if (isset($data) && count($data)>0)
                                {{-- <div class="text-end">
                                    <button class="btn btn-primary" type="button" id="selectButton">Approve</button>
                                </div> --}}
                                {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }}
                            @endif
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- end row-->

    </div>

    <!-- container -->

</div>

</div>

</div>


<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <img class="modal-content" id="img01">
        <div id="caption"></div>
      </div>

   
    </div>
  </div>

@endsection



@push('scripts')

<script>

var modal = document.getElementById("myModal");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

// Select all images with class "modal-image"
var images = document.getElementsByClassName("modal-image");

for (let i = 0; i < images.length; i++) {
  images[i].onclick = function() {
    modal.style.display = "block";
    modalImg.src = this.src;
    captionText.innerHTML = this.alt;
  }
}

// Close the modal when clicking on the close button
// var span = document.getElementsByClassName("close")[0];
// span.onclick = function() { 
//   modal.style.display = "none";
// }

$(document).ready(function() {

    $("#selectedAll").on('click', function() {
        let isChecked = $(this).prop('checked');
        $('input[name="selected[]"]').prop('checked', isChecked);
    });

    $("#category").on('change', function() {

        var id = $(this).val();

        var url = "{{ route('admin.get.category.commodity', ':id') }}";

        url = url.replace(':id', id);

        if (id) {

            $.ajax({

                url: url,

                type: "GET",

                success: function(response) {

                    if (response.success) {

                        let data = response.data;

                        let dropdown = $('#commodity');

                        dropdown.empty();

                        dropdown.append('<option value="">Select Commodity</option>');

                        response.data.forEach(function(item) {

                            dropdown.append(
                                `<option value="${item.id}">${item.name}</option>`
                            );

                        });

                    }

                },

                error: function(xhr) {

                    let errors = xhr.responseJSON.errors;

                }

            });

        } else {

            let dropdown = $('#commodity');

            dropdown.append('<option value="">Select Commodity</option>');

        }

    })



    function setupDropdown(dropdownButtonId) {

        const $dropdownButton = $('#' + dropdownButtonId);

        const $dropdownMenu = $dropdownButton.next();

        const $dropdownItems = $dropdownMenu.find('.dropdown-item');

        // Toggle dropdown visibility

        $dropdownButton.on('click', function() {
            $dropdownMenu.toggle();

        });
        // Update dropdown button text on item click

        $dropdownItems.on('click', function() {
            const selectedValue = $(this).data('value');
            $dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');
            $dropdownMenu.hide();

        });
        // Close dropdown when clicking outside

        $(document).on('click', function(e) {

            if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e
                    .target).length === 0) {

                $dropdownMenu.hide();

            }

        });

    }



    // Initialize dropdowns

    setupDropdown('dropdownButton1');

    setupDropdown('dropdownButton2');

    setupDropdown('dropdownButton3');

    setupDropdown('dropdownButton4');



    // Multi-select dropdown setup

    const $dropdownButton = $('#dropdownButton');

    const $dropdownMenu = $('#dropdownMenu');

    const $checkboxes = $dropdownMenu.find("input[type='checkbox']");



    // Toggle dropdown visibility

    $dropdownButton.on('click', function() {

        $dropdownMenu.toggle();

    });

});



function toggleDropdown() {

    var dropdown = document.getElementById("dropdown");

    dropdown.classList.toggle("active");

}



window.onload = function() {

    let params = new URLSearchParams(window.location.search);

    if (params.has('amount') || params.has('assignee') || params.has('availability') || params.has('commodity') ||
        params.has('market') || params.has('category') || params.has('unit') || params.has('brand') || params.has(
            'collected_buy') || params.has('updated_by') || params.has('publish') || params.has('collected_date') ||
        params.has('start_date') || params.has('end_date') || params.has('id') || params.has('status')) {

        let dropdown = document.getElementById("dropdown");

        dropdown.classList.toggle("active");

    }

};
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<script>   
$(function() {
   $("#collected_date").datepicker(); 
  $("#start_date").datepicker();
  $("#end_date").datepicker();
});
</script>

@endpush