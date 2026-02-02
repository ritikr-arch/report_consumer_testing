@extends('admin.layouts.app')

@section('title', @$title)
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
@section('content')

<style>
.page-content 
{
   height: 100vh;
   overflow-y: scroll;
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
                           Image Gallery
                        </h4>
                     </div>

                     <div class="col-12 col-md-7 col-lg-7">
                        <div class="search-btn1 text-end">
                           @can('gallery_create')
                           <button class="d-fle btn btn-primary btn-sm createGallery" data-bs-toggle="modal" data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Gallery">
                              <i class="fa-solid fa-plus"></i>&nbsp;Image Gallery </button>
                           @endcan
                        </div>
                     </div>

                  </div>


                  <div class="row">
                     <form action="{{route('admin.image.gallery.filter')}}" method="get">
                        <div id="dropdown" class="dropdown-container-filter">
                           <!-- <div class="name-input">
                              <input type="text" class="form-control" name="name" id="name" placeholder="Title" value="{{ request('name') }}">
                           </div> -->
                           <select class="form-select" name="status" aria-label="Default select example">
                              <option value="" selected="">Status</option>
                              <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active</option>
                              <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive</option>
                           </select>

                           <button type="submit" class="d-flex searc-btn">Search</button>

                           <a href="{{route('admin.image.gallery.list')}}">Reset</a>

                        </div>

                     </form>

                  </div>



                  <div class="table-responsive white-space">

                     <table class="table table-hover mb-0">

                        <thead>

                           <tr class="border-b  bg-light2">

                              <th style="min-width:10px;">S.No.</th>
                              <th style="min-width:80px;">Title</th>
                              <th style="min-width:100px;">Image</th>
                              @can('gallery_edit')
                              <th style="min-width:80px;">Status</th>
                              @endcan

                              @canany(['gallery_edit','gallery_delete'])
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
                                 if ($value->image) {
                                    $imageURL = 'admin/images/imageGallery/' . $value->image;
                                 } 
                                 ?>
                                 <img src="{{asset($imageURL)}}" style="height: 60px; width:60px;border-radius: 50%;" alt="">
                              </td>
                              @can('gallery_edit')
                              <td class="active-bt">
                                 <label class="switch">
                                    <input type="checkbox" value="{{$value->id}}" class="toggleSwitch" {{ $value->status == 1 ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                 </label>
                              </td>
                              @endcan
                              @canany(['gallery_edit','gallery_delete'])
                              <td>
                                 <div class="action-btn">
                                    @can('gallery_edit')
                                    <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editFaq" id="{{$value->id}}" alt="edit">
                                    @endcan

                                    @can('gallery_delete')
                                    <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteImageGallery" alt="trash">
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



@endsection



@push('scripts')
<div class="modal fade home-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

   <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="btn-close close-modal-btn" aria-label="Close"></button>
         </div>
         <div class="modal-body">

            <div class="heading">

               <h2 id="formHeading">Add Gallery</h2>

               <p>Please enter the following Data</p>

            </div>

            <form method="post" id="zone" class="mt-4" enctype="multipart/form-data">
               @csrf
               <input type="hidden" id="id" name="id">

               <div class="row">
                  <div class="loader"></div>
                  <div class="screen-block"></div>
                    <div class="col-md-6">
                     <div class="form-group ad-user">
                        <label>Image title </label><span style="color:red">*</span>
                        <div class="rela-icon">
                           <input type="text" class="form-control" name="image_title" id="image-title">
                           
                           <span class="text-danger error-image-title"></span>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group ad-user">
                        <label>Feature Image </label><span style="color:red">*</span>
                       
                        <div class="upload-box" id="uploadBox">
                        <span id="fileName">
                           <i class="fa-solid fa-upload"></i>&nbsp;Upload Image
                        </span>
                        <input type="file" id="fileInput" name="image" accept="image/*" style="opacity:0; position:absolute; left:0; top:0; width:100%; height:100%; cursor:pointer;">
                        </div>
                        <span class="font-text">Only .jpg, .jpeg and .png are accepted. Maximum file size: 2 MB.</span><br>
                        <span class="text-danger error-image"></span>
                     </div>

                     <img id="editImage" src="" class="editt-img" style="display: none;">

                  </div>

                  <div class="col-md-6">
                     <div class="form-group ad-user"  style="margin-top:10px;">
                        <label>Gallery Images </label><span style="color:red">*</span>
                        <div class="upload-box" id="uploadBoxes">
                           <span id="fileName"> <i class="fa-solid fa-upload"></i>&nbsp;Upload Image</span>

                           <input type="file" id="fileInputGallery" name="gallery_images[]" accept="image/*" multiple>

                        </div>
                        <span class="font-text">Only .jpg, .jpeg and .png are accepted. Maximum file size: 2 MB for each.</span><br>
                        <span class="text-danger image-gallery-error"></span>
                     </div>
                     
                  </div> 
                  

                  <div class="col-md-6">
                     <div class="form-group ad-user" style="margin-top:10px;">
                        <label>Select Status </label><span style="color:red">*</span>
                        <div class="rela-icon">
                           <select class="form-control" id="status" name="status">
                              <option value="" selected disabled>Select Status </option>
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                           </select>
                           <i class="fa-solid fa-caret-down"></i>
                           <span class="text-danger error-status"></span>
                        </div>
                     </div>
                  </div>

                  <div id="editGalleryImage" style="display: none;">
                        
                  </div>
                  <div class="text-center">
                     <button type="submit" class="btn gallery-btn btn-save">Add Image Gallery</button>
                  </div>
               </div>
            </form>

         </div>

      </div>

   </div>

</div>
<script>
 

 $(document).on('change', '#fileInput, #fileInputGallery', function (e) {
   const maxSize = 2 * 1024 * 1024; // 2MB
   const isMultiple = this.id === 'fileInputGallery';
   const files = e.target.files;

   let hasError = false;
   let errorMsg = '';

   Array.from(files).forEach(file => {
      if (file.size > maxSize) {
         hasError = true;
         errorMsg = 'Each image must be less than 2 MB.';
      }
   });

   if (hasError) {
      if (isMultiple) {
         $('.image-gallery-error').text(errorMsg);
      } else {
         $('.error-image').text(errorMsg);
      }
      $(this).val(""); // Clear the input
   } else {
      $('.image-gallery-error').text('');
      $('.error-image').text('');
   }
});

// ===========================
// Preview Gallery Images (if not too large)
// ===========================

$(document).on('change', '#fileInputGallery', function (e) {
   const maxSize = 2 * 1024 * 1024; // 2MB
   const files = e.target.files;
   const previewContainer = $('#editGalleryImage');
   previewContainer.show();

   previewContainer.empty();

   Array.from(files).forEach(file => {
      if (!file.type.startsWith('image/')) return;
      if (file.size > maxSize) return; // Already validated earlier

      const reader = new FileReader();
      reader.onload = function (e) {
         const wrapper = $('<div>', {
            class: 'image-wrapper',
            css: {
               position: 'relative',
               display: 'inline-block',
               margin: '5px'
            }
         });

         const img = $('<img>', {
            src: e.target.result,
            class: 'preview-image',
            css: {
               width: '60px',
               height: '60px',
               objectFit: 'cover',
               border: '1px solid #ccc',
               borderRadius: '5px'
            }
         });

         wrapper.append(img);
         previewContainer.append(wrapper);
      };

      reader.readAsDataURL(file);
   });
});


$(document).on('click', '.editFaq', function (e) {
   var id = $(this).attr('id');
   var url = "{{ route('admin.image.gallery.edit', ':id') }}";
   url = url.replace(':id', id);

   if (id) {
      $.ajax({
         url: url,
         type: "GET",
         success: function (response) {
            if (response.success) {
               $('#id').val(response.data.id);
               $('#status').val(response.data.status).change();
               $('#image-title').val(response.data.title);
               $("#editImage").css('display', 'flex').attr('src', response.data.image);
               $('#editGalleryImage').empty().css('display', 'unset');

               response.data.multi_images.forEach(function (imageObj) {
                  let imgUrl = imageObj.name;
                  let imgId = imageObj.id;

                  let wrapper = $('<div>', {
                     class: 'image-wrapper',
                     css: {
                        position: 'relative',
                        display: 'inline-block',
                        margin: '5px'
                     }
                  });

                  let imgElement = $('<img>', {
                     src: imgUrl,
                     alt: 'Gallery Image',
                     class: 'preview-image',
                     id: imgId,
                     css: {
                        width: '60px',
                        height: '60px',
                        objectFit: 'cover',
                        border: '1px solid #ccc',
                        borderRadius: '5px'
                     }
                  });

                  let deleteIcon = $('<i>', {
                     class: 'fa fa-trash delete-image-icon',
                     'data-id': imgId,
                     css: {
                        position: 'absolute',
                        top: '2px',
                        right: '2px',
                        backgroundColor: '#fff',
                        color: 'red',
                        padding: '4px',
                        borderRadius: '50%',
                        cursor: 'pointer',
                        fontSize: '14px'
                     }
                  });

                  wrapper.append(imgElement).append(deleteIcon);
                  $('#editGalleryImage').append(wrapper);
               });

               $("#formHeading").text('Edit Gallery');
               $(".gallery-btn").text('Update Gallery');

               $('#exampleModal').modal({
                  backdrop: true,
                  keyboard: true
               }).modal('show');
            }
         }
      });
   }
});

$(document).on('click', '.close-modal-btn', function () {
   $('#exampleModal').modal('hide');
});


   $(document).on('click', '.delete-image-icon', function () {
      let $this = $(this);
      let id = $this.data('id');
      let url = `{{ route('admin.delete.multiImage.gallery', ':id') }}`;
      url = url.replace(':id', id);

      Swal.fire({
         title: 'Do you want to DELETE?',
         icon: 'question',
         showCancelButton: true,
         confirmButtonColor: '#24695c',
         cancelButtonColor: '#d22d3d',
         confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
         if (result.isConfirmed) {
               $.ajax({
                  type: "GET",
                  url: url,
                  success: function (response) {
                     if (response.success) {
                           toastr.success('Gallery Image Deleted successfully');
                           // Remove the image card or container from the DOM
                           $this.closest('.image-card, .gallery-item, .image-wrapper').remove();
                     } else {
                           toastr.error(response.message || 'Something went wrong.');
                     }
                  },
                  error: function () {
                     toastr.error('Failed to delete image.');
                  }
               });
         }
      });
   });


   $(document).ready(function () {
      var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
         backdrop: 'static',
         keyboard: false
      });

      $('#exampleModal').on('hidden.bs.modal', function () {
         document.body.classList.remove('modal-open');
         $('.modal-backdrop').remove();

         // Clear form and reset modal when closed
         $('#zone')[0].reset();
         $('#id').val('');
         $('#editGalleryImage').empty().hide();
         $('#editImage').hide();
         $('.text-danger').text('');
         $('#status').val('').change();
      });

      // ✅ CREATE MODE: Show modal with clean state
      $(document).on('click', '.createGallery', function () {
         $('#zone')[0].reset();
         $('#id').val('');
         $('#editGalleryImage').empty().hide();
         $('#editImage').hide();
         $('.text-danger').text('');
         $('#status').val('').change();

         $('#formHeading').text('Create Gallery');
         $('.gallery-btn').text('Add Gallery');

         modal.show();
      });

      $('#zone').on('submit', function (e) {
         e.preventDefault();
         $('.text-danger').text('');
         let isValid = true;

         let status = $('#status').val();
         if (!status) {
               $('.error-status').text('The status field is required.');
               isValid = false;
         }

         let imageTitle = $('#image-title').val().trim();
         if (!imageTitle) {
               $('.error-image-title').text('The image title is required.');
               isValid = false;
         }

         let isCreate = $('#id').val() === '';
         let featureImage = $('#fileInput')[0].files;
         let galleryImages = $('#fileInputGallery')[0].files;
         let allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

         if (isCreate && featureImage.length === 0) {
               $('.error-image').text('Please upload a feature image.');
               isValid = false;
         } else if (featureImage.length > 0) {
               let file = featureImage[0];
               if (!allowedTypes.includes(file.type)) {
                  $('.error-image').text('Only JPG, JPEG, and PNG images are allowed for the feature image.');
                  isValid = false;
               } else if (file.size > 10 * 1024 * 1024) {
                  $('.error-image').text('Feature image must not exceed 10 MB.');
                  isValid = false;
               }
         }

         if (isCreate && galleryImages.length === 0) {
               $('.image-gallery-error').text('Please upload at least one image in the gallery.');
               isValid = false;
         } else {
               for (let i = 0; i < galleryImages.length; i++) {
                  let file = galleryImages[i];
                  if (!allowedTypes.includes(file.type)) {
                     $('.image-gallery-error').text('Only JPG, JPEG, and PNG images are allowed in the gallery.');
                     isValid = false;
                     break;
                  }
                  if (file.size > 10 * 1024 * 1024) {
                     $('.image-gallery-error').text('Each gallery image must not exceed 10 MB.');
                     isValid = false;
                     break;
                  }
               }
         }

         if (!isValid) return;

         var formData = new FormData(this);
         $('.loader').show();
         $('.screen-block').show();

         $.ajax({
               url: "{{ route('admin.image.gallery.save') }}",
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
                  if (errors.status) $(".error-status").text(errors.status);
                  if (errors.image) $(".error-image").text(errors.image);
                  modal.show();
               },
               complete: function () {
                  $('.loader').hide();
                  $('.screen-block').hide();
               }
         });
      });

      $(".toggleSwitch").on("change", function () {
         var status = $(this).is(":checked") ? 1 : 0;
         var id = $(this).val();
         $.ajax({
               url: "{{ route('admin.image.gallery.update.status') }}",
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
               error: function () {
                  toastr.error("Something went wrong");
               }
         });
      });

      function setupDropdown(dropdownButtonId) {
         const $dropdownButton = $('#' + dropdownButtonId);
         const $dropdownMenu = $dropdownButton.next();
         const $dropdownItems = $dropdownMenu.find('.dropdown-item');

         $dropdownButton.on('click', function () {
               $dropdownMenu.toggle();
         });

         $dropdownItems.on('click', function () {
               const selectedValue = $(this).data('value');
               $dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');
               $dropdownMenu.hide();
         });

         $(document).on('click', function (e) {
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
         endDateInput.min = startDate; // Set min date for End Date
      });
   });
</script>
<script>
document.getElementById('fileInput').addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    document.getElementById('fileName').innerHTML =
      `<i class="fa-solid fa-check-circle text-success"></i>&nbsp; ${file.name}`;
  } else {
    document.getElementById('fileName').innerHTML =
      `<i class="fa-solid fa-upload"></i>&nbsp;Upload Image`;
  }
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