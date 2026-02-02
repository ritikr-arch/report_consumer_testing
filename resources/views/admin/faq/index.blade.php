@extends('admin.layouts.app') 
@section('title', @$title) 

@section('content') 
<style>
.page-content 
{
   height: 100vh;
   overflow-y: scroll;
}
.modal-body {
  overflow: auto;
  position: relative;
}

.tox-tinymce-aux {
  z-index: 1060 !important; /* above modal */
}

  .modal-body.no-scroll {
    overflow: hidden !important;
  }
/* .tox-color-picker {
  z-index: 1060 !important; /* higher than Bootstrap modal */
  position: fixed !important; /* Prevents it from shifting with scrolling */
} */
.tox.tox-silver-sink .tox-picker,
.tox.tox-silver-sink .tox-menu,
.tox.tox-silver-sink .tox-swatches {
  position: fixed !important;
  z-index: 1065 !important; /* higher than Bootstrap modal */
}
.no-scroll {
  overflow: hidden !important;
}

</style>
<div class="px-3">
  <!-- Start Content-->
  <div class="container-fluid">
    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center d-flex mb-3">
              <div class="col-xl-7">
                <h4 class="header-title mb-0 font-weight-bold"> FAQ </h4>
              </div>
              <div class="col-12 col-md-5 col-lg-5 text-end">
                <div>
                  <div>
                    @can('faq_create')
                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create FAQ">
                      <i class="fa-solid fa-plus"></i>&nbsp; FAQ </button>
                       <a class="btn btn-info btn-sm" href="{{route('admin.faq.type.list')}}"><i class="fa-solid fa-plus"></i>&nbsp; FAQ Type </a>
                    @endcan
                   
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive white-space">
              <table class="table table-hover mb-0">
                <thead>
                  <tr class="border-b bg-light2">
                    <th style="min-width:10px;">S.No.</th>
                    <th style="min-width:10px;">Type</th>
                    <th style="min-width:80px;">Question</th>
                    <th style="min-width:100px;">Answer</th>
                    @can('faq_edit')
                    <th style="min-width:80px;">Status</th>
                    @endcan

                    @canany(['faq_edit','faq_delete'])
                    <th style="min-width:120px;">Action</th>
                    @endcanany
                  </tr>
                </thead>
                <tbody>
                  @if(isset($data) && count($data)>0)
                  @foreach($data as $key=>$value)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{ strip_tags(ucfirst($value->types?->type ?? 'NA')) }}</td>
                    <td>{{ html_entity_decode(strip_tags(ucfirst($value->title)) )}}</td>
                    <td>{{ html_entity_decode(strip_tags($value->description)) }}</td>
                    @can('faq_edit')
                    <td class="active-bt">
                      <label class="switch">
                        <input type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                      </label>
                    </td>
                    @endcan
                    @canany(['faq_edit','faq_delete'])
                    <td>
                      <div class="action-btn">
                        @can('faq_edit')
                        <img data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editFaq" id="{{$value->id}}" alt="edit">
                        @endcan

                        @can('faq_delete')
                        <img data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteFaq" alt="trash">
                        @endcan
                      </div>
                    </td>
                    @endcanany
                  </tr>
                  @endforeach
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
</div>
<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="heading">
          <h2 id="formHeading">Add Faq</h2>
          <p>Please enter the following Data</p>
        </div>
        <div class="container mt-5">
  <form method="post" id="zone" class="mt-4">
    @csrf
    <div class="loader"></div>
    <div class="screen-block"></div>

    <input type="hidden" id="id" value="" name="id">

    <div class="row">
      <div class="col-md-12" id="question-field"> <!-- Used for scroll target -->
        <div class="form-group ad-user">
          <label>Question <span class="text-danger">*</span> </label>
          <textarea class="form-control oye-f2" name="title" id="title"></textarea>
          <span class="text-danger error-title"></span>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group ad-user">
          <label>Answer <span class="text-danger">*</span></label>
          <textarea class="form-control oye-f2" name="description" id="description"></textarea>
          <span class="text-danger error-description"></span>
        </div>
      </div>

      <div class="col-md-6">
        <label>Select Type <span class="text-danger">*</span> </label>
        <div class="form-group ad-user">
          <div class="rela-icon">
           <select class="form-control" id="type_id" name="type_id">
    <option value="" selected disabled>Select type</option>
    @foreach($faqtype as $type)
        <option value="{{ $type->id }}">{{ $type->type }}</option>
    @endforeach
</select>

            <i class="fa-solid fa-caret-down"></i>
            <span class="text-danger error-type_id"></span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <label>Status <span class="text-danger">*</span> </label>
        <div class="form-group ad-user">
          <div class="rela-icon">
            <select class="form-control" id="status" name="status">
              <option value="" selected disabled>Choose Status </option>
              <option value="1">Active</option>
              <option value="0">Deactive</option>
            </select>
            <i class="fa-solid fa-caret-down"></i>
            <span class="text-danger error-status"></span>
          </div>
        </div>
      </div>

      <div class="text-center col-12">
        <button type="submit" class="btn btn-primary btn-save btnss">Submit</button>
      </div>
    </div>
  </form>
