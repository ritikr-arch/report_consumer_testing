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
                <h4 class="header-title mb-0 font-weight-bold"> Brands </h4>
              </div>


              <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()" data-toggle="tooltip" data-placement="top" title="Filter"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                                    @can('brand_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Brand"><i
                                            class="fa-solid fa-plus"></i>&nbsp; Brands</button>
                                  
                                    <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Import" href="{{route('admin.import.brand')}}"><i
                                            class="fas fa-file-import"></i> Import </a>

                                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Export"
                                        href="{{route('admin.brand.export', request()->query())}}" title=""><i
                                            class="fas fa-file-download"></i> Export</a>
                                            @endcan

                                </div>
                            </div>

            
            </div>
            <div class="row mb-4">
              <form action="{{route('admin.brand.filter')}}" method="get">
                <hr>
                <div id="dropdown" class="dropdown-container-filter">
                  <div class="name-input">
                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="{{ request('name') }}" placeholder="Name">
                  </div>
                  <select class="form-select" name="status" aria-label="Default select example">
                    <option value="" selected="">Status</option>
                    <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active</option>
                    <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive</option>
                  </select>
                  <select class="form-select" name="type_id" aria-label="Select Type">
                      <option value="" selected>Type</option>
                      @foreach($type as $item)
                          <option value="{{ $item->id }}" {{ request('type_id') == $item->id ? 'selected' : '' }}>
                              {{ $item->name }}
                          </option>
                      @endforeach
                  </select>

                  <select class="form-select" name="category_id" aria-label="Select Category">
                      <option value="" selected>Category</option>
                      @foreach($category as $item)
                          <option value="{{ $item->id }}" {{ request('category_id') == $item->id ? 'selected' : '' }}>
                              {{ $item->name }}
                          </option>
                      @endforeach
                  </select>

                  <div class="filter-date">
                    <!-- <label for="start-date">Start Date</label> -->
                    <input type="text" value="{{ request('start_date') }}" name="start_date" class="form-control" placeholder="Start Date" id="start_date" autocomplete="off">
                  </div>
                  <div class="filter-date">
                    <!-- <label for="end-date">End Date</label> -->
                    <input type="text" value="{{ request('end_date') }}" name="end_date" class="form-control" placeholder="End Date" id="end_date" autocomplete="off">
                  </div>
                  <button type="submit" class="d-flex searc-btn">Search</button>
                  <a href="{{ route('admin.brand.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>

                  <!-- <a href="{{route('admin.brand.list')}}">Reset</a> -->
                </div>
              </form>
            </div>
            <div class="table-responsive white-space">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th>S.No.</th>
                      <th>Type</th>
                    <th>Brand Name</th>
                    <th>Categories</th>
                    <th>Created At</th>
                    @can('brand_edit')
                    <th>Status</th>
                    @endcan
                    @canany(['brand_edit','brand_delete'])
                    <th>Action</th>
                    @endcanany
                  </tr>
                </thead>
                <tbody> 
                @if(isset($data) && count($data)>0) 
                @foreach($data as $key=>$value) 
                <tr>
                    <td>{{$key+1}}</td>
                   <td>{{ optional($type->firstWhere('id', $value->type))->name ?? 'N/A' }}</td>
                    <td>{{ucfirst($value->name)}}</td>
                   <td>{{ optional($category->firstWhere('id', $value->category_id))->name ?? 'N/A' }}</td>
                    <td>{{ customt_date_format($value->created_at) }}</td>
                  @can('brand_edit')
                    <td>
                    <label class="switch">
                          <input type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                          <span class="slider round"></span>
                        </label>
                      </td>
                   @endcan
                    @canany(['brand_edit','brand_delete'])
                    <td>
                      <div class="action-btn">
                        @can('brand_edit')
                        <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory" id="{{$value->id}}" alt="edit">
                        @endcan

                        @can('brand_delete')
                        <img data-toggle="tooltip" data-placement="top" title="Delete"  src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteBrand" alt="trash">
                        @endcan
                      </div>
                    </td>
                    @endcanany
                </tr>
              	@endforeach 
                @else
                <tr>
                    <td colspan="5">
                        <p class="no_data_found">No Data Found! </p>
                    </td>
                </tr>
          		@endif 
                </tbody>
              </table> 
              @if (isset($data)) {{ $data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
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
<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->
</div>
<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="formHeading">Add Brand</h2>
          <p>Please enter the following Data</p>
        </div>
        <form method="post" id="brand" class="mt-4" > 
          @csrf 
          <div class="loader"></div>
          <div class="screen-block"></div>

          <div class="row">
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
                      <span class="text-danger error-type"></span>
                  </div>
              </div>
          </div>
          <div class="col-md-6">
              <label>Select categories <span class="text-danger">*</span></label>
              <div class="form-group ad-user">
                  <div class="rela-icon">
                      <select class="form-control" id="category_id" name="category_id">
                          <option value="" hidden>Select Categories</option>
                          
                      </select>
                      <i class="fa-solid fa-caret-down"></i>
                      <span class="text-danger error-category"></span>
                  </div>
              </div>
          </div>
            <div class="col-md-6">
            <label > Brand name <span class="text-danger">*</span></label>
              <div class="form-group ad-user">
                <input type="hidden" id="id" value="" name="id">
                <input type="text" class="form-control" id="name" name="name" >
                <span class="text-danger error-name"></span>
              </div>
            </div>
            <div class="col-md-6">
            <label > Select Status <span class="text-danger">*</span></label>
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
              <button type="submit" class="btnss btn-save">Add Brand</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> 
<!-- Modal -->
<div class="modal fade" id="typeRequiredModal" tabindex="-1" aria-labelledby="typeRequiredModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header">
        <h5 class="modal-title" id="typeRequiredModalLabel">Missing Selection</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Please select a type first before choosing a category.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="focusTypeBtn">Select Type</button>
      </div>
    </div>
  </div>
</div>

@endsection 
@push('scripts') 

<script>
let editCategoryId = null; // Global scope

$(document).ready(function () {
  var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
    backdrop: 'static',
    keyboard: false
  });

  // 🔁 Reset modal on close
  $('#exampleModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#id').val('');
    $("#editImage").hide().attr('src', '');
    $("#formHeading").text('Add Brand');
    $(".btnss").text('Add Brand');
    window.location.reload();
    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
  });

