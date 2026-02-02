@extends('admin.layouts.app') @section('title', @$title) @section('content') <div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-7">
                <h4 class="header-title mb-0 font-weight-bold"> Consumer Corner </h4>
              </div>
              <div class="col-12 col-md-5 col-lg-5 text-end">
                <div>
                  @can('consumer_corner_create')
                  <div>
                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Consumer Corner">
                      <i class="fa-solid fa-plus"></i>&nbsp; Consumer Corner </button>
                  </div>
                  @endcan
                </div>
              </div>
            </div>
            <div class="table-responsive white-space">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th>S.No.</th>
                    <th>Title</th>
                    <th>Document</th>
                    @can('consumer_corner_edit')
                    <th>Status</th>
                    @endcan
                    @canany(['consumer_corner_edit','consumer_corner_delete'])
                    <th>Action</th>
                    @endcan
                  </tr>
                </thead>
                <tbody> @if(isset($data) && count($data)>0) @foreach($data as $key=>$value) <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ucfirst($value->title)}}</td>
                    <td>
                      @if(!empty($value->link))
                      <a href="@if($value['type'] === 'video_external' || $value['type'] === 'link')
                            {{ $value['link'] }}
                        @else
                            {{ asset('admin/images/consumer_corner/' . $value['link']) }}
                        @endif
                        " target="_blank"> View </a>
                      @else
                      {{ '-' }}
                      @endif
                    </td>
                    @can('consumer_corner_edit')
                    <td class="active-bt">
                      <label class="switch">
                        <input type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                      </label>
                    </td>
                     @endcan
                     @canany(['consumer_corner_edit','consumer_corner_delete'])

                    <td>
                      <div class="action-btn">
                        @can('consumer_corner_edit')
                        <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory" id="{{$value->id}}" alt="edit">
                        @endcan

                        @can('consumer_corner_edit')
                        <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteConsumerCorner" alt="trash">
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
              @if (isset($data)) {{ @$data->appends(request()->query())->links('pagination::bootstrap-5') }} @endif
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
        <button type="button" class="btn-close"  data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="formHeading">Add Consumer Corner</h2>
          <p>Please enter the following Data</p>
        </div>
        <form method="post" class="mt-4" id="category"> 
          @csrf 
          <div class="loader"></div>
          <div class="screen-block"></div>
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" id="id" value="" name="id">
              <label>Title <span class="text-danger">*</span></label>
              <div class="form-group ad-user">
                <input type="text" class="form-control" name="title" id="title">
                <span class="text-danger error-title"></span>
              </div>
            </div>
              <div class="col-md-6">
                
                  <label>Type <span class="text-danger">*</span></label>
                  <div class="form-group ad-user">
                    <div class="rela-icon">
                   <select class="form-control" name="type" id="type">
                      <option value="image" selected>Image</option>
                      <option value="video_external"> External - Video</option>
                      <option value="video_internal">Internal - Video</option>
                      <option value="link">Link</option>
                    </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-type"></span>
                </div>
                    <span class="text-danger error-type"></span>
                  </div>
                </div>

                <!-- Upload Area -->
                <div class="col-md-6" id="uploadArea">
                  <div class="form-group ad-user">
                    <label id="uploadLabel">Upload Image <span class="text-danger">*</span></label>
                    <div class="upload-box" id="uploadBox">
                      <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload File</span>
                      <input type="file" id="document" name="document" accept=".jpeg,.jpg,.png,.webp">
                    </div>
                    <span class="file-note font-text">Only .jpeg, .jpg, .png, .webp files. Max size: 2 MB.</span><br>
                    <span class="text-danger error-document"></span>
                    <a id="editDocumentLink" href="" target="_blank" style="display: none; margin-top: 10px;">View Document</a>
                    <img id="editDocumentImg" src="" class="editt-img" style="display: none; max-width: 100%; margin-top: 10px;" />
                  </div>
                </div>

                <!-- YouTube Link Input -->
                <div class="col-md-6" id="youtubeLinkArea" style="display: none;">
                  <div class="form-group ad-user">
                    <label>YouTube Link <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" name="youtube_url" id="youtube_url">
                    <span class="text-danger error-youtube_url"></span>
                  </div>
                </div>

                <!-- Link Input -->
                <div class="col-md-6" id="linkInputArea" style="display: none;">
                  <div class="form-group ad-user">
                    <label>Enter Link <span class="text-danger">*</span></label>
                    <input type="url" class="form-control" name="link_url" id="link_url">
                    <span class="text-danger error-link_url"></span>
                  </div>
                </div>
            <!-- <div class="col-md-6">
              <div class="form-group ad-user">
              <label>Upload Document <span class="text-danger">*</span></label>
                <div class="upload-box" id="uploadBox">
                  <span id="fileName">
                    <i class="fa-solid fa-upload"></i>&nbsp;Upload Document </span>
                  <input type="file" id="image" name="document" accept=".pdf,.doc,.docx">
                </div>
                <span>Only .pdf, .docx and .doc are accepted. Maximum file size: 2 MB.</span><br>
                <span class="text-danger error-document"></span>
              </div>
              <p>
                <a  id="editDocument" href="" style="display: none;">View Document</a>
            <img id="editDocument" src="" class="editt-img" style="display: none;"> View</p> 
            </div> -->
            <div class="col-md-6">
              <div class="form-group ad-user">
              <label>Select Status <span class="text-danger">*</span></label>
                <div class="rela-icon">
                  <select class="form-control" id="status" name="status">
                    <option value="" selected disabled>Select Status</option>
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                  </select>
                  <i class="fa-solid fa-caret-down"></i>
                  <span class="text-danger error-status"></span>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btnsss btn-save">Add Consumer Corner</button>
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
  