</div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
   <script src="https://cdn.tiny.cloud/1/8ts5mbr9hypz93cm48toaybsggxg80y362fobzt0q1gcqe4l/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>


$(document).on('click', '.editFaq', function(e) {
  var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
    backdrop: 'static',
    keyboard: false
  });

  var id = $(this).attr('id');
  var url = "{{ route('admin.faq.edit', ':id') }}";
  url = url.replace(':id', id);
  if (id) {
    $.ajax({
      url: url,
      type: "GET",
      success: function(response) {
        if (response.success) {
          $('#id').val(response.data.id);
          $('#title').val(response.data.title);
        $('#description').val(response.data.description);
          $('#status').val(response.data.status).change();
          $('#type_id').val(response.data.type_id);
          $("#editImage").css('display', 'flex')
          $("#editImage").attr('src', response.data.image)
          $("#formHeading").text('Edit FAQ')
          $(".btnss").text('Update FAQ')
          $('#exampleModal').modal('show');
        }
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
      }
    });
  }
});

$('#exampleModal').on('shown.bs.modal', function () {
  // 🔸 Remove existing TinyMCE editors if already initialized
  tinymce.remove('#title, #description');

  // 🔸 Init TinyMCE inside the modal
  tinymce.init({
    selector: '#title, #description',
    menubar: true,
    plugins: 'code link lists table fullscreen fontfamily fontsize',
    toolbar:
      'undo redo | fontfamily fontsize | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link customImageUpload | code fullscreen',
    height: 400,
    fixed_toolbar_container: '.modal-body',

    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    font_family_formats:
      'Arial=arial,helvetica,sans-serif;' +
      'Courier New=courier new,courier,monospace;' +
      'Georgia=georgia,palatino;' +
      'Tahoma=tahoma,arial,helvetica,sans-serif;' +
      'Times New Roman=times new roman,times;' +
      'Verdana=verdana,geneva;',

    setup: function (editor) {
      // ✅ Custom Image Upload Button
      editor.ui.registry.addButton('customImageUpload', {
        text: 'Upload Image',
        icon: 'image',
        onAction: () => {
          const input = document.createElement('input');
          input.type = 'file';
          input.accept = 'image/*';
          input.onchange = () => {
            const file = input.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('file', file);

            fetch('/tinymce-upload.php', {
              method: 'POST',
              body: formData,
            })
              .then(res => (res.ok ? res.json() : Promise.reject(res)))
              .then(data => {
                if (data.location) {
                  editor.insertContent(`<img src="${data.location}" alt="Uploaded Image">`);
                } else {
                  alert('Upload failed.');
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

      // ✅ Highlight clickable elements inside editor
      editor.on('Click', function (e) {
        const target = e.target;
        const color = target.style.color;
        const bgColor = target.style.backgroundColor;

        if (color || bgColor || target.tagName === 'IMG' || target.tagName === 'A') {
          navigateToQuestionField?.(); // optional chaining in case function not defined
        }
      });

      // ✅ Z-index fix for Bootstrap modal overlap
      editor.on('init', function () {
        const container = editor.getContainer();
        if (container) container.style.zIndex = '1055'; // above Bootstrap modal backdrop
      });

      // ✅ Scroll lock/unlock when pickers open
      editor.on('OpenWindow', () => {
        document.querySelector('.modal-body')?.classList.add('no-scroll');
      });

      editor.on('CloseWindow', () => {
        document.querySelector('.modal-body')?.classList.remove('no-scroll');
      });
    }
  });
});

// 🔸 Optional: destroy TinyMCE when modal is closed
$('#exampleModal').on('hidden.bs.modal', function () {
  tinymce.remove('#title, #description');
});

$(document).ready(function () {
  const $modalBody = $('#exampleModal .modal-body');

  function hideSwatchesIfOutOfView(containerSelector) {
    const swatches = $('.tox-swatches:visible');
    if (swatches.length === 0) return;

    const container = $(containerSelector)[0].getBoundingClientRect();
    const picker = swatches[0].getBoundingClientRect();

    // Agar picker container ke andar nahi hai, hide kar do
    const isInside = picker.top >= container.top && picker.bottom <= container.bottom;

    if (!isInside) {
      $('.tox-swatches').hide();
    } else {
      $('.tox-swatches').show();
    }
  }

  function checkAndPositionSwatches() {
    const activeElement = document.activeElement;

    // Agar question textarea pe focus hai
    if ($(activeElement).is('#title')) {
      $('.tox-swatches').show();
      hideSwatchesIfOutOfView('#question-field');
    }
    // Agar answer textarea pe focus hai
    else if ($(activeElement).is('#description')) {
      $('.tox-swatches').show();
      hideSwatchesIfOutOfView('#description-field');
    } else {
      // Agar dono me focus nahi, toh hide kar do
      $('.tox-swatches').hide();
    }
  }

  // Scroll & wheel events on modal body to check visibility
  $modalBody.on('scroll wheel', function () {
    checkAndPositionSwatches();
  });

  // Also check on focus change on textareas
  $('#title, #description').on('focus click keyup', function () {
    checkAndPositionSwatches();
  });

  // Hide color picker on modal close or other events if needed
  $('#exampleModal').on('hidden.bs.modal', function () {
    $('.tox-swatches').hide();
  });
});


$(document).ready(function() {
// Scroll to question field
function navigateToQuestionField() {
  const el = document.getElementById('question-field');
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' });
    el.classList.add('highlight');
    setTimeout(() => el.classList.remove('highlight'), 2000);
  }
}
  $(document).on('keyup', '#title', function(e) {
    var productName = $.trim($(this).val());
    checkSlugIsUnique(productName);
  });

  var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
    backdrop: 'static',
    keyboard: false
  });

  $('#exampleModal').on('hidden.bs.modal', function() {
    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
  });

  function isEmptyTinyMCEContent(content) {
    return !$.trim($("<div>").html(content).text());
  }

  $('#zone').on('submit', function(e) {
    e.preventDefault();

    $('.text-danger').text('');

    var title = tinymce.get('title').getContent();
    var description = tinymce.get('description').getContent();
    var typeId = $('#type_id').val();
    var status = $('#status').val();

    let hasError = false;

    if (isEmptyTinyMCEContent(title)) {
      $('.error-title').text('The question field is required.');
      hasError = true;
    }

    if (isEmptyTinyMCEContent(description)) {
      $('.error-description').text('The answer field is required.');
      hasError = true;
    }

    if (!typeId) {
      $('.error-type_id').text('The type field is required.');
      hasError = true;
    }

    if (!status) {
      $('.error-status').text('The status field is required.');
      hasError = true;
    }

    if (hasError) return false;

    var formData = new FormData(this);
    formData.set('title', title);
    formData.set('description', description);

    $.ajax({
      url: "{{ route('admin.faq.save') }}",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function() {
        $('.text-danger').text('');
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message);
          $('#exampleModal').modal('hide'); // modal close karna yahan
          location.reload();
        }
      },
      error: function(xhr) {
        let errors = xhr.responseJSON.errors;
        $(".text-danger").text("");
        if (errors.title) {
          $(".error-title").text(errors.title);
        }
        if (errors.description) {
          $(".error-description").text(errors.description);
        }
        if (errors.status) {
          $(".error-status").text(errors.status);
        }
        if (errors.type_id) {
          $(".error-type_id").text(errors.type_id);
        }
        modal.show();
      },
      complete: function() {
        $('.loader').hide();
        $('.screen-block').hide();
      }
    });
  });

  function checkSlugIsUnique(productName) {
    if (productName) {
      $.ajax({
        url: "{{ route('admin.faq.unique.slug') }}",
        method: "post",
        dataType: "json",
        data: {
          '_token': '{{ csrf_token() }}',
          '_method': 'post',
          productName: productName
        },
        success: function(res) {
          $('#slug').val(res.slug);
          if (res.error === true) {
            $('#slugError').text(res.msg).show();
          }
        },
      });
    }
  }

  $(".toggleSwitch").on("change", function() {
    var status = $(this).is(":checked") ? 1 : 0;
    var id = $(this).val();
    $.ajax({
      url: "{{ route('admin.faq.update.status') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        status: status,
        id: id
      },
      success: function(response) {
        if (response.success) {
          toastr.success(response.message)
        }
      },
      error: function(xhr, status, error) {
        toastr.error("Something went wrong")
      }
    });
  });

  $('#exampleModal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    $('#id').val('');
    $("#editImage").hide().attr('src', '');
    $("#formHeading").text('Add FAQ');
    $(".btn-save").text('Add FAQ');
  });

  function setupDropdown(dropdownButtonId) {
    const $dropdownButton = $('#' + dropdownButtonId);
    const $dropdownMenu = $dropdownButton.next();
    const $dropdownItems = $dropdownMenu.find('.dropdown-item');

    $dropdownButton.on('click', function() {
      $dropdownMenu.toggle();
    });

    $dropdownItems.on('click', function() {
      const selectedValue = $(this).data('value');
      $dropdownButton.html(selectedValue + '  <i class="fa fa-caret-down"></i>');
      $dropdownMenu.hide();
    });

    $(document).on('click', function(e) {
      if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
        $dropdownMenu.hide();
      }
    });
  }

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
  if (params.has('name') || params.has('status') || params.has('type') || params.has('start_date') || params.has('end_date')) {
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
</script> @endpush