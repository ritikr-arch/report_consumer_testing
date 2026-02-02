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
                             Brochures & Presentation
	                        </h4>
	                     </div>

						 <div class="col-12 col-md-7 col-lg-7">

							<div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                                        <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>

                                    @can('publication_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Brochures & Presentation">
                                        <i class="fa-solid fa-plus"></i>&nbsp;Brochures & Presentation </button>
                                    @endcan
                                </div>
	                     </div>

	                  </div>


	                  <div class="row">
	                     <form action="{{route('admin.broachers.presentation.filter')}}" method="get">
	                        <div id="dropdown" class="dropdown-container-filter">
	                           <div class="name-input">
	                              <input type="text" class="form-control" name="title" id="exampleFormControlInput1" value="{{ request('title') }}" placeholder="Title">
	                           </div>
							   <select class="form-select" name="type" aria-label="Default select example">
	                              <option value="" selected="">Type</option>
	                             <option value="Brochures" {{ request('type') === 'Brochures' ? 'selected' : '' }}>Brochures </option>

		                            <option value="Presentation" {{ request('type') === 'Presentation' ? 'selected' : '' }}>Presentation</option>
	                           </select>
	                           <select class="form-select" name="status" aria-label="Default select example">
	                              <option value="" selected="">Status</option>
	                              <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active</option>
	                              <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive</option>
	                           </select>
	                           <button type="submit" class="d-flex searc-btn" >Search</button>
	                           <a href="{{route('admin.broachers.presentation.list')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>
	                        </div>
	                     </form>
	                  </div>
	                  <div class="table-responsive white-space">
	                     <table class="table table-hover mb-0">
	                        <thead>
	                           <tr class="border-b">
	                              <th>S.No.</th>
	                               <th>Title</th>
								   <th>Image</th>
								   <th>Type</th>
								   <th>Documnt</th>
								  @can('publication_edit')
	                              <th>Status</th>
								  @endcan
	                              @canany(['publication_view','publication_edit','publication_delete'])
	                              <th>Action</th>
	                              @endcan
	                           </tr>
	                        </thead>
	                        <tbody>

	                              <tbody>
	                              	@if(isset($data) && count($data)>0)
	                              	@foreach($data as $key=>$value)
	                              		<tr>
	                              		   <td>{{$key+1}}</td>
											
	                              		    <td>{{ucfirst($value->title)}}</td>
											<td><img src="{{ asset('admin/images/broacher/'.$value->image) }}" style="height: 60px; width:60px;border-radius: 50%;"></td>
											 <td>{{ucfirst($value->type)}}</td>
										  <td style="text-align: center;"><a href="{{ asset('admin/images/broacher/'.$value->document) }}" target="_blank"><i class="fas fa-file-pdf text-danger" style="font-size: 20px;"></i></a></td>
											@can('publication_edit')
	                              		   <td class="active-bt">
											 <label class="switch">
	                           		         		<input type="checkbox" value="{{$value->id}}" class="toggleSwitch"  {{ $value->status == 1 ? 'checked' : '' }}>
	                           		         		<span class="slider round"></span>
	                           		         	</label>
											
										   </td>
										   @endcan
										   @canany(['publication_view','publication_edit','publication_delete'])
	                           		   	<td>
	                           		      	<div class="action-btn d-flex">
	                           		      		<!-- @can('publication_view')
	                           		         	<a data-toggle="tooltip" data-placement="top" title="View" href="{{route('admin.broachers.presentation.view', $value->id)}}"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a>
	                           		         	@endcan -->

	                           		         	@can('publication_edit')
	                           		         	<img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editBroacher" id="{{$value->id}}" alt="edit">
	                           		         	@endcan

	                           		         	@can('publication_delete')
	                           		         	<img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteBroacher" alt="trash">
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

	               <h2 id="formHeading">Brochures & Presentation</h2>

	               <p>Please enter the following Data</p>
	            </div>
	            <form method="post" id="broachers" class="mt-4">
	            	@csrf
					<div class="loader"></div>
					<div class="screen-block"></div>

	               <div class="row">
	                  <div class="col-md-6">
					  <label>Title <span class="text-danger">*</span></label>
	                     <div class="form-group ad-user">
	                     	<input type="hidden" id="id" value="" name="id">
	                        <input type="text" class="form-control" id="title" name="title" >
	                        <span class="text-danger error-title"></span>
							
	                     </div>
	                  </div>

                      <div class="col-md-6">
	                     <div class="form-group ad-user">
						 <label>Select Type <span class="text-danger">*</span></label>
	                        <div class="rela-icon">
	                            <select class="form-control" id="type" name="type">
                                <option value="" hidden>Select Type </option>

		                            <option value="Brochures">Brochures </option>

		                            <option value="Presentation">Presentation</option>
	                            </select>
	                            <i class="fa-solid fa-caret-down"></i>
	                            <span class="text-danger error-type"></span>
	                        </div>

	                     </div>
	                  </div>

					  <div class="col-md-6 mt-3 mb-3">
						<div class="form-group ad-user">
								<label>Upload Image <span class="text-danger">*</span></label>
							<div class="upload-box">
								<span id="fileNameImage"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image</span>
								<input type="file" id="image" name="image" accept="image/*">
							</div>
							<span class="text-danger error-image"></span>
							<p style="font-size: 11px; font-weight: 700;"> Recommended Dimension -> 352x250 Pixels (Max) </br> Image size should not more then 2MB </p>
						</div>
						<img id="editImage" src="" class="editt-img" style="display: none; max-width: 100px;">
					</div>

					<div class="col-md-6 mt-3 mb-3">
						<div class="form-group ad-user">
						<label>Upload Document <span class="text-danger">*</span></label>
							<div class="upload-box">
								<span id="fileNameDocument"><i class="fa-solid fa-upload"></i>&nbsp;Upload Document</span>
								<input type="file" id="document" name="document">
							</div>
							<span class="font-text">Only .pdf, .docx and .doc are accepted. Maximum file size: 2 MB.</span><br>
							<span class="text-danger error-document"></span>
						</div>
						<p id="documentPreview" style="display: none;"></p>
					</div>


                        <div class="col-md-6">
	                     <div class="form-group ad-user">
						 <label >Select Status <span class="text-danger">*</span></label>
	                       
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

	                     <button type="submit" class="btn btn-save">Add Brochures & Presentation</button>

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
	$(document).on("click", ".editBroacher", function (e) {
		var modal = new bootstrap.Modal(document.getElementById("exampleModal"), {
			backdrop: "static",
			keyboard: false,
		});

		var id = $(this).attr("id");
		var url = "{{ route('admin.broachers.presentation.edit', ':id') }}";
		url = url.replace(":id", id);

		if (id) {
			$.ajax({
				url: url,
				type: "GET",
				success: function (response) {
					if (response.success) {
						$("#title").val(response.data.title);
						$("#id").val(response.data.id);
						
						// Set image preview
						if (response.data.image) {
							$("#editImage").css("display", "block");
							$("#editImage").attr('src', response.data.image)
						
						} else {
							$("#editImage").css("display", "none");
						}

						// Set document preview as a link
						if (response.data.document) {
							$("#documentPreview")
								.html(
									`<a href="${response.data.document}" target="_blank">View Document</a>`
								)
								.css("display", "block");
						} else {
							$("#documentPreview").css("display", "none").html("");
						}

						$("#status").val(response.data.status).change();
						$("#type").val(response.data.type).change();
						$("#formHeading").text("Edit Brochures & Presentation");
						$(".btn-save").text("Update Brochures & Presentation");

						$("#exampleModal").modal("show");
					}
				},
				error: function (xhr) {
					let errors = xhr.responseJSON.errors;
				},
			});
		}
	});


			$('#exampleModal').on('hidden.bs.modal', function () {
			var $form = $(this).find('form');
			
			if ($form.length) {
				$form[0].reset(); // Reset form fields
			}

			$('#id').val(''); 
			$("#editImage").hide().attr('src', ''); 
			$("#documentPreview").hide().html(''); // Reset document preview
			$("#formHeading").text('Add Broacher & Presentation'); 
			$(".btn-save").text('Add Broacher & Presentation'); 
			document.body.classList.remove('modal-open');
			$('.modal-backdrop').remove();
		});

	$(document).ready(function(){
		var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
		    backdrop: 'static',
		    keyboard: false 
		});

		$('#broachers').on('submit', function (e) {
            // alert('hello');
		    e.preventDefault();
		    let formData = new FormData(this);
			$('.loader').show();
       		$('.screen-block').show()
		    $.ajax({
		        url: "{{ route('admin.broachers.presentation.save') }}", 
		        type: "POST",
		        data: formData,
		        processData: false,
		        contentType: false,
		        beforeSend: function () {
		            $('.text-danger').text('');
		        },

		        success: function (response) {
		            if (response.success) {
		                toastr.success(response.message)
		                modal.hide();
		                location.reload();
		            }
		        },

		        error: function (xhr) {
		            let errors = xhr.responseJSON.errors; 
		            $(".text-danger").text("");
		            if (errors.title) {
		                $(".error-title").text(errors.title[0]);
		            }
					if (errors.type) {
		                $(".error-type").text(errors.type[0]);
		            }
                    if (errors.document) {
		                $(".error-document").text(errors.document[0]);
		            }

					if (errors.image) {
		                $(".error-image").text(errors.image[0]);
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



		$(".toggleSwitch").on("change", function(){
		   var status = $(this).is(":checked") ? 1 : 0;
		   var id = $(this).val();
		   $.ajax({
		      url: "{{ route('admin.broachers.presentation.status') }}",
		      type: "POST",
		      data: {_token: "{{ csrf_token() }}", status: status, id:id },
		      success: function(response){
		         console.log(response);
		         if (response.success) {
		            toastr.success(response.message)
		         }
		      },

		      error: function(xhr, status, error){
		         toastr.success(response.message)
		      }
		   });
		});



		function setupDropdown(dropdownButtonId) {
		 const $dropdownButton = $('#' + dropdownButtonId);
		 const $dropdownMenu = $dropdownButton.next();
		 const $dropdownItems = $dropdownMenu.find('.dropdown-item');

		 // Toggle dropdown visibility

		 $dropdownButton.on('click', function () {
		   $dropdownMenu.toggle();
		 });

		
		 // Update dropdown button text on item click

		 $dropdownItems.on('click', function () {
		   const selectedValue = $(this).data('value');
		   $dropdownButton.html(selectedValue + ' <i class="fa fa-caret-down"></i>');
		   $dropdownMenu.hide();
		 });

		

		 // Close dropdown when clicking outside

		 $(document).on('click', function (e) {
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

		$dropdownButton.on('click', function () {
		 $dropdownMenu.toggle();
		});

		// Close dropdown when clicking outside

		$(document).on('click', function (e) {
		 if (!$dropdownButton.is(e.target) && !$dropdownMenu.is(e.target) && $dropdownMenu.has(e.target).length === 0) {
		   $dropdownMenu.hide();
		 }
		});
		// Update dropdown button text based on selected items

		$checkboxes.on('change', function () {
		 const selectedOptions = $checkboxes.filter(':checked').map(function () {
		   return $(this).val();
		 }).get();
		 $dropdownButton.html(
		   selectedOptions.length > 0
		     ? selectedOptions.join(', ') + ' <i class="fa fa-caret-down"></i>'
		     : 'Select Options <i class="fa fa-caret-down"></i>'
		 );
		});

	});
	function toggleDropdown() {
	    var dropdown = document.getElementById("dropdown");
	    dropdown.classList.toggle("active");
	}
	window.onload = function () {

	   let params = new URLSearchParams(window.location.search);

	      if (params.has('name') || params.has('start_date') || params.has('status') || params.has('end_date')) {

	      let dropdown = document.getElementById("dropdown");

	      dropdown.classList.toggle("active");

	   }

	};


	document.addEventListener("DOMContentLoaded", function () {
    function previewFile(inputId, previewId, isImage) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                if (isImage) {
                    // Image preview
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = "block";
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Document preview (file name)
                    preview.textContent = "Selected File: " + file.name;
                    preview.style.display = "block";
                }
            }
        });
    }

    // Initialize previews
    previewFile("image", "editImage", true);
    previewFile("document", "documentPreview", false);
});

</script>

@endpush