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
                                  

                                    @can('complaint_category_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Category"><i class="fa-solid fa-plus"></i>&nbsp;
                                        Category</button>
                                    @endcan

                                </div>
                            </div>
                        </div>


                        <div class="table-responsive white-space">
 
                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Created At</th>
                                        @can('complaint_category_edit')
                                        <th>Status</th>
                                        @endcan

                                        @canany(['complaint_category_edit','complaint_category_delete'])
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

                                        <td>{{ customt_date_format($value->created_at) }}</td>
                                        @can('complaint_category_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>

                                                <span class="slider round"></span>

                                            </label>
                                        </td>
                                        @endcan
                                        @canany(['complaint_category_edit','complaint_category_delete'])

                                        <td>

                                            <div class="action-btn">

                                                @can('complaint_category_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory"
                                                    id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('complaint_category_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteComplaintCategory" alt="trash">
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
   
// Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});


$(document).on('click', '.editcategory', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('id');
    var url = "{{ route('admin.complaint.category.edit', ':id') }}";
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
            url: "{{ route('admin.complaint.category.save') }}",
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
            url: "{{ route('admin.complaint.category.update.status') }}",
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