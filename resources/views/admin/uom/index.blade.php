@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<style>
    
</style>

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
                                    Unit Of Measurement
                                </h4>
                            </div>

                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>

                                    @can('uom_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create UOM"><i class="fa-solid fa-plus"></i>&nbsp;
                                        UOM</button>
                                

                                    <a class="btn btn-info btn-sm" href="{{route('admin.import.uom')}}"><i
                                            class="fas fa-file-import"></i> Import </a>

                                    <a class="btn btn-secondary btn-sm"
                                        href="{{route('admin.uom.export', request()->query())}}" title=""><i
                                            class="fas fa-file-download"></i> Export</a>
                                            @endcan

                                </div>



                            </div>

                            <div class="row mb-3">

                               <form action="{{ route('admin.uom.filter') }}" method="get">
                                    <hr>
                                    <div id="dropdown" class="dropdown-container-filter">
                                        <div class="name-input">
                                            <input type="text" class="form-control" name="name"
                                                id="exampleFormControlInput1" placeholder="Name"
                                                value="{{ request('name') }}">
                                        </div>

                                        {{-- Category Dropdown --}}
                                        <select class="form-select" name="category_id" aria-label="Category">
                                            <option value="">Select Category</option>
                                            @foreach($category as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Brand Dropdown --}}
                                        <select class="form-select" name="brand_id" aria-label="Brand">
                                            <option value="">Select Brand</option>
                                            @foreach($brand as $br)
                                                <option value="{{ $br->id }}" {{ request('brand_id') == $br->id ? 'selected' : '' }}>
                                                    {{ $br->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select class="form-select" name="status" aria-label="Status">
                                            <option value="">Status</option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Deactive</option>
                                        </select>

                                        <div class="filter-date">
                                            <input type="text" name="start_date" class="form-control"
                                                value="{{ request('start_date') }}" placeholder="Start Date"
                                                id="start_date" autocomplete="off">
                                        </div>

                                        <div class="filter-date">
                                            <input type="text" name="end_date" class="form-control"
                                                value="{{ request('end_date') }}" placeholder="End Date"
                                                id="end_date" autocomplete="off">
                                        </div>

                                        <button type="submit" class="d-flex searc-btn">Search</button>
                                        <a class="btn btn-secondary btn-sm" href="{{ route('admin.uom.list') }}">Reset</a>
                                    </div>
                                </form>


                            </div>

                            <div class="table-responsive white-space">

                                <table class="table table-hover mb-0">

                                    <thead>

                                        <tr class="border-b bg-light2">

                                            <th>S.No.</th>
                                            <th>Category</th>
                                            <th> Name</th>
                                            <th>Brand</th>
                                            <th>Created At</th>
                                            @canany('uom_edit')
                                            <th>Status</th>
                                            @endcan

                                            @canany(['uom_edit','uom_delete'])
                                            <th>Action</th>
                                            @endcanany
                                        </tr>

                                    </thead>

                                    <tbody>

                                        @if(isset($data) && count($data)>0)

                                        @foreach($data as $key=>$value)

                                        <tr>

                                            <td>{{$key+1}}</td>
                                            <td>{{ optional($category->firstWhere('id', $value->categories_id))->name ?? 'N/A' }}</td>
                                            <td>{{ucfirst($value->name)}}</td>
                                            <td>{{ optional($brand->firstWhere('id', $value->brand_id))->name ?? 'N/A' }}</td>
                                            <td>{{ customt_date_format($value->created_at) }}</td>
                                            @canany('uom_edit')

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
                                            @canany(['uom_edit','uom_delete'])

                                            <td>
                                                <div class="action-btn">
                                                    @can('uom_edit')
                                                    <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="edituom" id="{{$value->id}}" alt="edit">
                                                    @endcan

                                                    @can('uom_delete')
                                                    <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteuom" alt="trash">
                                                    @endcan
                                                </div>
                                            </td>
                                            @endcanany
                                        </tr>

                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5">
                                                <p class="no_data_found">No Data found! </p>
                                            </td>
                                        </tr>

                                        @endif

                                    </tbody>

                                </table>

                                @if (isset($data))

                                {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }}

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

                    <h2 id="formHeading">Add UOM</h2>

                    <p>Please enter the following Data</p>

                </div>

                <form method="post" id="uom" class="mt-4"  autocomplete="off">

                    @csrf
                    <div class="loader"></div>
                    <div class="screen-block"></div>
                    <div class="row">

                        <input type="hidden" id="id" value="" name="id">
                                <div class="col-md-6">
                                    <label>Select Category <span class="text-danger">*</span></label>
                                    <div class="form-group ad-user">
                                        <div class="rela-icon">
                                            <select class="form-control" id="category_id" name="category_id">
                                                <option value="" hidden>Select Category</option>
                                                @if(isset($category) && count($category) > 0)
                                                    @foreach($category as $categories)
                                                        <option value="{{ $categories->id }}">{{ ucfirst($categories->name) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="fa-solid fa-caret-down"></i>
                                            <span class="text-danger error-category"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>Select Brand <span class="text-danger">*</span></label>
                                    <div class="form-group ad-user">
                                        <div class="rela-icon">
                                            <select class="form-control" id="brand_id" name="brand_id">
                                                <option value="">Select Brand</option>
                                                {{-- This will be filled dynamically --}}
                                            </select>
                                            <i class="fa-solid fa-caret-down"></i>
                                            <span class="text-danger error-brand"></span>
                                        </div>
                                    </div>
                                </div>


                       <div class="col-md-6">
                            <label>Unit Name (i.e Weight, Size etc.) <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="text" class="form-control" id="name" name="name" list="uomList">
                                <datalist id="uomList"></datalist>
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                        <label > Select Status <span class="text-danger">*</span> </label>
                            <div class="form-group ad-user">
                                <div class="rela-icon">
                                    <select class="form-control" id="status" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-status"></span>
                                </div>

                            </div>

                        </div>

                        <div class="text-center">

                            <button type="submit" class="btnsss btn-save">Add Unit</button>

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
// Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});

$(document).on('click', '.edituom', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('id');
    var url = "{{ route('admin.uom.edit', ':id') }}";
    url = url.replace(':id', id);

    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#name').val(response.data.name);
                    $('#id').val(response.data.id);
                    $('#status').val(response.data.status).change();
                    $("#formHeading").text('Edit UOM');
                    $(".btnsss").text('Update UOM');

                    const selectedCategoryId = response.data.categories_id;
                    const selectedBrandId = response.data.brand_id;

                    // Set category and trigger change to load brands
                    $('#category_id').val(selectedCategoryId).trigger('change');

                    // Wait for the category change to finish loading brands
                    setTimeout(function () {
                        $.ajax({
                            url: '{{ route("admin.uom.get-categories-by-type") }}',
                            type: 'GET',
                            data: { category_id: selectedCategoryId },
                            success: function (brands) {
                                $('#brand_id').empty().append('<option value="">Select Brand</option>');
                                $.each(brands, function (key, brand) {
                                    $('#brand_id').append('<option value="' + brand.id + '">' + brand.name + '</option>');
                                });

                                // ✅ Pre-select the correct brand
                                $('#brand_id').val(selectedBrandId);
                            },
                            error: function () {
                                alert('Failed to fetch brands.');
                            }
                        });
                    }, 300);

                    $('#exampleModal').modal('show');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
            }
        });
    }
});


