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
                                    Users
                                </h4>
                            </div>

                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()"><i
                                            class="fa-solid fa-filter"></i>&nbsp;Filter</button>
                                    @can('users_create')

                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Users"><i class="fa-solid fa-plus"></i>&nbsp;
                                        Users</button>

                                    <a class="btn btn-secondary btn-sm" href="{{route('admin.user.export', request()->query())}}" title="">
                                    <i class="fas fa-file-download"></i> Export</a>
                                    
                                    @endcan

                                </div>
                            </div>




                            <!-- <div class="col-12 col-md-5 col-lg-5">
                        	<div class="search-btn">
                           		<div>
	                              <button class="d-flex" onclick="toggleDropdown()"><i class="fa-solid fa-filter"></i>&nbsp;Filter</button>
	                           	</div>
                           		<div >
                              		<button class="d-flex" data-bs-toggle="modal" data-bs-target="#exampleModal" ><i class="fa-solid fa-plus"></i>&nbsp;Users</button>
                           		</div>
                            
                        	</div>
                     	</div> -->
                        </div>
                        <div class="row mb-4">

                            <form action="{{route('admin.user.filter')}}" method="get">
								<hr>
                                <div id="dropdown" class="dropdown-container-filter">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name"
                                            id="exampleFormControlInput1" placeholder="Name"
                                            value="{{ request('name') }}">
                                    </div>
                                    <div class="email-input">
                                        <input type="text" class="form-control" name="email" id="email"
                                            placeholder="Email" value="{{ request('email') }}">
                                    </div>
                                    <select class="form-select" name="status" aria-label="Default select example">
                                        <option value="" selected="">Status</option>
                                        <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive
                                        </option>
                                    </select>

                                    <select class="form-select form-control" id="role" name="role">
                                        <option value="">Role</option>

                                        @foreach($roles as $role)
                                        <option {{ request('role') == $role->id ? 'selected' : '' }}
                                            value='{{$role->id}}' <?php if(@old('role') == $role->id)echo"selected"; ?>>
                                            {{$role->name}}</option>
                                        @endforeach

                                    </select>

                                    <button type="submit" class="d-flex searc-btn">Search</button>
                                    <a href="{{ route('admin.user.list') }}" type="button" class="btn btn-secondary btn-sm">Reset</a>

                                    <!-- <a href="{{route('admin.user.list')}}">Reset</a> -->

                                </div>

                            </form>

                        </div>

                        <div class="table-responsive white-space">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr class="border-b bg-light2">

                                        <th style="min-width:50px;">S.No.</th>
                                        <th style="min-width:100px;">Image</th>
                                        <th style="min-width:200px;">Name</th>
                                        <th style="min-width:200px;">Email Id</th>
                                        <th style="min-width:100px;">Role</th>
                                        @can('users_edit')
                                        <th style="min-width:100px;">Status</th>
                                        @endcan
                                         @canany(['users_edit','users_delete'])
                                        <th style="min-width:200px;">Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($users) && count($users)>0)


                                    @foreach($users as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>
                                            <div class="market-img">
                                                <img style="height: 40px; width: 40px; border-radius: 25%;" 
                                                    src="{{ !empty($value->image) ? asset('admin/images/user/'.$value->image) : asset('admin/images/user/default.png') }}" 
                                                    alt="market">

                                            </div>
                                        </td>
                                        <td>{{ucfirst($value->title)}} {{ucfirst($value->name)}}</td>
                                        <td>{{$value->email}}</td>
                                        <td>
                                            @if ($value->roles->isNotEmpty())
                                            {{ $value->roles->pluck('name')->implode(', ') }}
                                            @else
                                            No Role Assigned
                                            @endif
                                        </td>
                                        @can('users_edit')

                                        <td class="active-bt">
                                            <!-- {{($value->status == '1')?'Active':'Deactive'}} -->
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        @endcan
                                        @canany(['users_edit','users_delete'])

                                        <td>

                                            <div class="action-btn">

                                                <!-- <a data-toggle="tooltip" data-placement="top" title="View" href="{{route('admin.user.view', $value->id)}}"><img
                                                        src="{{asset('admin/img/view-eye.png')}}" alt="view"
                                                        class="view-icon"></a> -->
                                                @can('users_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editUser"
                                                    id="{{$value->id}}" alt="edit">
                                                     @endcan

                                                @can('users_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}"
                                                    class="deleteUser" alt="trash">
                                                     @endcan
                                            </div>
                                        </td>
                                        @endcanany
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7">
                                            <p class="no_data_found">No User found! </p>
                                        </td>
                                    </tr>

                                    @endif
                                </tbody>
                            </table>
                            @if(isset($users))
                            {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="heading">
                    <h2 id="formHeading">Add User</h2>
                    <p>Please enter the following Data</p>
                </div>
                <form method="post" class="mt-4" id="user">
                    @csrf
                    <div class="row">
                         <div class="col-md-6">
                            <input type="hidden" id="id" value="" name="id">
                            <label>Title <span class="text-danger">*</span></label>

                            <div class="form-group ad-user">
                                <select class="form-control" name="title" id="title">
                                    <option value="">-- Select Title --</option>
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Dr.">Dr.</option>
                                    <option value="Prof.">Prof.</option>
                                </select>
                                <span class="text-danger error-title"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            
                            <label>User Name <span class="text-danger">*</span></label>

                            <div class="form-group ad-user">
                                <input type="text" class="form-control" name="name" id="name" >
                                <span class="text-danger error-name"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                        <label>User Email <span class="text-danger">*</span></label>
                            <div class="form-group ad-user">
                                <input type="email" class="form-control" name="email" id="emails"
                                    >
                                <span class="text-danger error-email"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ad-user">
                                <!-- <label for="status">Select Status</label> -->
                                <label>Select Role <span class="text-danger">*</span></label>
                                <div class="rela-icon">
                                    <select class="form-control" id="roles" name="role">
                                        <option value="">Select Role *</option>
                                        @foreach($roles as $role)
                                        <option value='{{$role->id}}'
                                            <?php if(@old('role') == $role->id)echo"selected"; ?>>{{$role->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-role"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label>Select status <span class="text-danger">*</span></label>
                                <div class="rela-icon">
                                    <select class="form-control" id="statuss" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Deactive</option>
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-status"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3 mb-3">
                            <div class="form-group ad-user">
                            <label>Upload Image <span class="text-danger"></span></label>
                                <div class="upload-box" id="uploadBox">
                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image </span>
                                    <input type="file" id="image" name="image" accept="image/*">
                                </div>
                                <span class="font-text">Only .jpg, .jpeg and .png are accepted. Maximum file size: 2 MB.</span><br>
                                <span class="text-danger error-image"></span>
                            </div>
                            <img id="editImage" src="" class="editt-img" style="display: none;">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btnss btn-save">Add User</button>
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

$(document).on('click', '.editUser', function(e) {
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });
    var id = $(this).attr('id');
    var url = "{{ route('admin.user.edit', ':id') }}";
    url = url.replace(':id', id);
    if (id) {
        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                if (response.success) {

                   if (response.data.title) {
                   $('#title').val(response.data.title).change();
                    }
                    $('#name').val(response.data.name);
                    $('#emails').val(response.data.email);
                    $('#id').val(response.data.id);
                    $('#statuss').val(response.data.status).change();
                 
                    if (response.data.roles && response.data.roles.length > 0) {
                        $('#roles').val(response.data.roles[0].id).change();
                    }
                    $("#editImage").css('display', 'flex')
                    $("#editImage").attr('src', response.data.image)
                    $("#formHeading").text('Edit User')
                    $(".btnss").text('Update User')
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
    var modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        backdrop: 'static',
        keyboard: false
    });
    $('#user').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('admin.user.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('.text-danger').text('');
            },

            success: function(response) {
                // console.log(response);
                if (response.success) {
                    toastr.success(response.message)
                    modal.hide();
                    location.reload();
                }

            },

            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                $(".text-danger").text("");
                 if (errors.title) {
                    $(".error-title").text(errors.title[0]);
                }
                if (errors.name) {
                    $(".error-name").text(errors.name[0]);
                }
                if (errors.email) {
                    $(".error-email").text(errors.email[0]);
                }
                if (errors.role) {
                    $(".error-role").text(errors.role[0]);
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



    $(".toggleSwitch").on("change", function() {
        var status = $(this).is(":checked") ? 1 : 0;
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.user.update.status') }}",
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
                    location.reload();
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



    // Multi-select dropdown setup

    const $dropdownButton = $('#dropdownButton');

    const $dropdownMenu = $('#dropdownMenu');

    const $checkboxes = $dropdownMenu.find("input[type='checkbox']");

});


$('#exampleModal').on('hidden.bs.modal', function() {
    $(this).find('form')[0].reset();
    $('#id').val('');
    $("#editImage").hide().attr('src', '');
    $("#formHeading").text('Add User');
    $(".btn-save").text('Add User');

    document.body.classList.remove('modal-open');
    $('.modal-backdrop').remove();
});

function toggleDropdown() {
    let dropdown = document.getElementById("dropdown");
    dropdown.classList.toggle("active");
}



window.onload = function() {
    let params = new URLSearchParams(window.location.search);
    if (params.has('name') || params.has('email') || params.has('status') || params.has('role')) {
        // document.getElementById("dropdown").style.display = "block";
        let dropdown = document.getElementById("dropdown");
        dropdown.classList.toggle("active");
    }

};
</script>

@endpush