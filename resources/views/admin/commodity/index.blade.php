@extends('admin.layouts.app')

@section('title', @$title)

@section('content')

<style>
.dropdown-container {
    position: relative;
    display: inline-block;
    width: 100%;
}



.customDropdown2 {
    appearance: none;
    width: 100%;
    padding: 8px 30px 8px 10px;

    border: 1px solid #ccc;

    border-radius: 5px;

    cursor: pointer;

    background: #fff url('https://cdn-icons-png.flaticon.com/512/892/892498.png') no-repeat;

    background-size: 14px;

    background-position: right 10px center;

}



.customDropdown2:focus {

    outline: none;

    border-color: #007bff;

}
</style>

<div class="px-3 ">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-xl-5">
                                <h4 class="header-title mb-0 font-weight-bold">
                                    Commodities
                                </h4>
                            </div>


                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">

                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>

                                    @can('commodity_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Commodity"><i class="fa-solid fa-plus"></i>&nbsp;
                                        Commodities</button>
                                    
                                    <a class="btn btn-info btn-sm" href="{{route('admin.import.commodity')}}"><i
                                            class="fas fa-file-import"></i> Import </a>

                                    <a class="btn btn-secondary btn-sm"
                                        href="{{route('admin.commodity.export', request()->query())}}" title=""><i
                                            class="fas fa-file-download"></i> Export</a>
                                    @endcan

                                </div>
                            </div>


                        </div>

                        <div class="row mb-4">

                            <form action="{{route('admin.commodity.filter')}}" method="get">
                                <hr>
                                <div id="dropdown" class="dropdown-container-filter fil-comm">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name"
                                            id="exampleFormControlInput1" placeholder="Name"
                                            value="{{ request('name') }}">
                                    </div>

                                  <!-- <div class="unit-input">
                                        <input type="text" class="form-control" name="unit"
                                            id="exampleFormControlInput1" placeholder="Unit Value"
                                            value="{{ request('unit') }}">

                                    </div>   -->

                                    <select class="form-select" name="category" aria-label="Default select example">
                                        <option value="" selected="">Category</option>
                                        @if(isset($category) && count($category)>0)
                                        @foreach($category as $catKey=>$categoryValue)

                                        <option {{ request('category') == $categoryValue->id ? 'selected' : '' }}
                                            value="{{$categoryValue->id}}">{{ucfirst($categoryValue->name)}}</option>

                                        @endforeach

                                        @endif

                                    </select>

                                    <select class="form-select" name="brand" aria-label="Default select example">

                                        <option value="" selected="">Brand</option>

                                        @if(isset($brand) && count($brand)>0)

                                        @foreach($brand as $brndKey=>$brndValue)

                                        <option {{ request('brand') == $brndValue->id ? 'selected' : '' }}
                                            value="{{$brndValue->id}}">{{ucfirst($brndValue->name)}}</option>

                                        @endforeach

                                        @endif

                                    </select>

                                    <select class="form-select" name="uom" aria-label="Default select example">

                                        <option value="" selected="">UOM</option>

                                        @if(isset($uom) && count($uom)>0)

                                        @foreach($uom as $uomKey=>$uValue)

                                        <option {{ request('uom') == $uValue->id ? 'selected' : '' }}
                                            value="{{$uValue->id}}">{{ucfirst($uValue->name)}}</option>

                                        @endforeach

                                        @endif

                                    </select>

                                    <select class="form-select" name="status" aria-label="Default select example">

                                        <option value="" selected="">Status</option>

                                        <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active
                                        </option>

                                        <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive
                                        </option>

                                    </select>

                                    <div class="filter-date">

                                        <!-- <label for="start-date">Start Date</label> -->

                                        <input type="text" value="{{ request('start_date') }}" name="start_date"
                                            class="form-control" placeholder="Start Date" id="start_date" autocomplete="off">

                                    </div>

                                    <div class="filter-date">

                                        <!-- <label for="end-date">End Date</label> -->

                                        <input value="{{ request('end_date') }}" type="text" name="end_date"
                                            class="form-control" placeholder="End Date" id="end_date" autocomplete="off">

                                    </div>

                                    <button type="submit" class="d-flex searc-btn">Search</button>
                                  
                                    <a href="{{route('admin.commodity.list')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>

                                </div>

                            </form>

                        </div>

                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th >S.No.</th>
                                        <th >Image</th>
                                        <th >Commodities</th>
                                        <th >Category </th>
                                        <th >Brand </th>
                                        <th >UOM </th>
                                        <th >Created At</th>
                                         @can('commodity_edit')
                                        <th >Status</th>
                                        @endcan

                                        @canany(['commodity_edit','commodity_delete'])
                                        <th >Action</th>
                                        @endcanany
                                    </tr>

                                </thead>

                                <tbody>

                                    @if(isset($data) && count($data)>0)

                                    @foreach($data as $key=>$value)

                                    <tr>

                                        <td>{{$key+1}}</td>

                                        <td>

                                            <div class="market-img">
                                                @if(!empty($value->image))
                                                <img style="height: 40px;width: 40px;border-radius: 25%"
                                                    src="{{asset('admin/images/commodity/'.$value->image)}}"
                                                    alt="market">
                                                @else
                                                <img style="height: 40px;width: 40px;border-radius: 25%"
                                                    src="{{asset('admin/images/consumer-affairs-logo.png')}}"
                                                    alt="market">
                                                @endif

                                            </div>

                                        </td>

                                        <td>{{$value->name}}</td>

                                        <td>{{($value->category)?ucfirst($value->category->name):''}}</td>

                                        <td>{{($value->brand)?ucfirst($value->brand->name):''}}</td>

                                        <td>{{($value->uom)?ucfirst($value->uom->name):''}}</td>

                                        <!-- <td>{{ucfirst($value->unit_value)}}</td> -->

                                        <td>{{ customt_date_format($value->created_at) }}</td>
                                         @can('commodity_edit')

                                        <td>

                                            <div class="action-btn">

                                                <label class="switch">
                                                     

                                                    <input type="checkbox" value="{{$value->id}}"
                                                            class="toggleSwitch"
                                                            {{ $value->status == 1 ? 'checked' : '' }}>

                                                        <span class="slider round"></span>

                                                </label>
                                            </div>
                                        </td>
                                        @endcan
                                        @canany(['commodity_edit','commodity_delete'])

                                        <td>

                                            <div class="action-btn">
                                                @can('commodity_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcommodity" id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('commodity_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteCommodity" alt="trash">
                                                @endcan
                                                 @can('commodity_edit')
                                                <a data-toggle="tooltip" data-placement="top" title="Copy" href="javascript:void(0)" id="{{$value->id}}" class="copyCommodity">
                                                    <i class="far fa-copy"></i>
                                                </a>
                                                 @endcanany
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>

                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">
                                            <p class="no_data_found">No Data found! </p>
                                        </td>
                                    </tr>

                                    @endif

                                </tbody>

                            </table>

                            @if (isset($data))

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



<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">

                <div class="heading">

                    <h2 id="formHeading">Add Commodity</h2>

                    <p>Please enter the following Data</p>

                </div>

                <form method="post" id="commodity" class="mt-4">
                    @csrf
                    <div class="loader"></div>
                    <div class="screen-block"></div>

                    <div class="row">

                        <div class="col-md-6 form-group ad-user">
                            <input type="hidden" id="id" value="" name="id">
                            <label> Commodity Name <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="text" class="form-control" name="name" id="name" >
                                <span class="text-danger error-name"></span>
                            </div>

                        </div>




                        <div class="col-md-6">

                            <div class="form-group ad-user">
                            <label>Select Category <span class="text-danger">*</span></label>
                                <div class="dropdown-container">
                                    <div class="rela-icon">
                                    <select class="form-select" id="category_filter" name="category">
                                        <option value="" selected>Category</option>
                                        @foreach($category as $categoryValue)
                                            <option {{ request('category') == $categoryValue->id ? 'selected' : '' }}
                                                    value="{{ $categoryValue->id }}">{{ ucfirst($categoryValue->name) }}</option>
                                        @endforeach
                                    </select>

                                    <span class="text-danger error-category"></span>
                                     <i class="fa-solid fa-caret-down"></i>
                                    </div> 
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group ad-user">

                                <label>Select Brand <span class="text-danger">*</span></label>
                                <div class="dropdown-container">
                                    <div class="rela-icon">
                                        <select class="form-select" id="brand_filter" name="brand">
                                            <option value="" selected>Brand</option>
                                            <!-- @foreach($brand as $brndValue)
                                                <option {{ request('brand') == $brndValue->id ? 'selected' : '' }}
                                                        value="{{ $brndValue->id }}">{{ ucfirst($brndValue->name) }}</option>
                                            @endforeach -->
                                        </select>

                                        <span class="text-danger error-brand"></span>
                                        <i class="fa-solid fa-caret-down"></i>
                                     </div> 
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="form-group ad-user">

                                <label>Select UOM <span class="text-danger">*</span></label>
                                <div class="dropdown-container">
                                    <div class="rela-icon">
                                        <select class="form-select" id="uom_filter" name="uom">
                                            <option value="" selected>UOM</option>
                                            <!-- @foreach($uom as $uValue)
                                                <option {{ request('uom') == $uValue->id ? 'selected' : '' }}
                                                        value="{{ $uValue->id }}">{{ ucfirst($uValue->name) }}</option>
                                            @endforeach -->
                                        </select>

                                        <span class="text-danger error-uom"></span>
                                        <i class="fa-solid fa-caret-down"></i>
                                    </div> 
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group ad-user">

                            <label>Select Status <span class="text-danger">*</span></label>
                                <div class="dropdown-container">

                                    <select class="form-control customDropdown2" id="status" name="status">

                                        <option value="1">Active</option>

                                        <option value="0">Deactive</option>

                                    </select>

                                    <span class="text-danger error-status"></span>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="form-group ad-user">
                            <label>Upload Image</label>

                                <div class="upload-box" id="uploadBox">

                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image</span>

                                    <input type="file" id="fileInput" name="image" accept="image/*">

                                </div>
                                 <span class="font-text">Only .jpg, .jpeg and .png are accepted. Maximum file size: 2 MB.</span><br>
                                <span id="imageError" style="color:red;"></span>
                                <span class="text-danger error-image"></span>

                            </div>

                            <img id="editImage" src="" class="editt-img" style="display: none;">

                        </div>

                        <div class="text-center">

                            <button type="submit" class="btnsss btn-save">Add Commodity</button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
@push('scripts')

<script>
    
document.getElementById('fileInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.size > 2 * 1024 * 1024) { // 2MB
        document.getElementById('imageError').innerText = "Image must be less than 2MB.";
        e.target.value = ""; // clear the input
    } else {
        document.getElementById('imageError').innerText = "";
    }
});

// Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});

    
const fileInput = document.getElementById("fileInput");
const fileNameDisplay = document.getElementById("fileName");
fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        fileNameDisplay.textContent = file.name; // Display the name of the file

    } else {
        fileNameDisplay.textContent = "Upload Image"; // Reset text if no file is selected
    }

});

let editBrandId = null;
let editUOMId = null;

$(document).on('click', '.editcommodity', function (e) {
    const modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    const id = $(this).attr('id');
    let url = "{{ route('admin.commodity.edit', ':id') }}";
    url = url.replace(':id', id);

    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    let unit = response.data.unit_value || '';

                    $("#units").empty().append(`
                        <div class="col-md-12">
                            <div class="form-group ad-user">
                                <label for="unit_value">Unit Value</label>
                                <input type="text" class="form-control" name="unit_value" id="unit_value" placeholder="Enter Unit Values (i.e. 1KG, 2KG, etc)">
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>
                    `);

                    $('#unit_value').val(unit);
                    $('#name').val(response.data.name);
                    $('#id').val(response.data.id);
                    $('#status').val(response.data.status).change();
                    $('#category_filter').val(response.data.category_id).trigger('change');

                    // Save values temporarily
                    editBrandId = response.data.brand_id;
                    editUOMId = response.data.uom_id;

                    if (response.data.image) {
                        $("#editImage").css('display', 'flex').attr('src', response.data.image);
                    } else {
                        $("#editImage").hide();
                    }

                    $("#formHeading").text('Edit Commodity');
                    $(".btnsss").text('Update Commodity');
                    modal.show();
                }
            },
            error: function (xhr) {
                console.error(xhr.responseJSON.errors);
            }
        });
    }
});



