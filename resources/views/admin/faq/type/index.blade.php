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
                                    FAQ Type
                                </h4>
                            </div>


                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">


                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i>&nbsp; FAQ Type</button>

                                  <a class="btn btn-info btn-sm" href="{{route('admin.faq.list')}}"><i
                                            class="fas fa-file-import"></i> Back to FAQ  </a>
                        <!-- 
                                    <a class="btn btn-secondary btn-sm"
                                        href="{{route('admin.zone.export', request()->query())}}" title=""><i
                                            class="fas fa-file-download"></i> Export</a> -->

                                </div>
                            </div>
                        </div>

                        <!-- <form action="{{route('admin.zone.filter')}}" method="get">
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

                                        <a class="btn btn-secondary btn-sm "
                                            href="{{route('admin.zone.list')}}">Reset</a>
                                    </div>



                                </div>

                            </div>

                        </form> -->

                        <div class="table-responsive white-space">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="border-b bg-light2">

                                        <th style="width:10%;">S.No.</th>
                                        <th style="width:30%;">Name</th>
                                        <th style="width:20%;">Created At</th>
                                        <th style="width:20%;">Status</th>
                                        <th style="width:20%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$value->type}}</td>
                                        <td>{{ date('d-m-Y', strtotime($value->created_at)) }}</td>
                                        <td class="active-bt">
                                            <!-- {{($value->status == '1')?'Active':'Deactive'}} -->

                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>

                                        </td>
                                        <td>
                                            <div class="action-btn">

                                                <!-- <a href="{{route('admin.zone.view', $value->id)}}"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a> -->
                                                <img src="{{asset('admin/img/edit-2.png')}}" class="editZone"
                                                    id="{{$value->id}}" alt="edit">
                                                <img src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteFAQType" alt="trash">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
                    <h2 id="formHeading">Add FAQ Type</h2>
                    <p>Please enter the following Data</p>
                </div>
                <form method="post" id="zone" class="mt-4">
                    @csrf
                    <div class="loader"></div>
                    <div class="screen-block"></div>
                    <input type="hidden" id="id" value="" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Type Name<span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="text" class="form-control" id="name" name="name">
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Select Status<span class="text-danger">*</span></label>
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
                            <button type="submit" class="btnss btn-save">Add FAQ Type</button>
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
$(document).on('click', '.editZone', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });

    var id = $(this).attr('id');
    var url = "{{ route('admin.faq.type.edit', ':id') }}";
    url = url.replace(':id', id);
    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#name').val(response.data.type);
                    $('#id').val(response.data.id);
                    $('#status').val(response.data.status).change();
                    $("#formHeading").text('Edit Faq Type')
                    $(".btnss").text('Update faq type')
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

    $('#zone').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        // Show loader and block screen
        $('.loader').show();
        $('.screen-block').show();
        $.ajax({
            url: "{{ route('admin.faq.type.save') }}",
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
            url: "{{ route('admin.faq.type.update.status') }}",
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
        $("#formHeading").text('Add FAQ Type');
        $(".btn-save").text('Add FAQ Type');

        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
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
        endDateInput.min = startDate; // Set min date for End Date
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