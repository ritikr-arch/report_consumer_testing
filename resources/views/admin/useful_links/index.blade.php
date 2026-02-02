@extends('admin.layouts.app')
@section('content')
<style>
  .rela-icon i {
    top: 45px;
  }
  #title {
    height: 50px !important;
    border-radius: 50px;
    background: #fff;
    margin-top: 2px;
  }
  .text-danger {
    font-size: 0.875em;
    margin-top: 4px;
  }
</style>

<div class="px-3">
  <div class="container-fluid">
    <input type="hidden" id="content_id" value="">

    <div class="row mt-3">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body">
            <h4 class="header-title mb-3 font-weight-bold">Useful Links</h4>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group ad-user">
                  <div class="rela-icon">
                    <label for="type" class="mb-2">Page Type <span class="text-danger">*</span></label>
                    <select class="form-control" name="type" id="type" onchange="getContent(this.value)">
                      <option value="1" {{ ($usefull_link->type == 1) ? 'selected' : '' }}>Lid on spending</option>
                      <option value="2" {{ ($usefull_link->type == 2) ? 'selected' : '' }}>Cellular Phones</option>
                      <option value="3" {{ ($usefull_link->type == 3) ? 'selected' : '' }}>Consumer Rights & Responsibilites</option>
                      <option value="4" {{ ($usefull_link->type == 4) ? 'selected' : '' }}>Backyard Gardening</option>
                      <option value="5" {{ ($usefull_link->type == 5) ? 'selected' : '' }}>Weight & Measure</option>
                      <option value="6" {{ ($usefull_link->type == 6) ? 'selected' : '' }}>Wise Spender</option>
                    </select>
                    <i class="fa-solid fa-caret-down"></i>
                    <div class="text text-danger" id="type_error"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" placeholder="Title" name="title" id="title" value="">
                <div class="text text-danger" id="title_error"></div>
              </div>

              <div class="col-md-12">
                <div class="form-group ad-user mt-4">
                  <label for="content">Content <span class="text-danger">*</span></label>
                  <textarea class="form-control oye-f2" name="content" id="content"></textarea>
                  <div class="text text-danger" id="content_error"></div>
                </div>
              </div>
            </div>

            <button class="searc-btn mt-3" onclick="updateContent()">Update</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include TinyMCE -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  // Initialize TinyMCE
  tinymce.init({
    selector: '#content',
    plugins: 'code link lists table fullscreen fontfamily fontsize',
    toolbar: 'undo redo | fontfamily fontsize | bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link customImageUpload | code fullscreen',
    height: 400,
    fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
    font_family_formats:
      'Arial=arial,helvetica,sans-serif;' +
      'Courier New=courier new,courier,monospace;' +
      'Georgia=georgia,palatino;' +
      'Tahoma=tahoma,arial,helvetica,sans-serif;' +
      'Times New Roman=times new roman,times;' +
      'Verdana=verdana,geneva;',
    setup: function(editor) {
      editor.ui.registry.addButton('customImageUpload', {
        text: 'Upload Image',
        icon: 'image',
        onAction: function() {
          const input = document.createElement('input');
          input.type = 'file';
          input.accept = 'image/*';
          input.onchange = function() {
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

     
    }
  });

  // Get content for given type and update form fields including TinyMCE
  function getContent(typeValue) {
    $.ajax({
      method: "POST",
      url: "{{ route('admin.get-useful-content') }}",
      data: {
        type: typeValue,
        _token: '{{ csrf_token() }}'
      },
      dataType: "JSON",
      success: function(response) {
        if (response.status == 1) {
          $('#content_id').val(response.content_id);
          $('#type').val(response.type);
          $('#title').val(response.title);
          $('#content').val(response.content);
          
          if (tinymce.get('content')) {
            tinymce.get('content').setContent(response.content);
          }

          // Hide error messages on successful load
          $('#type_error').hide();
          $('#title_error').hide();
          $('#content_error').hide();
        } else if (response.status == 0) {
          // Show error message if any
          alert(response.message || 'Failed to load content');
        }
      },
      error: function(xhr) {
        console.error("Error:", xhr.responseText);
        alert('Something went wrong. Please try again.');
      }
    });
  }

      
        let defaultType = $('#type').val();
        
        getContent(defaultType);
  // Update content on server via AJAX
  function updateContent() {
    var type = $('#type').val();
    var title = $('#title').val();
    var content = tinymce.get('content').getContent();
    var content_id = $('#content_id').val();

    // Clear previous errors
    $('#type_error').hide();
    $('#title_error').hide();
    $('#content_error').hide();

    $.ajax({
      method: "POST",
      url: "{{ route('admin.update-useful-content') }}",
      data: {
        type: type,
        title: title,
        content: content,
        id: content_id,
        _token: '{{ csrf_token() }}'
      },
      dataType: "JSON",
      success: function(response) {
        if (response.status == 0) {
          // Show validation errors
          if (response.type) {
            $('#type_error').text(response.type).show();
          }
          if (response.title) {
            $('#title_error').text(response.title).show();
          }
          if (response.content) {
            $('#content_error').text(response.content).show();
          }
        } else if (response.status == 1) {
          toastr.success(response.message || "Content updated successfully.");
          setTimeout(function() {
            location.reload();
          }, 2000);
        }
      },
      error: function(xhr) {
        console.error("Error:", xhr.responseText);
        toastr.error('Something went wrong. Please try again.');
      }
    });
  }
</script>
@endsection