$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    $('#commodity').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('.loader').show();
        $('.screen-block').show();

        $.ajax({
            url: "{{ route('admin.commodity.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.text-danger').text('');
            },

            success: function(response) {
                // console.log(response);
                if (response.success) {
                    toastr.success(response.message)
                    modal.hide();
                    location.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $(".text-danger").text("");
                if (errors.name) {
                    $(".error-name").text(errors.name[0]);
                }

                if (errors.category) {
                    $(".error-category").text(errors.category[0]);
                }

                if (errors.brand) {
                    $(".error-brand").text(errors.brand[0]);
                }
                if (errors.uom) {
                    $(".error-uom").text(errors.uom[0]);
                }

                if (errors.status) {

                    $(".error-status").text(errors.status[0]);

                }

                if (errors.image) {

                    $(".error-image").text(errors.image[0]);

                }

                modal.show();

            },
            complete: function() {
                // Hide loader and unblock screen
                $('.loader').hide();
                $('.screen-block').hide();
            }


        });

    });



    $(".toggleSwitch").on("change", function() {

        var status = $(this).is(":checked") ? 1 : 0;

        var id = $(this).val();



        $.ajax({

            url: "{{ route('admin.commodity.update.status') }}",

            type: "POST",

            data: {
                _token: "{{ csrf_token() }}",
                status: status,
                id: id
            },

            success: function(response) {

                console.log(response);

                if (response.success) {

                    toastr.success(response.message)

                }

            },

            error: function(xhr, status, error) {

                toastr.success(response.message)

            }

        });

    });



    $("#addUnit").on('click', function() {
        $("#units").append(
            '<div class="col-md-12 addedUnits"><div class="form-group ad-user"><div class="d-flex"><input type="text" class="form-control" name="unit_values[]" id="" placeholder="Enter Unit Values (i.e. 1KG, 2KG, etc)"><button type="button" class="btnsssz btn-danger ml-2 removeUnit"><i class="fas fa-minus"></i></button></div><span class="text-danger error-unit_value"></span></div></div>'
            );

    });



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



    // Close dropdown when clicking outside

    $(document).on('click', function(e) {

        if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target)
            .length === 0) {

            $dropdownMenu.hide();

        }

    });



    // Update dropdown button text based on selected items

    $checkboxes.on('change', function() {

        const selectedOptions = $checkboxes.filter(':checked').map(function() {

            return $(this).val();

        }).get();



        $dropdownButton.html(

            selectedOptions.length > 0

            ?
            selectedOptions.join(', ') + ' <i class="fa fa-caret-down"></i>'

            :
            'Select Options <i class="fa fa-caret-down"></i>'
        );
    });
});