$(document).ready(function () {
  function resetUploadAreas() {
  $('#uploadArea').hide();
  $('#youtubeLinkArea').hide();
  $('#linkInputArea').hide();
}
  function showAreaByType(type) {
  resetUploadAreas(); // hide all first

  if (type === "image") {
    $('#uploadArea').show();
    $('#uploadInput').attr('accept', '.jpeg,.jpg,.png,.webp');
    $('#uploadLabel').text('Upload Image');
    $('#fileNote').text('Only .jpeg, .jpg, .png, .webp files. Max size: 2 MB.');
  } else if (type === "video_internal") {
    $('#uploadArea').show();
    $('#uploadInput').attr('accept', '.mp4,.avi,.mov');
    $('#uploadLabel').text('Upload Video');
    $('#fileNote').text('Only .mp4, .avi, .mov files. Max size: 10 MB.');
  } else if (type === "video_external") {
    $('#youtubeLinkArea').show();
  } else if (type === "link") {
    $('#linkInputArea').show();
  }
}
  // Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});
  const modalEl = document.getElementById('exampleModal');
  const modal = new bootstrap.Modal(modalEl, {
    backdrop: 'static',
    keyboard: false
  });
$('.btn-close').on('click', function () {

   modal.hide();
});

  // File input handler
  const fileInput = document.getElementById("image");
  const fileNameDisplay = document.getElementById("fileName");
  if (fileInput && fileNameDisplay) {
    fileInput.addEventListener("change", (event) => {
      const file = event.target.files[0];
      fileNameDisplay.textContent = file ? file.name : "Upload Image";
    });
  }

  // Edit announcement modal trigger
 $(document).on('click', '.editcategory', function () {
  const id = $(this).attr('id');
  let url = "{{ route('admin.consumer_corner.edit', ':id') }}".replace(':id', id);

  if (id) {
    $.ajax({
      url: url,
      type: "GET",
      success: function (response) {
        if (response.success) {
          // 👇 YEH PART YAHAN AAYEGA
          // Clear preview areas first
          $("#editDocumentLink").hide().attr('href', '').text('');
          $("#editDocumentImg").hide().attr('src', '');

          if (response.data.type === "image" || response.data.type === "video_internal") {
            if(response.data.link){
              const link = response.data.link;
              const ext = link.split('.').pop().toLowerCase();

              if(['jpeg','jpg','png','webp','gif'].includes(ext)){
                // Show image preview
                $("#editDocumentImg").attr('src', link).show();
              } else {
                // Show document/video link
                $("#editDocumentLink").attr('href', link).text('View Document').show();
              }
            }
          } else if(response.data.type === "video_external") {
            
            $('#youtube_url').val(response.data.link || '');
          } else if(response.data.type === "link") {
         
            $('#link_url').val(response.data.link || '');
          }

          // Other fields fill
          $('#title').val(response.data.title);
          $('#id').val(response.data.id);
          $('#type').val(response.data.type).change();
          showAreaByType(response.data.type);
          $('#status').val(response.data.status).change();

          $("#formHeading").text('Edit Consumer Corner');
          $(".btnsss").text('Update Consumer Corner');
          modal.show();
        }
      },
      error: function (xhr) {
        console.error(xhr.responseJSON.errors);
      }
    });
  }
});

  // Form submission
$('#category').on('submit', function (e) {
    e.preventDefault();

    // Clear previous errors
    $(".text-danger").text("");

    let isValid = true;

    // Form values
    let type = $('#type').val();
    let title = $('#title').val().trim();
    let status = $('#status').val();
    let document = $('#document')[0]?.files[0];
    let youtubeUrl = $('#youtube_url').val();
    let linkUrl = $('#link_url').val();

    // Create form data and detect edit mode from ID
    let formData = new FormData(this);
    let isEdit = formData.get('id'); // edit mode if id exists

    // Title: required, max 250
    if (!title) {
        $(".error-title").text("The title field is required.");
        isValid = false;
    } else if (title.length > 250) {
        $(".error-title").text("The title may not be greater than 250 characters.");
        isValid = false;
    }

    // Status: required, must be 0 or 1
    if (status !== '0' && status !== '1') {
        $(".error-status").text("The status field is required.");
        isValid = false;
    }

    // Type: required and valid
    const validTypes = ['image', 'video_internal', 'video_external', 'link'];
    if (!validTypes.includes(type)) {
        $(".error-type").text("The type field is invalid.");
        isValid = false;
    }

    // Document validation based on type
    if (type === 'image') {
        if (!document && !isEdit) {
            $(".error-document").text("The image is required.");
            isValid = false;
        } else if (document && !['image/jpeg', 'image/jpg', 'image/png', 'image/webp'].includes(document.type)) {
            $(".error-document").text("Only jpeg, jpg, png, or webp files are allowed.");
            isValid = false;
        } else if (document && document.size > 2 * 1024 * 1024) {
            $(".error-document").text("Image must be less than 2MB.");
            isValid = false;
        }
    }

    if (type === 'video_internal') {
        if (!document && !isEdit) {
            $(".error-document").text("The video file is required.");
            isValid = false;
        } else if (document && !['video/mp4', 'video/avi', 'video/quicktime'].includes(document.type)) {
            $(".error-document").text("Only mp4, avi, or mov files are allowed.");
            isValid = false;
        } else if (document && document.size > 10 * 1024 * 1024) {
            $(".error-document").text("Video must be less than 10MB.");
            isValid = false;
        }
    }

    if (type === 'video_external') {
        if (!youtubeUrl || !isValidURL(youtubeUrl)) {
            $(".error-document").text("A valid YouTube URL is required.");
            isValid = false;
        }
    }

    if (type === 'link') {
        if (!linkUrl || !isValidURL(linkUrl)) {
            $(".error-document").text("A valid URL is required.");
            isValid = false;
        }
    }

    // If not valid, stop submission
    if (!isValid) return;

    // Proceed with AJAX
    $('.loader').show();
    $('.screen-block').show();

    $.ajax({
        url: "{{ route('admin.consumer_corner.save') }}",
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
            if (errors.title) $(".error-title").text(errors.title[0]);
            if (errors.type) $(".error-type").text(errors.type[0]);
            if (errors.status) $(".error-status").text(errors.status[0]);
            if (errors.document) $(".error-document").text(errors.document[0]);
            modal.show();
        },
        complete: function () {
            $('.loader').hide();
            $('.screen-block').hide();
        }
    });
});



// Helper: Validate URL
function isValidURL(url) {
    let pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '(([\\da-z.-]+)\\.([a-z.]{2,6})|([0-9]{1,3}\\.){3}[0-9]{1,3})'+ // domain name or IP
        '(\\:[0-9]{1,5})?'+ // port
        '(\\/[-a-z\\d%_.~+]*)*'+ // path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i');
    return !!pattern.test(url);
}


  // Toggle status switch
  $(".toggleSwitch").on("change", function () {
    const status = $(this).is(":checked") ? 1 : 0;
    const id = $(this).val();

    $.ajax({
      url: "{{ route('admin.consumer_corner.status') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        status: status,
        id: id
      },
      success: function (response) {
        if (response.success) {
          toastr.success(response.message);
        }
      },
      error: function (xhr) {
        toastr.error("Failed to update status.");
      }
    });
  });

  // Reset modal on close
  $('#exampleModal').on('hidden.bs.modal', function () {
    $(this).find('form')[0].reset();
    $('#id').val('');
    $("#editDocument").hide().attr('href', '');
    $("#formHeading").text('Add Consumer Corner');
    $(".btn-save").text('Add Consumer Corner');
    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
  });

  // Initialize single dropdowns
  function setupDropdown(dropdownButtonId) {
    const $button = $('#' + dropdownButtonId);
    const $menu = $button.next();
    const $items = $menu.find('.dropdown-item');

    $button.on('click', function () {
      $menu.toggle();
    });

    $items.on('click', function () {
      const selectedValue = $(this).data('value');
      $button.html(selectedValue + ' <i class="fa fa-caret-down"></i>');
      $menu.hide();
    });

    $(document).on('click', function (e) {
      if (!$button.is(e.target) && !$menu.is(e.target) && $menu.has(e.target).length === 0) {
        $menu.hide();
      }
    });
  }

  // Initialize multiple dropdowns
  setupDropdown('dropdownButton1');
  setupDropdown('dropdownButton2');
  setupDropdown('dropdownButton3');
  setupDropdown('dropdownButton4');

  // Multi-select dropdown
  const $multiButton = $('#dropdownButton');
  const $multiMenu = $('#dropdownMenu');
  const $checkboxes = $multiMenu.find("input[type='checkbox']");

  $multiButton.on('click', function () {
    $multiMenu.toggle();
  });

  $(document).on('click', function (e) {
    if (!$multiButton.is(e.target) && !$multiMenu.is(e.target) && $multiMenu.has(e.target).length === 0) {
      $multiMenu.hide();
    }
  });

  $checkboxes.on('change', function () {
    const selected = $checkboxes.filter(':checked').map(function () {
      return $(this).val();
    }).get();
    $multiButton.html(
      selected.length > 0
        ? selected.join(', ') + ' <i class="fa fa-caret-down"></i>'
        : 'Select Options <i class="fa fa-caret-down"></i>'
    );
  });

  // Toggle advanced filter dropdown
  function toggleDropdown() {
    document.getElementById("dropdown").classList.toggle("active");
  }

  // Show dropdown if filter params exist
  (function () {
    const params = new URLSearchParams(window.location.search);
    if (params.has('name') || params.has('start_date') || params.has('status') || params.has('end_date')) {
      document.getElementById("dropdown").classList.add("active");
    }
  })();
});