$('#type_id').on('change', function () {
    const typeId = $(this).val();

    if (typeId) {
        $.ajax({
            url: '{{ route("admin.brand.get-categories-by-type") }}',
            type: 'GET',
            data: { type_id: typeId },
            success: function (response) {
                $('#category_id').prop('disabled', false);
                $('#category_id').empty(); // Clear existing options

                if (response.length > 0) {
                    $('#category_id').append('<option value="" hidden>Select Categories</option>');
                    $.each(response, function (key, value) {
                        $('#category_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                    // ✅ Set category if in edit mode
                    if (editCategoryId) {
                        $('#category_id').val(editCategoryId);
                        editCategoryId = null;
                    }
                } else {
                    // ⛔ No categories available
                    $('#category_id').append('<option value="">No Categories Found</option>');
                }
            },
            error: function () {
                alert('Failed to fetch categories.');
            }
        });
    } else {
        $('#category_id').empty().append('<option value="" hidden>Select Categories</option>');
    }
});




  // 🔁 Category requires type
 $('#category_id').on('focus', function () {
    if (!$('#type_id').prop('disabled')) {
        const typeId = $('#type_id').val();
        if (!typeId) {
            $('.error-category').text('Please select type first');
            $('#type_id').focus();
           $('#category_id').prop('disabled', true);

        } else {
            $('.error-category').text(''); // Clear error if type is selected
        }
    }
});


$(document).on('click', '.editcategory', function (e) {
    const id = $(this).attr('id');
    const url = "{{ route('admin.brand.edit', ':id') }}".replace(':id', id);

    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                if (response.success) {
                    // Fill brand fields
                    $('#name').val(response.data.name);
                    $('#id').val(response.data.id);
                    $('#status').val(response.data.status).change();

                    // Set category ID globally for use after categories are loaded
                    editCategoryId = response.data.category_id;

                    // Set type (this triggers category load)
                    $('#type_id').val(response.data.type).change();

                    // Update UI
                    $("#formHeading").text('Edit Brand');
                    $(".btnss").text('Update Brand');
                    $('#exampleModal').modal('show');
                }
            },
            error: function (xhr) {
                console.log('Edit fetch error:', xhr.responseJSON);
            }
        });
    }
});


  // ✅ Save Brand Form
  $('#brand').on('submit', function (e) {
    e.preventDefault();

    const typeId = $('#type_id').val();
    const categoryId = $('#category_id').val();
    let hasError = false;

    if (!typeId) {
      $(".error-type").text("Please select a type.");
      $('#type_id').focus();
      hasError = true;
    } else if (!categoryId) {
      $(".error-category").text("Please select a category.");
      $('#category_id').focus();
      hasError = true;
    }

    if (hasError) return;

    let formData = new FormData(this);
    formData.append('type_id', typeId);
    formData.append('category_id', categoryId);

    $('.loader').show();
    $('.screen-block').show();

    $.ajax({
      url: "{{ route('admin.brand.save') }}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function () {
        $('.text-danger').text('');
      },
      success: function (response) {
        if (response.success) {
          toastr.success(response.message);
          modal.hide();
          location.reload();
        }
      },
      error: function (xhr) {
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
      complete: function () {
        $('.loader').hide();
        $('.screen-block').hide();
      }
    });
  });
  
});

 $(document).ready(function() {
     $(".toggleSwitch").on("change", function() {
          var status = $(this).is(":checked") ? 1 : 0;
          var id = $(this).val();
          $.ajax({
            url: "{{ route('admin.brand.update.status') }}",
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
            $dropdownButton.html(selectedValue + '  < i class = "fa fa-caret-down" > < /i>');
              $dropdownMenu.hide();
            });
          // Close dropdown when clicking outside
          $(document).on('click', function(e) {
            if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
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
            if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
              $dropdownMenu.hide();
            }
          });
          // Update dropdown button text based on selected items
          $checkboxes.on('change', function() {
            const selectedOptions = $checkboxes.filter(':checked').map(function() {
              return $(this).val();
            }).get();
            $dropdownButton.html(selectedOptions.length > 0 ? selectedOptions.join(', ') + '  < i class = "fa fa-caret-down" > < /i>' : 'Select Options  < i class = "fa fa-caret-down" > < /i>');
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
</script>



<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

 <script>
  $( function() {
    $( "#start_date" ).datepicker();
    $( "#end_date" ).datepicker();
  } );
  </script>

@endpush