$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false

    });

    $('#uom').on('submit', function(e) {
        e.preventDefault();
        

        const brandId = $('#brand_id').val();
        const categoryId = $('#category_id').val();
        let hasError = false;

        if (!brandId) {
        $(".error-brand").text("Please select brand.");
        $('#brand_id').focus();
        hasError = true;
        } else if (!categoryId) {
        $(".error-category").text("Please select category.");
        $('#category_id').focus();
        hasError = true;
        }

        if (hasError) return;
        let formData = new FormData(this);
        formData.append('brand_id', brandId);
        formData.append('category_id', categoryId);
        $('.loader').show();
        $('.screen-block').show();
        
        $.ajax({
            url: "{{ route('admin.uom.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.text-danger').text('');
            },

            success: function(response) {

                console.log(response);

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
                if (errors.status) {
                    $(".error-status").text(errors.status[0]);
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

            url: "{{ route('admin.uom.update.status') }}",

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



function toggleDropdown() {

    var dropdown = document.getElementById("dropdown");

    dropdown.classList.toggle("active");

}



$('#exampleModal').on('hidden.bs.modal', function() {
    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
});

let editBrandId = null; // used for pre-select in edit mode
    $('#category_id').on('change', function () {
    const categoryId = $(this).val();

    if (categoryId) {
        $.ajax({
            url: '{{ route("admin.uom.get-categories-by-type") }}', // adjust if needed
            type: 'GET',
            data: { category_id: categoryId },
            success: function (response) {
                $('#brand_id').empty().append('<option value="">Select Brand</option>');
                $.each(response, function (key, value) {
                    $('#brand_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                });

                if (editBrandId) {
                    $('#brand_id').val(editBrandId).prop('disabled', true);
                }
            },
            error: function () {
                alert('Failed to fetch brands.');
            }
        });
    } else {
        $('#brand_id').empty().append('<option value="">Select Brand</option>');
    }
});

let allUoms = [];

$(document).ready(function () {
    // Fetch UOMs from DB on brand change
    $('#brand_id').on('change', function () {
        const brandId = $(this).val();

        if (brandId) {
            $.ajax({
                url: "{{ route('admin.uom.get-uom-by-brand') }}",
                type: "GET",
                data: { brand_id: brandId },
                success: function (data) {
                    allUoms = data.map(item => item.name); // Store UOMs
                    $('#uomList').empty(); // Clear datalist

                    // You can also pre-populate entire list on brand change (optional)
                    // data.forEach(uom => {
                    //     $('#uomList').append(`<option value="${uom.name}">`);
                    // });
                }
            });
        }
    });

    // Filter and show matching UOM suggestions
    $('#name').on('input', function () {
        const inputVal = $(this).val().toLowerCase();
        $('#uomList').empty();

        if (inputVal) {
            const filtered = allUoms.filter(uom => uom.toLowerCase().includes(inputVal));
            filtered.forEach(name => {
                $('#uomList').append(`<option value="${name}">`);
            });
        }
    });

    // ✅ Allow custom UOM values, so no validation here
});
$(document).ready(function () {
        let hasFilter = "{{ request()->has('name') || request()->has('status') || request()->has('start_date') || request()->has('end_date') ? 'yes' : 'no' }}";

        if (hasFilter === 'yes') {
            $('#dropdown').addClass('active');
        }
});
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>
$(function() {
    $("#start_date").datepicker();
    $("#end_date").datepicker();
});
</script>


@endpush