</script> 

<script>
  const typeSelect = document.getElementById("type");
  const uploadArea = document.getElementById("uploadArea");
  const youtubeLinkArea = document.getElementById("youtubeLinkArea");
  const linkInputArea = document.getElementById("linkInputArea");
  const uploadInput = document.getElementById("document");
  const uploadLabel = document.getElementById("uploadLabel");
  const fileNote = document.querySelector(".file-note");

  // Function to update display based on selected type
  function updateFields(type) {
    // Hide all first
    uploadArea.style.display = "none";
    youtubeLinkArea.style.display = "none";
    linkInputArea.style.display = "none";
    uploadInput.accept = "";
    uploadInput.value = "";
    fileNote.innerText = "";

    // Show based on selected type
    if (type === "image") {
      uploadArea.style.display = "block";
      uploadInput.accept = ".jpeg,.jpg,.png,.webp";
      uploadLabel.innerText = "Upload Image";
      fileNote.innerText = "Only .jpeg, .jpg, .png, .webp files. Max size: 2 MB.";
    } else if (type === "video_internal") {
      uploadArea.style.display = "block";
      uploadInput.accept = ".mp4,.avi,.mov";
      uploadLabel.innerText = "Upload Video";
      fileNote.innerText = "Only .mp4, .avi, .mov files. Max size: 10 MB.";
    } else if (type === "video_external") {
      youtubeLinkArea.style.display = "block";
    } else if (type === "link") {
      linkInputArea.style.display = "block";
    }
  }

  // Initialize based on default selected
  updateFields(typeSelect.value);

  // Listen to change
  typeSelect.addEventListener("change", function () {
    updateFields(this.value);
  });
</script>
@endpush