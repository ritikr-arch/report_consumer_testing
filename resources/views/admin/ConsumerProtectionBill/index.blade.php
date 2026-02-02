@extends('admin.layouts.app')
@section('content')

<div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">

        <form action="{{route('admin.consumer.protectio.bill.update')}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mt-3">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center d-flex mb-3">
                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> Consumer Protection Bill Update                                    </h4>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    
                                              <label for="exampleFormControlInput1">Title  <span
                                                class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="exampleFormControlInput1"
                                            placeholder="Enter Title" maxlength="250" name="title" value="{{old('title', @$data->title)}}" >
                                            @error('title')
                                            <small class="text-danger text-bold"> {{ $message }} </small>
                                            @enderror
                                        </div>
                               
                                 <div class="col-md-4">
                                    <label for="exampleFormControlInput1">Image Upload <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="imagess" class="form-control" accept="image/*"> 
                                    @error('imagess') <small class="text-danger text-bold"> {{ $message }} </small> @enderror
                                    <p style="font-size: 11px; font-weight: 700;"> Recommended Dimension -> 600x400 Pixels (Max) </br> Image size should not more then 5MB </p>
                                </div>
                                <div class="col-md-2">
                                    <div class="profile-background">
                                       @if(!empty($data->image))
                                        <img src="{{ asset('admin/images/cms/'.@$data->image) }}" alt="" id="companyLogoPreview" class="profile-image" width="100" height="100"/>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group ad-user mt-4">
                                        <label for="exampleFormControlInput1">Content  <span
                                                class="text-danger">*</span></label>
                                                <textarea class="form-control oye-f2"  name="content" id="content" >{{ old ('content', @$data->content)}}</textarea>

                                    </div>
                                    @error('content')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <input type="hidden" name="id" value="{{@$data->id}}"> 
                            </div>

                            <button class="searc-btn mt-3">Update </button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <!-- container -->
    </div>
    @endsection

    @push('scripts')
    <script>
    function previewImage(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        input.addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    previewImage("companyLogoUpload", "companyLogoPreview");
    // previewImage("faviconUpload", "faviconPreview");
    </script>

    @endpush