@extends('admin.layouts.app')

@section('title', @$title)

@section('content')
<style>

      .modal-dialog.modal-lg.modal-dialog-centered.height-500 .modal-body {
    height: 440px;
    overflow: auto;
    text-align: justify;
}
.home-modal .heading h2 {
    margin-top: -10px !important;
}
  .overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5); /* semi-transparent black */
  z-index: 9998; /* thoda loader ke neeche rakhna */
  display: none; /* hidden by default */
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
                            <div class="col-xl-5">
                                <h4 class="header-title mb-0 font-weight-bold">
                                    Quick Links
                                </h4>
                            </div>



                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                                        <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>

                                    @can('quick_links_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Create Quick Links" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                        <i class="fa-solid fa-plus"></i>&nbsp;Quick Links </button>
                                    @endcan
                                </div>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <form action="{{route('admin.quick.filter')}}" method="get">
                                <hr>
                                <div id="dropdown" class="dropdown-container-filter">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Title" value="{{ request('name') }}">
                                    </div>
                                 
                                    <select class="form-select" name="status" aria-label="Default select example">
                                        <option value="" selected="">Status</option>
                                        <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive
                                        </option>
                                    </select>

                                    <button type="submit" class="d-flex searc-btn">Search</button>
                                    <a href="{{route('admin.quick.list')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>
                                    <!-- <button type="reset" class="btn btn-secondary btn-sm"> Reset</button> -->

                                </div>

                            </form>

                        </div>



                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th style="min-width:10px;">S.No.</th>
                                        <th style="min-width:80px;">Title</th>
                                        <th style="min-width:100px;">Image</th>
                                        <th style="min-width:100px;">Description</th>
                                        @can('quick_links_edit')

                                        <th style="min-width:80px;">Status</th>
                                        @endcan
                                         <th style="min-width:80px;">Document</th>
                                        @canany(['quick_links_view','quick_links_edit','quick_links_delete'])
                                        <th style="min-width:120px;">Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ Str::limit(ucfirst($value->title), 50, '...') }}</td>
                                        <td>
                                            <?php
                                    $imageURL = '';
                                    if($value->image){
                                       $imageURL = 'admin/images/quck_link/'.$value->image;
                                    }else{
                                       $imageURL = 'admin/images/news/news.jpg';
                                    }
                                    ?>
                                            <img src="{{asset($imageURL)}}"
                                                style="height: 60px; width:60px;border-radius: 50%;" alt="">
                                        </td>
                                        <td>
                                        @php
                                            $plainContent = strip_tags($value->content);
                                            $isLong = strlen($plainContent) > 100;
                                            $limitedContent = Str::limit($plainContent, 100, '...');
                                        @endphp

                                        {!! $isLong ? $limitedContent : $plainContent !!}

                                        @if ($isLong)
                                            <!-- Read More trigger -->
                                            <a href="#" 
                                               data-bs-toggle="modal" 
                                               data-bs-target="#readMoreModal{{ $value->id }}">
                                               Read more
                                            </a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="readMoreModal{{ $value->id }}" tabindex="-1" aria-labelledby="readMoreLabel{{ $value->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered height-500">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="readMoreLabel{{ $value->id }}">Full Content</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! $plainContent !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>


                                          @can('quick_links_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        @endcan
                                       
                                        <td class="active-bt text-center">
                                            @php
                                                $path = 'admin/docs/quick_link/';
                                                $documentName = $value->document; 
                                                $documentUrl = asset($path . $documentName);

                                             
                                                $ext = pathinfo($documentName, PATHINFO_EXTENSION);
                                                $iconClass = 'fas fa-file-alt';

                                                if ($ext === 'pdf') {
                                                    $iconClass = 'fas fa-file-pdf text-danger';
                                                } elseif (in_array($ext, ['doc', 'docx'])) {
                                                    $iconClass = 'fas fa-file-word text-primary';
                                                }
                                            @endphp

                                            @if(!empty($documentName))
                                                <a href="{{ $documentUrl }}" target="_blank" data-toggle="tooltip" data-placement="top" title="Open Document">
                                                    <i class="{{ $iconClass }}" style="font-size: 20px;"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">No document</span>
                                            @endif

                                        </td>
                                         @canany(['quick_links_view','quick_links_edit','quick_links_delete'])
                                        <td>
                                            <div class="action-btn">

                                                @can('quick_links_view')
                                                
                                                @endcan

                                                @can('quick_links_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editZone"
                                                    id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('quick_links_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteQucklink" alt="trash">
                                                @endcan
                                            </div>
                                        </td>
                                         @endcanany
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
    <div class="modal-dialog modal-lg modal-dialog-centered height-500">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="heading">
                    <h2 id="formHeading">Add Quick Links</h2>
                    <p>Please enter the following Data</p>
                </div>

                <form method="post" id="zone" class="mt-4">
                    @csrf
                    <div class="overlay"></div>
<div class="loader" style="display:none;"></div>
                    <input type="hidden" id="id" value="" name="id">
                    <input type="hidden" class="form-control" id="slug" name="slug">
                    <div class="row">
                        <div class="col-md-12">
                        <label>Title <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="text" class="form-control" id="title" name="title" >
                                <span class="text-danger error-title"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                        <label>Description <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <textarea class="form-control oye-f2" name="description" id="description" ></textarea>
                                <span class="text-danger error-description"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label>Upload Image <span class="text-danger">*</span></label>
                                <div class="upload-box" id="uploadBox">
                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image </span>
                                    <input type="file" id="fileInput" name="image" accept="image/*">
                                </div>
                                <span class="text-danger error-image"></span>
                                <p style="font-size: 11px; font-weight: 700;"> Recommended Dimension -> 58x58 Pixels (Max) </br>  Image size should not more than 5MB </p>

                                <!-- <p> Recommended size should be Minimum image size: 58x58 pixels, 2 MP </p> -->
                            </div>
                            <img id="editImage" src="" class="editt-img" style="display: none;">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label>Upload PDF <span class="text-danger">*</span></label>
                                <div class="upload-box" id="uploadBox">
                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload PDF </span>
                                    <input type="file" id="fileInput" name="document" accept=".pdf">
                                </div>
                                <span class="text-danger error-pdf"></span>
                                <p style="font-size: 11px; font-weight: 700;"> PDF size should not more than 2MB </p>
                            </div>
                             <a  id="editPdf" href=""  target="_blank" style="display: none;">View Document</a>
                            
                        </div>

                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label>Select Status <span class="text-danger">*</span></label>
                                <div class="rela-icon">
                                    <select class="form-control" id="status" name="status">
                                        <option value="" selected disabled>Select Status </option>
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-status"></span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btnss btn-save">Add Quick Links</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered height-500">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quickViewLabel">View Content</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="quickViewContent">
        Loading...
      </div>
    </div>
  </div>
</div>

@endsection



@push('scripts')




<script>
  // Initialize TinyMCE
  tinymce.init({
    selector: '#description',
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
$(document).on('click', '.editZone', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });
 var baseAssetPath = "{{ asset('admin/docs/quick_link/') }}/";
    var id = $(this).attr('id');
    var url = "{{ route('admin.quick.edit', ':id') }}";
    url = url.replace(':id', id);
    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#title').val(response.data.title);
                    $('#id').val(response.data.id);
                    // $('#description').text(response.data.description);
                    tinymce.get('description').setContent(response.data.content); // ✅ TinyMCE

                    $('#status').val(response.data.status).change();
                    $('#type').val(response.data.type).change();
                    $("#editImage").css('display', 'flex')
                    $("#editImage").attr('src', response.data.image)
                    let documentName = response.data.document;
                    let documentUrl = baseAssetPath + documentName;

                    $("#editPdf").css('display', 'flex').attr('href', documentUrl);
                  
                    $("#formHeading").text('Edit Quick Links')
                    $(".btnss").text('Update Quick Links')
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
  // Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});
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



$('#zone').on('submit', function(e) {
    e.preventDefault();

    var description = tinymce.get('description').getContent();
    var formData = new FormData(this);

    // Set rich text content into the form
    formData.set('description', description);

    // Show loader
    $('.overlay').show();
    $('.loader').show();

    $.ajax({
        url: "{{ route('admin.quick.save') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Hide loader
            $('.overlay').hide();
            $('.loader').hide();

            if (response.success) {
                toastr.success(response.message);
                modal.hide();
                location.reload();
            }
        },
        error: function(xhr) {
            // Hide loader
            $('.overlay').hide();
            $('.loader').hide();

            let errors = xhr.responseJSON.errors;

            if (errors.title) {
                $(".error-title").text(errors.title);
            }
            if (errors.description) {
                $(".error-description").text(errors.description);
            }
            if (errors.status) {
                $(".error-status").text(errors.status);
            }
            if (errors.document) {
                $(".error-pdf").text(errors.document);
            }
            if (errors.image) {
                $(".error-image").text(errors.image);
            }

            modal.show();
        }
    });
});


    function checkSlugIsUnique(productName) {
        if (productName) {
            $.ajax({
                url: "{{ route('admin.quick.unique.slug') }}",
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
            })
        }
    }

    $(".toggleSwitch").on("change", function() {
        var status = $(this).is(":checked") ? 1 : 0;
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.quick.update.status') }}",
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
          $("#formHeading").text('Add Quick Links');
          $(".btn-save").text('Add Quick Links');
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
    if (params.has('name') || params.has('status') || params.has('type') || params.has('start_date') || params.has(
            'end_date')) {
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