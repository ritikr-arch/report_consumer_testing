@extends('admin.layouts.app')
@section('content')


<div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">

        <form action="{{route('admin.setting.update')}}" id="setting_form" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mt-3">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center d-flex mb-3">
                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> Company Setting </h4>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <label >Image Upload <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    @error('image') <small class="text-danger text-bold"> {{ $message }} </small> @enderror
                                   <span class="font-text">Recommended Dimension -> 200x70 Pixels (Max)
                                    Image size should not more then 5MB</span> 
                                    <br>
                                    @error('logo') <small class="text-danger text-bold"> {{ $message }} </small> @enderror
                                    <br>
                                    <small class="text-danger logo-error"></small>
                                </div>
                                <div class="col-md-2">
                                    <div class="profile-background">
                                        @if(!empty($data->company_image))
                                        <img src="{{ asset('admin/images/company_setting/'.$data->company_image) }}" alt="" id="companyLogoPreview" class="profile-image" width="100" height="100" />
                                        @endif
                                    </div>
                                   
                                </div>

                                <div class="col-md-4">
                                    <label >Favicon <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="favicon" class="form-control" accept="image/*">
                                     <span class="font-text">
                                    Recommended Dimension -> 64x64 Pixels (Max)
                                    Image size should not more then 5MB
                                    </span>
                                    <br>
                                    @error('favicon') <small class="text-danger text-bold"> {{ $message }} </small> @enderror
                                    <br>
                                    <small class="text-danger favicon-error"></small>
                                </div>
                                <div class="col-md-2">
                                    <div class="profile-background">
                                        @if(!empty($data->favicon))
                                        <img src="{{ asset('admin/images/company_setting/'.$data->favicon) }}" alt="" id="companyLogoPreview" class="profile-image" width="100" height="100" />
                                        @endif
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">

                                        <label >Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Title" value="{{old('title',@$data->company_title)}}" name="title" maxlength="200">
                                    </div>

                                    @error('title')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Address " value="{{old('address',@$data->company_address)}}" name="address" maxlength="200">
                                    </div>
                                    @error('address')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label > Email ID <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control"  placeholder="Enter Email" maxlength="200" name="email" value="{{old('email', @$data->email_address)}}">
                                    </div>
                                    @error('email')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Phone No. <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control"  name="phone" placeholder="Enter Numbaer" value="{{old ('phone', @$data->phone)}}" maxlength="150" minlength="8">
                                    </div>
                                    @error('phone')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Registration Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  name="registration_number" placeholder="Registration Number" maxlength="250" value="{{old('registration_number',@$data->company_registration_no)}}">
                                    </div>
                                    @error('registration_number')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> SMTP Setting </h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Host <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Host" value="{{old('host',@$data->host)}}" name="host" maxlength="200">
                                    </div>
                                    @error('host')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Prot Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Prot Number " value="{{old('port',@$data->port)}}" name="port" maxlength="200">
                                    </div>
                                    @error('port')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label > User Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter User Name" maxlength="200" name="user_name" value="{{old('user_name', @$data->user_name)}}">
                                    </div>
                                    @error('user_name')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Password <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control"  name="password" placeholder="Enter Password" value="{{old ('password', @$data->password)}}" maxlength="15">
                                    </div>
                                    @error('password')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> Socal Media </h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Facebook Url <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Facebook" value="{{old('facebook',@$data->social_fb)}}" name="facebook" maxlength="200">
                                    </div>
                                    @error('facebook')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Instagram Url <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter Instagram " value="{{old('instagram',@$data->social_instagram)}}" name="instagram" maxlength="200">
                                    </div>
                                    @error('instagram')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label > Twitter Url<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control"  placeholder="Enter twitter" maxlength="200" name="twitter" value="{{old('twitter', @$data->social_twitter)}}">
                                    </div>
                                    @error('twitter')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Linked in <span class="text-danger">*</span></label>

                                        <input type="text" class="form-control"  name="linked" placeholder="Enter Password" value="{{old ('linked', @$data->linked_in)}}" maxlength="15">
                                    </div>
                                    @error('linked')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Date Format <span class="text-danger">*</span></label>
                                        <select name="date_format" id="date_format" class="form-control">
                                            @php
                                                $formats = [
                                                    'd-m-Y' => 'dd-mm-yyyy',
                                                    'm/d/Y' => 'mm/dd/yyyy',
                                                    'Y-m-d' => 'yyyy-mm-dd',
                                                    'd/m/Y' => 'dd/mm/yyyy',
                                                    'M d, Y' => 'Mon dd, yyyy',
                                                    'd M Y' => 'dd Mon yyyy',
                                                ];
                                            @endphp

                                            @foreach($formats as $key => $label)
                                                <option value="{{ $key }}" {{ (old('date_format', @$data->date_format) == $key) ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                    @error('date_format')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>



                                 <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Price Collection Heading<span class="text-danger">*</span></label>

                                        <input type="text" class="form-control" name="price_collection" maxlength="100" placeholder="Price Collection Heading" value="{{ old('price_collection', @$data->price_collection) }}">

                                    </div>
                                    @error('price_collection')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group ad-user mt-4">
                                        <label >Admin Email<span class="text-danger">*</span></label>

                                        <input type="email" class="form-control"  name="admin_email" placeholder="Admin Email" value="{{old ('admin_email', @$data->admin_email)}}">
                                    </div>
                                    @error('admin_email')
                                    <small class="text-danger text-bold"> {{ $message }} </small>
                                    @enderror
                                </div>

                            </div>

                            <button class="searc-btn mt-3">Update</button>
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
$(document).ready(function () {
    // Prevent default form submission
    $('#setting_form').on('submit', function (e) {
        e.preventDefault(); // Form submit pehle se prevent rahega
    });

    // Custom method: prevent only-space input
    $.validator.addMethod("noSpaceOnly", function (value) {
        return $.trim(value).length > 0;
    }, "This field cannot contain only spaces.");

    // Image validation function
    function validateImage(file, maxWidth, maxHeight, maxSizeMB) {
        return new Promise((resolve) => {
            const fileSizeMB = file.size / (1024 * 1024);
            if (fileSizeMB > maxSizeMB) {
                resolve(false);
                return;
            }

            const img = new Image();
            const objectUrl = URL.createObjectURL(file);

            img.onload = function () {
                URL.revokeObjectURL(objectUrl);
                if (this.width > maxWidth || this.height > maxHeight) {
                    resolve(false);
                } else {
                    resolve(true);
                }
            };

            img.onerror = function () {
                resolve(false);
            };

            img.src = objectUrl;
        });
    }

    // jQuery Validate setup
    $("#setting_form").validate({
        ignore: [],
        rules: {
            title: { required: true, maxlength: 250, noSpaceOnly: true },
            address: { required: true, maxlength: 250, noSpaceOnly: true },
            email: { required: true, email: true },
            phone: { required: true, minlength: 8, maxlength: 250 },
            registration_number: { required: true, maxlength: 250, noSpaceOnly: true },
            host: { required: true, maxlength: 200, noSpaceOnly: true },
            port: { required: true, digits: true, maxlength: 200 },
            user_name: { required: true, maxlength: 200, noSpaceOnly: true },
            password: { required: true, maxlength: 250, noSpaceOnly: true },
            facebook: { required: true, url: true },
            instagram: { required: true, url: true },
            twitter: { required: true, url: true },
            linked: { required: true, url: true }
        },
        messages: {
            title: { required: "Title is required.", maxlength: "Max 250 characters" },
            address: { required: "Address is required.", maxlength: "Max 250 characters" },
            email: { required: "Email is required.", email: "Invalid email" },
            phone: { required: "Phone is required.", minlength: "Min 8 digits", maxlength: "Max 250 characters" },
            registration_number: { required: "Registration number is required.", maxlength: "Max 250 characters" },
            host: { required: "Host is required.", maxlength: "Max 200 characters" },
            port: { required: "Port is required.", digits: "Only digits are allowed", maxlength: "Max 200 characters" },
            user_name: { required: "Username is required.", maxlength: "Max 200 characters" },
            password: { required: "Password is required.", maxlength: "Max 250 characters" },
            facebook: { required: "Facebook URL is required.", url: "Invalid URL" },
            instagram: { required: "Instagram URL is required.", url: "Invalid URL" },
            twitter: { required: "Twitter URL is required.", url: "Invalid URL" },
            linked: { required: "LinkedIn URL is required.", url: "Invalid URL" }
        },

        // Only called when form passes validation
        submitHandler: async function (form) {
            $(".logo-error, .favicon-error").remove(); // remove old errors

            const logoInput = $('input[name="logo"]')[0];
            const faviconInput = $('input[name="favicon"]')[0];
            let hasError = false;

            // Validate logo
            if (logoInput.files.length > 0) {
                const valid = await validateImage(logoInput.files[0], 200, 70, 5);
                if (!valid) {
                    $('<small class="text-danger logo-error d-block">Logo must be max 200x70px and < 5MB.</small>').insertAfter($(logoInput));
                    hasError = true;
                }
            }

            // Validate favicon
            if (faviconInput.files.length > 0) {
                const valid = await validateImage(faviconInput.files[0], 64, 64, 5);
                if (!valid) {
                    $('<small class="text-danger favicon-error d-block">Favicon must be max 64x64px and < 5MB.</small>').insertAfter($(faviconInput));
                    hasError = true;
                }
            }

            if (hasError) {
               // ❌ If image validation failed, scroll to top
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                return false;
            }

            // ✅ All validations passed - submit the form
            form.submit(); // Now we manually submit the form
        }
    });
});

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
        previewImage("faviconUpload", "faviconPreview");
    </script>

    @endpush