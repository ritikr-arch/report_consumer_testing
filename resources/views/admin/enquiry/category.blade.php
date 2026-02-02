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
                                    Contact Categories
                                </h4>
                            </div>
                            @can('contact_categories_create')
                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                                        <i class="fa-solid fa-filter"></i>&nbsp;Filter
                                    </button>
                                    
                                    <button class="d-fle open-survery-modal btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Create Category">
                                      <i class="fa-solid fa-plus"></i>&nbsp;Category </button>
                                     
                                </div>
                            </div>
                             @endcan
                        </div>

                        <div class="row mb-4">
                            <form action="{{route('admin.enquiry.category')}}" method="get">
                                <hr>
                                <div id="dropdown" class="dropdown-container-filter">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ request('name') }}" maxlength="200">
                                    </div>
                                    <div class="name-input">
                                        <select class="form-select w-100" name="status" aria-label="Default select example">
                                          <option value="" selected disabled>Status</option>
                                          <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active </option>
                                          <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive </option>
                                        </select>
                                    </div>
                                    <div class="filter-date">
                                        <input type="text" value="{{ request('start_date') }}" name="start_date" class="form-control"  placeholder="Start Date" id="start_date" autocomplete="off">
                                    </div>
                                    <div class="filter-date">
                                        <input type="text" value="{{ request('end_date') }}" name="end_date"
                                            class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                                    </div>
                                       <button type="submit" class="d-fle searc-btn btn-sm">Search</button>
                                        <a href="{{route('admin.enquiry.category')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                                   

                                </div>

                            </form>

                        </div>

                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th style="min-width:10%;">S.No.</th>
                                        <th style="min-width:15%;">Name</th>
                                        @can('contact_categories_edit')
                                        <th style="min-width:20%;">Status</th>
                                        @endcan
                                        
                                        <th style="min-width:120px;">Created At</th>
                                        @canany(['contact_categories_edit','contact_categories_delete'])
                                        <th style="min-width:120px;">Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ $value->name }}</td>
                                        @can('contact_categories_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                            <input  data-toggle="tooltip" data-placement="top" title="Status" type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                          </label>
                                        </td>
                                        @endcan
                                        <td>{{ customt_date_format($value->created_at) }}</td>
                                        @canany(['contact_categories_edit','contact_categories_delete'])
                                        <td>

                                            <div class="action-btn">
                                                @can('contact_categories_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory"
                                                    id="{{$value->id}}" alt="edit">
                                                    @endcan

                                                @can('contact_categories_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteEnquiryCategory" alt="trash">
                                                    @endcan
                                                
                                            </div>

                                        </td>
                                        @endcanany
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">
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

<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="formHeading">Add Category</h2>
          <p>Please enter the following Data</p>
        </div>
        <form method="post" id="category" class="mt-4"> 
            @csrf <div class="loader"></div>
            <div class="screen-block"></div>
            <div class="row">
            <div class="col-md-6 mb-3">
              <input type="hidden" id="id" value="" name="id">
              <div class="form-group ad-user marg-bot">
                <label>Category Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Category Title">
                <span class="text-danger error-name"></span>
              </div>
            </div>
            
            <div class="col-md-6 mb-3">
              <div class="form-group ad-user marg-bot">
                <label > Select Status <span class="text-danger">*</span> </label>
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
              <button type="submit" class="btnrhrhtht btn-save btnss">Add Category</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<!-- Reusable Modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {

    $('body').on('click', '.open-survery-modal', function(event) {
      $('#exampleModal').modal('show');
    })
    
    $('#exampleModal').on('hidden.bs.modal', function () {
      location.reload();
    });

  });

</script>

<script>

    $(document).on('click', '.editcategory', function(e) {
        var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false
        });

        var id = $(this).attr('id');
        var url = "{{ route('admin.enquiry.category.edit', ':id') }}";
        url = url.replace(':id', id);
        if (id) {
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.success){
                        $('#name').val(response.data.name);
                        $('#id').val(response.data.id);

                        $('#status').val(response.data.status).change();
                       
                        $("#formHeading").text('Edit Category')

                        $(".btnss").text('Update Category')

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

    $('#category').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.save.enquiry.category') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                // $('.text-danger').text('');
            },
            success: function(response) {
                console.log(response)
                if (response.success) {
                    toastr.success(response.message)
                    location.reload();
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                // $(".text-danger").text("");
                if (errors.name) {
                    $(".error-name").text(errors.name[0]);
                }
                if (errors.status) {
                    $(".error-status").text(errors.status[0]);
                }
                modal.show();
            }
        });
    });



    $(".toggleSwitch").on("change", function() {
        var status = $(this).is(":checked") ? 1 : 0;
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.update.enquiry.category.status') }}",
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
        $("#formHeading").text('Add Zone');
        $(".btn-save").text('Add Zone');
    });

    $('body').on('click','.modal-close',function(e){
        console.log('fdsa');
        $('.ddsa').modal('hide');
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
    if (params.has('name') || params.has('email') || params.has('phone') || params.has('type') || params.has(
            'start_date') || params.has('end_date')) {
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