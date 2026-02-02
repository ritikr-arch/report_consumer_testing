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
                                    Posts
                                </h4>
                            </div>



                            <div class="col-12 col-md-7 col-lg-7">
                                <div class="search-btn1 text-end">
                                    <button class="d-fle btn btn-success btn-sm" onclick="toggleDropdown()">
                                        <i class="fa-solid fa-filter"></i>&nbsp;Filter </button>
                                     @can('post_create')
                                    <button class="d-fle btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal" data-toggle="tooltip" data-placement="top" title="Create Posts">
                                        <i class="fa-solid fa-plus"></i>&nbsp;Posts </button>
                                    @endcan
                                </div>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <form action="{{route('admin.news.filter')}}" method="get">
                                <hr>
                                <div id="dropdown" class="dropdown-container-filter">
                                    <div class="name-input">
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Title" value="{{ request('name') }}">
                                    </div>
                                    <select class="form-select" name="type" aria-label="Default select example">
                                        <option value="" selected="">Type</option>
                                        <option {{ request('type') === 'article' ? 'selected' : '' }} value="article">
                                            Article</option>
                                        <option {{ request('type') === 'blog' ? 'selected' : '' }} value="blog">Blog
                                        </option>
                                        
                                    </select>
                                    <select class="form-select" name="status" aria-label="Default select example">
                                        <option value="" selected="">Status</option>
                                        <option {{ request('status') === '1' ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ request('status') === '0' ? 'selected' : '' }} value="0">Deactive
                                        </option>
                                    </select>

                                    <button type="submit" class="d-flex searc-btn">Search</button>
                                    <a href="{{route('admin.news.updates')}}" type="button" class="btn btn-secondary btn-sm">Reset</a>

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
                                        <th style="min-width:100px;">Type</th>
                                        <th style="min-width:100px;">Image</th>
                                        <th style="min-width:100px;">Description</th>
                                        @can('post_edit')
                                        <th style="min-width:80px;">Status</th>
                                        @endcan
                                        @canany(['post_view','post_edit','post_delete'])
                                        <th style="min-width:120px;">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($data) && count($data)>0)
                                    @foreach($data as $key=>$value)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{ html_entity_decode(Str::limit(strip_tags($value->title), 50, '...')) }}</td>
                                        <td>{{ ucfirst($value->type) }}</td>
                                        <td>
                                            <?php
                                    $imageURL = '';
                                    if($value->image){
                                       $imageURL = 'admin/images/news/'.$value->image;
                                    }else{
                                       $imageURL = 'admin/images/news/news.jpg';
                                    }
                                    ?>
                                            <img src="{{asset($imageURL)}}"
                                                style="height: 60px; width:60px;border-radius: 50%;" alt="">
                                        </td>
                                       @php
                                            $shortDesc = Str::limit(strip_tags($value->description), 60, '...');
                                            $fullDesc = html_entity_decode($value->description);
                                            $modalId = 'descModal_' . $value->id;
                                        @endphp

                                        <td>
                                            {!! $shortDesc !!}
                                            @if(strlen(strip_tags($value->description)) > 60)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">Read More</a>
                                            @endif
                                        </td>

                                        <!-- Modal -->
                                        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="{{ $modalId }}Label">Full Description</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {!! $fullDesc !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @can('post_edit')
                                        <td class="active-bt">
                                            <label class="switch">
                                                <input type="checkbox" value="{{$value->id}}" class="toggleSwitch"
                                                    {{ $value->status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        @endcan
                                        @canany(['post_view','post_edit','post_delete'])
                                        <td>
                                            <div class="action-btn">
                                                <!-- @can('post_view')
                                                <a data-toggle="tooltip" data-placement="top" title="View" href="{{route('admin.news.view', $value->id)}}"><img
                                                        src="{{asset('admin/img/view-eye.png')}}" alt="view"
                                                        class="view-icon"></a>
                                                @endcan -->

                                                @can('post_edit')
                                                <img data-toggle="tooltip" data-placement="top" title="Edit" src="{{asset('admin/img/edit-2.png')}}" class="editZone" id="{{$value->id}}" alt="edit">
                                                @endcan

                                                @can('post_delete')
                                                <img data-toggle="tooltip" data-placement="top" title="Delete" src="{{asset('admin/img/trash.png')}}" data-id="{{$value->id}}" class="deletePost" alt="trash">
                                                @endcanany

                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr><td colspan="7"><p class="no_data_found">No Data found! </p></td></tr>
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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body">
                <div class="heading">
                    <h2 id="formHeading">Add Post</h2>
                    <p>Please enter the following Data</p>
                </div>

                <form method="post" id="zone" class="mt-4">
                    @csrf
                    <div class="loader"></div>
                    <div class="screen-block"></div>

                    <input type="hidden" id="id" value="" name="id">
                    <input type="hidden" class="form-control" id="slug" name="slug">

                    <div class="row">
                        <div class="col-md-12">
                      
                            <div class="form-group ad-user">
                            <label > Title <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="title" name="title">
                                <span class="text-danger error-title"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ad-user">
                                <label > Select Type <span class="text-danger">*</span> </label>
                                <div class="rela-icon">
                                    <select class="form-control" id="type" name="type">
                                        <option value="" selected disabled>Select Type *</option>
                                        <!-- <option value="press">Press</option> -->
                                        <option value="article">Article</option>
                                        <option value="blog">Blog</option>
                                        <!-- <option value="news">News</option> -->
                                    </select>
                                    <i class="fa-solid fa-caret-down"></i>
                                    <span class="text-danger error-type"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label > Upload Image <span class="text-danger">*</span> </label>
                                <div class="upload-box" id="uploadBox">
                                    <span id="fileName"><i class="fa-solid fa-upload"></i>&nbsp;Upload Image *</span>
                                    <input type="file" id="fileInput" name="image" accept="image/*">
                                </div>
                                <span class="text-danger error-image"></span>
                                <p style="font-size: 11px; font-weight: 700;"> Recommended Dimension -> 352x250 Pixels (Max) </br>  Image size should not more then 5MB </p>
                              
                            </div>
                            <img id="editImage" src="" class="editt-img" style="display: none;">
                        </div>

                        <div class="col-md-12">
                        <label > Description <span class="text-danger">*</span> </label>
                            <div class="form-group ad-user">
                                <textarea class="form-control oye-f2" name="description" id="description"
                                    placeholder="Description *"></textarea>
                                <span class="text-danger error-description"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group ad-user">
                            <label > Select Status <span class="text-danger">*</span> </label>
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
                            <button type="submit" class="btsssn btn-save">Add Post</button>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

</div>

@endsection



@push('scripts')
<script src="https://cdn.tiny.cloud/1/8ts5mbr9hypz93cm48toaybsggxg80y362fobzt0q1gcqe4l/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
$(document).ready(function () {
// Reload page when modal closes
$('#exampleModal').on('hidden.bs.modal', function () {
    location.reload();
});

    // TinyMCE Init
    tinymce.init({
        selector: '#description',
        menubar: true,
        plugins: 'link image code lists table',
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
        setup: function (editor) {
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
                        .then(response => {
                            if (!response.ok) throw new Error('HTTP Error ' + response.status);
                            return response.json();
                        })
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

    // Edit button click
    $(document).on('click', '.editZone', function () {
        const modal = new bootstrap.Modal(document.getElementById('exampleModal'), {
            backdrop: 'static',
            keyboard: false
        });

        const id = $(this).attr('id');
        let url = "{{ route('admin.news.edit', ':id') }}";
        url = url.replace(':id', id);

        if (id) {
            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {
                    if (response.success) {
                        $('#title').val(response.data.title);
                        $('#id').val(response.data.id);
                        tinymce.get('description').setContent(response.data.description);
                        $('#status').val(response.data.status).change();
                        $('#type').val(response.data.type).change();
                        $("#editImage").css('display', 'flex').attr('src', response.data.image);
                        $("#formHeading").text('Edit Post');
                        $(".btsssn").text('Update Post');
                        $('#exampleModal').modal('show');
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseJSON.errors);
                }
            });
        }
    });

    // Auto slug check
    $(document).on('keyup', '#title', function () {
        const productName = $.trim($(this).val());
        checkSlugIsUnique(productName);
    });

    // Modal cleanup
    $('#exampleModal').on('hidden.bs.modal', function () {
        document.body.classList.remove('modal-open');
        $('.modal-backdrop').remove();
    });

    // Submit form
    $('#zone').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.set('description', tinymce.get('description').getContent());

        $('.loader').show();
        $('.screen-block').show();

        $.ajax({
            url: "{{ route('admin.news.save') }}",
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
                    $('#exampleModal').modal('hide');
                    location.reload();
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                $(".text-danger").text("");
                if (errors.title) $(".error-title").text(errors.title);
                if (errors.description) $(".error-description").text(errors.description);
                if (errors.status) $(".error-status").text(errors.status);
                if (errors.type) $(".error-type").text(errors.type);
                if (errors.image) $(".error-image").text(errors.image);
                $('#exampleModal').modal('show');
            },
            complete: function () {
                $('.loader').hide();
                $('.screen-block').hide();
            }
        });
    });

    // Slug check function
    function checkSlugIsUnique(productName) {
        if (productName) {
            $.ajax({
                url: "{{ route('admin.news.unique.slug') }}",
                method: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    productName: productName
                },
                success: function (res) {
                    $('#slug').val(res.slug);
                    if (res.error === true) {
                        $('#slugError').text(res.msg).show();
                    }
                }
            });
        }
    }

    // Toggle switch status update
    $(".toggleSwitch").on("change", function () {
        const status = $(this).is(":checked") ? 1 : 0;
        const id = $(this).val();
        $.ajax({
            url: "{{ route('admin.news.update.status') }}",
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
                toastr.error("Status update failed.");
            }
        });
    });

    // Dropdown setup
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
            if (
                !$dropdownButton.is(e.target) &&
                !$dropdownMenu.is(e.target) &&
                $dropdownMenu.has(e.target).length === 0
            ) {
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