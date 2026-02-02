@extends('admin.layouts.app')
@section('title', @$title)
@section('content')
<style>
.upload-box {
    width: 100%;
    border: 2px dashed #9eb6ba;
    border-radius: 4px;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    position: relative;
    cursor: pointer;
    background-color: #fff;
    transition: background-color 0.2s ease;
    text-align: center;
}

.upload-box.dragover {
    background-color: #f0fdf4;
    border-color: #28a745;
}

.upload-box input[type="file"] {
    display: none;
}

.upload-content {
    color: #89a1a5;
}

.upload-icon {
    font-size: 24px;
    display: block;
    margin-bottom: 10px;
}

.upload-content p {
    margin: 0;
    font-size: 16px;
}

.preview-img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

.hidden {
    display: none;
}

.error-text {
    color: red;
    font-size: 14px;
    margin-top: 5px;
}
.down_icon
{
    font-size:32px;
}
</style>
<div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">

     <form action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center d-flex mb-3">
                            <div class="col-xl-12">
                                <h4 class="header-title mb-0 font-weight-bold">
                                    Profile
                                </h4>
                            </div>
                        </div>
                         <div class="col-xl-12">
                       <div class="upload-box" id="dropArea">
                                <input type="file" name="profile_image" id="profileImageUpload" accept="image/*" />
                                
                                <div class="upload-content" id="uploadPlaceholder">
                                    <i class="fa fa-download down_icon" aria-hidden="true"></i>
                                    <p>Choose an image file or drag it here.</p>
                                </div>

                                <img id="profileImagePreview" class="preview-img hidden" />
                            </div>

                            @error('profile_image')
                                <div class="error-text">{{ $message }}</div>
                            @enderror

</div>

                      
                            <div class="col-md-12">
                                <div class="form-group ad-user mt-4">
                                    <label for="exampleFormControlInput1">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Enter Name" value="{{old('name',@$data->name)}}" name="name" maxlength="200">
                                </div>
                                @error('name')
                                <small class="text-danger text-bold"> {{ $message }} </small>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ad-user mt-4">
                                    <label for="exampleFormControlInput1">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Enter Email" maxlength="200" name="email" value="{{old('email', @$data->email)}}" readonly>
                                </div>
                                @error('email')
                                <small class="text-danger text-bold"> {{ $message }} </small>
                                @enderror
                            </div>

                        </div>
                  <div class="col-md-12 p-3 text-center">
                        <button class="searc-btn mt-3">Update</button>
                     </div>   
                    </div>
                
            </div>
        </div>

        </form>
        <!-- end row-->
    </div>
    <!-- container -->
</div>

@endsection

@push('scripts')
<script>
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('profileImageUpload');
    const previewImg = document.getElementById('profileImagePreview');
    const placeholder = document.getElementById('uploadPlaceholder');

    function previewFile(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            previewFile(fileInput.files[0]);
        }
    });

    dropArea.addEventListener('click', () => fileInput.click());

    dropArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropArea.classList.add('dragover');
    });

    dropArea.addEventListener('dragleave', () => {
        dropArea.classList.remove('dragover');
    });

    dropArea.addEventListener('drop', (e) => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            previewFile(file);
        } else {
            alert('Please upload a valid image file.');
        }
    });
</script>



<script>
   const profileImageUpload = document.getElementById("profileImageUpload");
   const profileImagePreview = document.getElementById("profileImagePreview");
   
   profileImageUpload.addEventListener("change", function (event) {
     const file = event.target.files[0];
     if (file) {
       const reader = new FileReader();
       reader.onload = function (e) {
         profileImagePreview.src = e.target.result;
       };
       reader.readAsDataURL(file);
     }
   });
</script>
@endpush
