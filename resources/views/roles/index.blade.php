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
                        <div class="col-xl-7">
                            <h4 class="header-title mb-0 font-weight-bold">
                               Roles
                            </h4>
                        </div>

                        


                        <div class="col-12 col-md-5 col-lg-5">
                            <div class="search-btn">
                                
                                <div>
                                    @can('Create')

                                <a class="btn  btn-primary btn-sm" href="{{route('admin.role.create')}}"data-toggle="tooltip" data-placement="top" title="Create Roles"><i class="fa-solid fa-plus"></i>&nbsp;Roles</a>
                                @endcan

                                    <!-- <a href="{{route('admin.role.create')}}" class="d-fle btn btn-secondary btn-sm" ><button><i class="fa-solid fa-plus"></i>&nbsp;Roles</button></a> -->
                                </div>


                            </div>
                        </div>
                    </div>
                  <div class="row">
                     <form action="{{route('admin.user.filter')}}" method="get">
                        <div id="dropdown" class="dropdown-container-filter">
                           <div class="name-input">
                              <input type="text" class="form-control" name="name" id="exampleFormControlInput1" placeholder="Name">
                           </div>
                           <div class="email-input">
                              <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                           </div>
                           <select class="form-select" name="status" aria-label="Default select example">
                              <option value="" selected="">Status</option>
                              <option value="1">Active</option>
                              <option value="0">Deactive</option>
                           </select>
                           <select class="form-control" id="role" name="role">
                               <option value="">Role</option>
                               @foreach($roles as $role)
                                  <option value='{{$role->id}}' <?php if(@old('role') == $role->id)echo"selected"; ?>>{{$role->name}}</option>
                               @endforeach
                            </select>
                           <button type="submit" class="d-flex searc-btn" >Search</button>
                           <a href="{{route('admin.user.list')}}">Reset</a>
                        </div>
                     </form>
                  </div>
                  <div class="table-responsive white-space">
                     <table class="table table-hover mb-0">
                        <thead>
                           <tr class="border-b">
                              <th style="min-width:50px;">S.No.</th>
                              <th style="min-width:50px;">Name</th>
                              <th style="min-width:150px;">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                            @if(isset($roles) && count($roles)>0)
                            @foreach($roles as $key=>$value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ucfirst($value->name)}}</td>
                                    <td>
                                        <div class="action-btn">
                                            @can('View')
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="View" href="{{ route('admin.role.show',$value->id) }}"><img src="{{asset('admin/img/view-eye.png')}}" alt="view" class="view-icon"></a>
                                            @endcan
                                            @can('Edit')
                                            <a data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="{{ route('admin.role.edit',$value->id) }}" title="">
                                                <img src="{{asset('admin/img/edit-2.png')}}" class="editUser" id="{{$value->id}}" alt="edit">
                                            </a>
                                            @endcan
                                            @can('Delete')
                                            <img data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deleteRole" alt="trash">
                                            @endcan
                                        </div>
                                    </td>
                                </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                     </table>
                     @if(isset($roles))
                         {{ $roles->appends(request()->query())->links('pagination::bootstrap-5') }}
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
               <h2 id="formHeading">Add Roles</h2>
               <p>Please enter the following Data</p>
            </div>
            <form method="post" class="mt-4" id="roles">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="id" value="" name="id">
                        <div class="form-group ad-user">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name">
                            <span class="text-danger error-name"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ad-user">
                            <input type="email" class="form-control" name="email" id="email" placeholder="User Email">
                            <span class="text-danger error-email"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ad-user">
                            <!-- <label for="status">Select Status</label> -->
                            <div class="rela-icon">
                               <select class="form-control" id="role" name="role">
                                   <option value="">Select Role</option>
                                   @foreach($roles as $role)
                                      <option value='{{$role->id}}' <?php if(@old('role') == $role->id)echo"selected"; ?>>{{$role->name}}</option>
                                   @endforeach
                                </select>
                                <i class="fa-solid fa-caret-down"></i>
                                <span class="text-danger error-role"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    <div class="col-md-12 mt-3 mb-3">
                        <div class="form-group ad-user">
                            <!-- <label for="image">Images</label> -->
                            <div class="upload-box" id="uploadBox">
                                <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image</span>
                                <input type="file" id="image" name="image" accept="image/*">
                            </div>
                            <span class="text-danger error-image"></span>
                        </div>
                        <img id="editImage" src="" class="editt-img">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-save">Add User</button>
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

    $(document).on('click', '.editUser', function(e){
        var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false 
        });

        var id = $(this).attr('id');
        var url = "{{ route('admin.user.edit', ':id') }}";
        url = url.replace(':id', id);
        if(id){
            $.ajax({
                url: url, 
                type: "GET",
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        $('#name').val(response.data.name);
                        $('#email').val(response.data.email);
                        $('#id').val(response.data.id);
                        $('#status').val(response.data.status).change();
                        $('#role').val(response.data.roles['0'].id).change();
                        $("#editImage").attr('src', response.data.image)
                        $("#formHeading").text('Edit Category')
                        $(".btn").text('Update Category')
                        modal.show();
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                }
            });
        }
    })

    $(document).ready(function(){

        var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false 
        });

        $('#user').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.user.save') }}", 
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.text-danger').text('');
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        toastr.success(response.message)
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
                    if (errors.image) {
                        $(".error-image").text(errors.image[0]);
                    }
                    modal.show();
                }
            });
        });

        $(".toggleSwitch").on("change", function(){
           var status = $(this).is(":checked") ? 1 : 0;
           var id = $(this).val();

           $.ajax({
              url: "{{ route('admin.user.update.status') }}",
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
    });

    function toggleDropdown() {
        var dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("active");
    }
</script>
@endpush