$(document).on('click', '.removeUnit', function() {

    $(this).closest('.addedUnits').remove();

});



function toggleDropdown() {

    var dropdown = document.getElementById("dropdown");

    dropdown.classList.toggle("active");

}


window.onload = function() {

    let params = new URLSearchParams(window.location.search);

    if (params.has('name') || params.has('unit') || params.has('brand') || params.has('uom') || params.has(
            'start_date') || params.has('end_date') || params.has('status') || params.has('category')) {

        let dropdown = document.getElementById("dropdown");

        dropdown.classList.toggle("active");

    }

};

$('#exampleModal').on('hidden.bs.modal', function() {
    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
});


const toggle = document.getElementById('myToggle');
const tooltip = new bootstrap.Tooltip(toggle);

toggle.addEventListener('change', function () {
let newTitle = this.checked ? 'Turned On' : 'Turned Off';
this.setAttribute('title', newTitle);
tooltip.setContent({ '.tooltip-inner': newTitle });
});
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
$(document).ready(function () {
   // Handle brand dropdown when category changes
$('#category_filter').on('change', function () {
    let categoryId = $(this).val();
    $('#brand_filter').empty().append('<option value="" selected>Brand</option>');
    $('#uom_filter').empty().append('<option value="" selected>UOM</option>');

    if (categoryId) {
        $.ajax({
            url: '{{ route("admin.commodity.get-brands-by-category") }}',
            type: 'GET',
            data: { category_id: categoryId },
            success: function (brands) {
                $.each(brands, function (i, brand) {
                    $('#brand_filter').append(`<option value="${brand.id}">${brand.name}</option>`);
                });

                // Set selected brand from edit
                if (editBrandId) {
                    $('#brand_filter').val(editBrandId).trigger('change');
                    editBrandId = null;
                }
            }
        });
    }
});

    // Handle UOM dropdown when brand changes
$('#brand_filter').on('change', function () {
    let brandId = $(this).val();
    $('#uom_filter').empty().append('<option value="" selected>UOM</option>');

    if (brandId) {
        $.ajax({
            url: '{{ route("admin.commodity.get-uoms-by-brand") }}',
            type: 'GET',
            data: { brand_id: brandId },
            success: function (uoms) {
                $.each(uoms, function (i, uom) {
                    $('#uom_filter').append(`<option value="${uom.id}">${uom.name}</option>`);
                });

                // Set selected UOM from edit
                if (editUOMId) {
                    $('#uom_filter').val(editUOMId);
                    editUOMId = null;
                }
            }
        });
    }
});


$(document).on('click', '.copyCommodity', function(e) {
    e.preventDefault();

    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('id');
    var url = "{{ route('admin.commodity.edit', ':id') }}".replace(':id', id);

    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    let unit = response.data.unit_value || '';

                    // Set unit field
                    $("#units").empty().append(`
                        <div class="col-md-12">
                            <div class="form-group ad-user">
                                <label for="unit_value">Unit Value</label>
                                <input type="text" class="form-control" name="unit_value" id="unit_value" placeholder="Enter Unit Values (i.e. 1KG, 2KG, etc)">
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>
                    `);
                    $("#unit_value").val(unit);

                    // Set other fields
                    $('#name').val(response.data.name);
                    $('#status').val(response.data.status).change();
                    $('#category_filter').val(response.data.category_id).trigger('change');

                    editBrandId = response.data.brand_id;
                    editUOMId = response.data.uom_id;

                    
                    
                    // Set image preview
                    if (response.data.image) {
                        $("#editImage").css('display', 'flex').attr('src', response.data.image);
                    } else {
                        $("#editImage").hide();
                    }

                    // Update form heading and button
                    $("#formHeading").text('Copy Commodity');
                    $(".btnsss").text('Copy Commodity');

                    // Show modal after a short delay to allow dropdowns to update
                    setTimeout(() => {
                        $('#exampleModal').modal('show');
                    }, 350); // Make sure this is slightly more than dropdown delay
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                console.error(errors);
            }
        });
    }
});


});

$(document).on('click', '.btn-close', function () {
    $('#exampleModal').modal('hide'); // Close modal
    location.reload(); // Reload page
});

</script>

<script>
$(function() {
    $("#start_date").datepicker();
    $("#end_date").datepicker();
});
</script>

@endpush