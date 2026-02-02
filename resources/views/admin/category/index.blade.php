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
                                    Categories
                                </h4>
                            </div>

                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()" data-toggle="tooltip" data-placement="top" title="Filter"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>

                                    @can('category_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Category"><i class="fa-solid fa-plus"></i>&nbsp;
                                        Category</button>
                               
                                    <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Import" href="{{route('admin.import.category')}}"><i
                                            class="fas fa-file-import"></i> Import </a>

                                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Export"
                                        href="{{route('admin.category.export', request()->query())}}" title=""><i
                                            class="fas fa-file-download"></i> Export</a>
                                             @endcan
                                </div>
                            </div>
                        </div>

                        <form action="{{route('admin.category.filter')}}" method="get">
                            <hr>
                            <div id="dropdown" class="dropdown-container-filter">
                                <div class="row mb-3 w-100">
                                    <div class="col-lg-3">
                                        <div class="name-input2">
                                            <input type="text" class="form-control" name="name"
                                                id="exampleFormControlInput1" placeholder="Name"
                                                value="{{ request('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-select w-100" name="status"
                                            aria-label="Default select example">
                                            <option value="" selected="">Status</option>
                                            <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active
                                            </option>
                                            <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 ">
                                        <button type="submit" class="d-fle searc-btn btn-sm">Search</button>
                                        <a href="{{ route('admin.category.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                                     
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive white-space">
 
                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Type</th>
                                        <th>Created At</th>
                                        @can('category_edit')
                                        <th>Status</th>
                                        @endcan

                                        @canany(['category_edit','category_delete'])
                                        <th style="width:15%;">Action</th>
                                        @endcan

                                    </tr>

                                </thead>

                                <tbody>

                                    @if(isset($data) && count($data)>0)

                                    @foreach($data as $key=>$value)

                                    <tr>

                                        <td>{{$key+1}}</td>

                                        <td>{{ucfirst($value->name)}}</td>

                                        <td>

                                            <div class="market-img">
                                                @if(!empty($value->image))
                                                    <img style="height: 40px;width: 40px;border-radius: 25%"
                                                    src="{{asset('admin/images/category/'.$value->image)}}"
                                                    alt="market">
                                                @else
                                                    <img style="height: 40px;width: 40px;border-radius: 25%"
                                                        src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="market">
                                                @endif

                                                

                                            </div>

                                        </td>
                                        <td>{{($value->type)?ucfirst($value->type->name):'N/A'}}</td>
                                        

                                        <td>{{ customt_date_format($value->created_at) }}</td>
                                        @can('category_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>

                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        @endcan
                                        @canany(['category_edit','category_delete'])
                                        <td>

                                            <div class="action-btn">

                                                @can('category_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory"
                                                    id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('category_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteCategory" alt="trash">
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

<!-- content -->



</div>

<!-- ============================================================== -->

<!-- End Page content -->

<!-- ============================================================== -->

</div>

<!-- END wrapper -->

<!-- Modal -->

<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">
                <div class="heading">
                    <h2 id="formHeading">Add New Category</h2>
                    <p>Please enter the following Data</p>
                </div>

                <form method="post" class="mt-4" id="category">

                    @csrf

                    <div class="loader"></div>
                    <div class="screen-block"></div>

                    <div class="row">

                        <div class="col-md-6">
                            <input type="hidden" id="id" value="" name="id">
                            <label > Category Name <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="text" class="form-control" name="name" id="name">
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label > Select Type <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                       

                                        <div class="rela-icon">
                                            <select class="form-control" id="type_id" name="type_id">
                                                <option value="" hidden>Select Type</option>
                                                @if(isset($type) && count($type)> 0)
                                                    @foreach($type as $types)
                                                    <option value="{{$types->id}}" >{{ucfirst($types->name)}} </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <i class="fa-solid fa-caret-down"></i>
                                            <span class="text-danger error-status"></span>
                                        </div>


                            </div>
                        </div>

                        <div class="col-md-6">
                            <label > Select Status <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <div class="rela-icon">
                                    <select class="form-control" id="status" name="status">
                                        <!-- <option value="">Select Status</option> -->
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-status"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="form-group ad-user">
                            <label > Upload Image <span class="text-danger"></span></label>
                                <div class="upload-box" id="uploadBox">
                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image </span>
                                    <input type="file" id="image" name="image" accept="image/*">
                                </div>
                                 <span class="font-text">Only .jpg, .jpeg and .png are accepted. Maximum file size: 2 MB.</span><br>
                                <span class="text-danger error-image"></span>
                                <span id="imageError" style="color:red;"></span>
                            </div>
                            <img id="editImage" src="" class="editt-img" style="display: none;">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btnss btn-save">Add Category</button>
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
    document.getElementById('image').addEventListener('change', function (e) {
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

const fileInput = document.getElementById("image");
const fileNameDisplay = document.getElementById("fileName");

fileInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
        fileNameDisplay.textContent = file.name;
    } else {
        fileNameDisplay.textContent = "Upload Image";
    }
});

$(document).on('click', '.editcategory', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('id');
    var url = "{{ route('admin.category.edit', ':id') }}";
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
                    $('#type_id').val(response.data.type_id).change();

                    $("#editImage").css('display', 'flex')

                    $("#editImage").attr('src', response.data.image)

                    $("#formHeading").text('Edit Category')

                    $(".btnss").text('Update Category')

                    // modal.show();
                    $('#exampleModal').modal('show');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
            }
        });
    }
})



$(document).ready(function() {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false

    });

    $('#category').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('.loader').show();
        $('.screen-block').show();
        $.ajax({
            url: "{{ route('admin.category.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.text-danger').text('');
            },

            success: function(response) {
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
            url: "{{ route('admin.category.update.status') }}",
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


    $('#exampleModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $('#id').val('');
        $("#editImage").hide().attr('src', '');
        $("#formHeading").text('Add Category');
        $(".btn-save").text('Add Category');

        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });;


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
    setupDropdown('dropdownButton4')
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
            selectedOptions.length > 0 ?
            selectedOptions.join(', ') + ' <i class="fa fa-caret-down"></i>' :
            'Select Options <i class="fa fa-caret-down"></i>'
        );

    });



});



function toggleDropdown() {
    var dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");

}



window.onload = function() {
    let params = new URLSearchParams(window.location.search);
    if (params.has('name') || params.has('start_date') || params.has('status') || params.has('end_date')) {
        let dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("active");
    }
};

document.addEventListener("DOMContentLoaded", function() {
    let startDateInput = document.querySelector('input[name="start_date"]');
    let endDateInput = document.querySelector('input[name="end_date"]');

    startDateInput.addEventListener("change", function() {
        let startDate = startDateInput.value;
        endDateInput.min = startDate;
    });
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