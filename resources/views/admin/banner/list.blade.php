@extends('admin.layouts.app') @section('title', @$title) @section('content') <div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-xl-12">
                <div class="card">

                    <div class="card-body">
                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-xl-5">
                                <h4 class="header-title mb-0 font-weight-bold"> Banner </h4>
                            </div>

                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">

                                    @can('banner_create')
                                    <button data-toggle="tooltip" data-placement="top" title="Create Banner" class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="fa-solid fa-plus"></i>&nbsp; Banner </button>
                                    @endcan

                                    <!-- <a class="btn btn-info btn-sm" href="{{route('admin.banner.heading')}}"><i class="fa-solid fa-plus"></i>&nbsp; Heading  </a> -->



                                </div>
                            </div>
                        </div>

                        

                        <div class="table-responsive white-space">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="border-b bg-light2">
                                        <th style="width:15%;">S.No.</th>
                                        <th style="width:15%;">Type</th>
                                        <th style="width:15%;">Attachments</th>
                                     
                                        @can('banner_edit')
                                        <th style="width:15%;">Status</th>
                                        @endcan
                                        @canany(['banner_edit','banner_delete'])
                                        <th style="width:15%;">Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ isset($value->type) ? $value->type : ''; }}</td>
                                        <td>
                                            @if(!empty($value->image) && $value->type == "image")
                                            <div class="market-img">
                                                <img style="height: 40px;width: 40px;border-radius: 25%"
                                                    src="{{asset('admin/images/banner/'.$value->image)}}" alt="market">
                                            </div>
                                            
                                            @endif
                                             @if(!empty($value->video) && $value->type == "video")
                                            <div class="market-img">
                                                <a href="{{ url('admin/videos/banner/'.$value->video) }}" target="_blank">View</a>
                                            </div>
                                            
                                            @endif
                                        </td>
                                       
                                        @can('banner_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        @endcan
                                        @canany(['banner_edit','banner_delete'])
                                        <td>
                                            <div class="action-btn">
                                                @can('banner_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit"
                                                    src="{{asset('admin/img/edit-2.png')}}" class="editBnaaer"
                                                    id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('banner_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete"
                                                    src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteBanner" alt="trash">
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
                            {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
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
<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="formHeading">Add New Banner</h2>
          <p>Please enter the following Data</p>
        </div>
        <form method="post" class="mt-4" id="banner"> @csrf <div class="loader"></div>
          <div class="screen-block"></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label>Upload Type <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select class="form-control" id="upload_type" name="upload_type">
                    <option value="" selected disabled>Select Type</option>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-upload_type"></span>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="imageUploadSection">
              <input type="hidden" id="id" value="" name="id">
              <div class="form-group ad-user">
                <label>Upload Image <span class="text-danger">*</span>
                </label>
                <div class="upload-box form-control" id="uploadBox">
                  <span id="fileName">
                    <i class="fa-solid fa-upload"></i>&nbsp;Upload Image * </span>
                  <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <span class="text-danger error-image"></span>
                <p style="font-size: 11px; font-weight: 700;"> Recommended Dimension -> 3850x1570 Pixels (Max) </br> Image size should not more then 5MB </p>
              </div>
              <img id="editImage" src="" class="editt-img" style="display: none;">
            </div>
            <div class="col-md-6" id="videoUploadSection" >
                <label>Upload Video <span class="text-danger">*</span></label>
                <div class="upload-box form-control" id="uploadBox">
                    <span id="fileName">
                      <i class="fa-solid fa-upload"></i>&nbsp;Upload Video * </span>
                    <input type="file" class="form-control" id="video" name="video" accept="video/*">
                </div>
                <span class="text-danger error-video"></span>
                <p style="font-size: 11px; font-weight: 700;">Recommended Format: MP4, Max size: 10MB</p>
            </div>
            <div class="col-md-6">
              <div class="form-group ad-user">
                <label>Select Status <span class="text-danger">*</span>
                </label>
                <div class="rela-icon">
                  <select class="form-control" id="status" name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-status"></span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btnsss btn-save">Add Banner</button>
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

$(document).ready(function () {
  $('#upload_type').on('change', function () {
    const selectedType = $(this).val();

    if (selectedType === 'image') {
      $('#imageUploadSection').show();
      $('#videoUploadSection').hide();
    } else if (selectedType === 'video') {
      $('#imageUploadSection').hide();
      $('#videoUploadSection').show();
    }
  });
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


$(document).on('click', '.editBnaaer', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).attr('id');
    var url = "{{ route('admin.banner.edit', ':id') }}";
    url = url.replace(':id', id);
    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
            
                if (response.success) 
                {
                    $('#id').val(response.data.id);
                    $('#upload_type').val(response.data.type).change();
                    $('#status').val(response.data.status).change();

                    if (response.data.type === 'video') 
                    {
                        $("#editImage").hide(); 
                    } 
                    else {
                        $("#editImage").css('display', 'flex');
                        $("#editImage").attr('src', response.data.image);
                    }

                    $("#formHeading").text('Edit Banner');
                    $(".btnsss").text('Update Banner');
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
    $('#banner').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $('.loader').show();
        $('.screen-block').show()
        $.ajax({
            url: "{{ route('admin.banner.save') }}",
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
                console.log(errors);
                $(".text-danger").text("");
                // if (errors.title) {
                //     $(".error-title").text(errors.title[0]);
                // }
                if (errors.upload_type) {
                    $(".error-upload_type").text(errors.upload_type[0]);
                }
                if (errors.status) {
                    $(".error-status").text(errors.status[0]);
                }
                if (errors.image) {
                    $(".error-image").text(errors.image[0]);
                }

                if (errors.video) {
                    $(".error-video").text(errors.video[0]);
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


    document.getElementById('image').addEventListener('change', function () {
        const file = this.files[0];
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes

        if (file && file.size > maxSize) {
          //alert('File is too large. Max size is 5MB.');
          $(".error-image").text('File is too large. Max size is 5MB.');
          this.value = ''; // Clear the input
        }
    });

    document.getElementById('video').addEventListener('change', function () {
        const file = this.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB in bytes

        if (file && file.size > maxSize) {
          $(".error-video").text('File is too large. Max size is 10MB.');
          this.value = ''; // Clear the input
        }
    });



    $(".toggleSwitch").on("change", function() {
        var status = $(this).is(":checked") ? 1 : 0;
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.banner.update.status') }}",
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
        $("#formHeading").text('Add New Banner');
        $(".btn-save").text('Add Banner');
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
            $dropdownButton.html(selectedValue + '  < i class = "fa fa-caret-down" > < /i>');
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
        $dropdownButton.html(selectedOptions.length > 0 ? selectedOptions.join(', ') +
            '  < i class = "fa fa-caret-down" > < /i>' :
            'Select Options  < i class = "fa fa-caret-down" > < /i>');
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

@endpush