@extends('admin.layouts.app')
@section('content')

<div class="px-3">
    <!-- Start Content-->
    <div class="container-fluid">

        <form action="{{route('admin.privacy.policy.update')}}" id="policy_form" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mt-3">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center d-flex mb-3">
                                <div class="col-xl-12">
                                    <h4 class="header-title mb-0 font-weight-bold"> Privacy Policy </h4>
                                </div>
                            </div>

                            <div class="row">



                                <div class="col-md-12">
                                    <div class="form-group ad-user mt-4">
                                        <label for="exampleFormControlInput1">Content <span class="text-danger">*</span></label>
                                        <textarea class=" form-control oye-f2" name="content" id="content">{{ old ('content', @$data->content)}}</textarea>

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
$(document).ready(function () {
    // Custom validator to prevent only spaces/HTML tags
    $.validator.addMethod("noSpaceOnly", function (value, element) {
        var strippedValue = $("<div>").html(value).text().trim();
        return strippedValue.length > 0;
    }, "This field cannot contain only spaces.");

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

        setup: function (editor) {
            // Add custom upload image button
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

            // TinyMCE content validation on form submit
            editor.on('init', function () {
                $("#policy_form").validate({
                    ignore: [],
                    rules: {
                        content: {
                            required: function () {
                                tinymce.triggerSave(); // Updates the textarea
                                var content = $('<div>').html($('#content').val()).text().trim();
                                return content.length === 0;
                            },
                            noSpaceOnly: true
                        }
                    },
                    messages: {
                        content: {
                            required: "Content field is required",
                            noSpaceOnly: "Content field is required"
                        }
                    },
                    submitHandler: function (form) {
                        tinymce.triggerSave();
                        form.submit();
                    }
                });
            });
        }
    });
});
</script>

@endpush
