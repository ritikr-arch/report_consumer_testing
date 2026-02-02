@extends('admin.layouts.app') @section('title', @$title) @section('content') <div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-7">
                <h4 class="header-title mb-0 font-weight-bold"> Tips And Advice </h4>
              </div>
              <div class="col-12 col-md-5 col-lg-5 text-end">
                <div>
                  @can('tips_and_advice_create')
                  <div>
                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Tips And Advice">
                      <i class="fa-solid fa-plus"></i>&nbsp; Tips And Advice </button>
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
                    <th>Content</th>
                    <th>Image</th>

                    @can('tips_and_advice_edit')
                    <th>Status</th>
                    @endcan
                    @canany(['tips_and_advice_edit','tips_and_advice_delete'])
                    <th>Action</th>
                    @endcan
                  </tr>
                </thead>
                <tbody> @if(isset($data) && count($data)>0) @foreach($data as $key=>$value) <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ucfirst($value->title)}}</td>
                    <td style="width:350px;">{!! ucfirst($value->content) !!}</td>
                    <td>
                      @if(!empty($value->link))
                      <!-- <a href="{{asset('admin/images/tips_advice/'. $value->link)}}" target="_blank"> View </a> -->
                       <img src="{{asset('admin/images/tips_advice/'. $value->link)}}" alt="{{ $value->title }}" style="width:60px;40px;">
                      @else
                      {{ '-' }}
                      @endif
                    </td>
                     @can('tips_and_advice_edit')
                    <td class="active-bt">
                      <label class="switch">
                        <input type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                      </label>
                    </td>
                    @endcan
                    @canany(['tips_and_advice_edit','tips_and_advice_delete'])

                    <td>
                      <div class="action-btn d-flex">
                        @can('tips_and_advice_edit')
                        <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editcategory" id="{{$value->id}}" alt="edit">
                        @endcan

                        @can('tips_and_advice_delete')
                        <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteTipsAndAdvice" alt="trash">
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
          <h2 id="formHeading">Add Tips And Advice</h2>
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
              <div class="form-group ad-user">
              <label>Upload Image <span class="text-danger">*</span></label>
                <div class="upload-box" id="uploadBox">
                  <span id="fileName">
                    <i class="fa-solid fa-upload"></i>&nbsp;Upload Image </span>
                  <input type="file" id="image" name="document" accept=".jpeg,.png,.jpg">
                </div>
                <span class="font-text">Recommended Dimension -> 1080x1080 Pixels (Max)
                  Image size should not more then 5MB</span><br>
                <span class="text-danger error-document"></span>
              </div>
              <p>
              
                <!-- <a   href="" target="blank" style="display: none;">View Document</a> -->
                 <img  id="editDocument" src="" style="width:60px;40px; display: none;">
               
              <!-- <img id="editDocument" src="" class="editt-img" style="display: none;"> View</p> -->
            </div>
            <div class="col-md-12">
                        <label > Content <span class="text-danger">*</span> </label>
                            <div class="form-group ad-user">
                                <textarea class="form-control oye-f2" name="content" id="tipsandadvice"
                                    placeholder="Content *"></textarea>
                                <span class="text-danger error-content"></span>
                            </div>
                        </div>
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
              <button type="submit" class="btnsss btn-save">Add Tips And Advice</button>
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

// TinyMCE Init
   tinymce.init({
    selector: '#tipsandadvice',
    menubar: true,
    plugins: 'link image code lists table',
    toolbar: 'undo redo | fontfamily fontsize | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link customImageUpload customPDFUpload | code fullscreen',
    height: 400,
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    font_family_formats:
        'Arial=arial,helvetica,sans-serif;' +
        'Courier New=courier new,courier,monospace;' +
        'Georgia=georgia,palatino;' +
        'Tahoma=tahoma,arial,helvetica,sans-serif;' +
        'Times New Roman=times new roman,times;' +
        'Verdana=verdana,geneva;',
    setup: function (editor) {

        // Image Upload Button (Already Present)
        editor.ui.registry.addButton('customImageUpload', {
            text: 'Upload Image',
            icon: 'image',
            onAction: function () {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    const file = this.files[0];
                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('/tinymce-upload.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.location) {
                            editor.insertContent('<img src="' + result.location + '" alt="Uploaded Image">');
                        } else {
                            alert('Upload failed');
                        }
                    })
                    .catch(err => {
                        console.error('Upload error:', err);
                        alert('Image upload error.');
                    });
                };

                input.click();
            }
        });

        // 📄 PDF Upload Button
        editor.ui.registry.addButton('customPDFUpload', {
            text: 'Upload Documents',
            icon: 'new-document',
            onAction: function () {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', '.pdf, .doc, .docx, .xls, .xlsx');


                input.onchange = function () {
                    const file = this.files[0];
                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('/tinymce-pdf-upload.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.location) {
                            editor.insertContent(`<a href="${result.location}" target="_blank">View Documents</a>`);
                        } else {
                            alert('Upload failed');
                        }
                    })
                    .catch(err => {
                        console.error('Upload error:', err);
                        alert('PDF upload error.');
                    });
                };

                input.click();
            }
        });
    }
});



  // Edit announcement modal trigger
  $(document).on('click', '.editcategory', function () {
    const id = $(this).attr('id');
    let url = "{{ route('admin.tips_advice.edit', ':id') }}".replace(':id', id);

    if (id) {
      $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
          if (response.success) {
            console.log(response.data);
            $('#title').val(response.data.title);
            tinymce.get('tipsandadvice').setContent(response.data.content);
            $('#id').val(response.data.id);
            $('#status').val(response.data.status).change();
            $("#editDocument").css('display', 'flex').attr('src', response.data.link);
            $("#formHeading").text('Edit Tips And Advice');
            $(".btnsss").text('Update Tips And Advice');
            modal.show(); // use global instance
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
    let formData = new FormData(this);
    $('.loader').show();
    $('.screen-block').show();

    $.ajax({
      url: "{{ route('admin.tips_advice.save') }}",
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
        if (errors.title) $(".error-title").text(errors.title[0]);
        if (errors.content) $(".error-content").text(errors.content[0]);
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

  // Toggle status switch
  $(".toggleSwitch").on("change", function () {
    const status = $(this).is(":checked") ? 1 : 0;
    const id = $(this).val();

    $.ajax({
      url: "{{ route('admin.tips_advice.status') }}",
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
    $("#formHeading").text('Add Tips And Advice');
    $(".btn-save").text('Add Tips And Advice');
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
@endpush