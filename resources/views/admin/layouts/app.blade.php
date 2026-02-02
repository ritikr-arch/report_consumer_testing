<?php 
use App\Models\Setting;
$data =  Setting::first();

?>

<!DOCTYPE html>

<html lang="en" data-bs-theme="light" data-menu-color="light">

<head>

    <meta charset="utf-8" />

    <title>{{ config('app.name', 'Consumer Affairs') }} | @yield('title',  'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->

  
    @if(isset($data) && $data->favicon != '')

    <link rel="shortcut icon" href="{{ asset('admin/images/company_setting/'. $data->favicon)}}">
    @else
    <link rel="shortcut icon" href="{{ asset('admin/img/favicon.png')}}">
    @endif

    <link href="{{ asset('admin/libs/morris.js/morris.css')}}" rel="stylesheet" type="text/css" />

    <!-- App css -->
 <link href="{{ asset('admin/libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/css/style.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{ asset('admin/css/style.css')}}" rel="stylesheet" type="text/css">

    <link href="{{ asset('admin/css/icons.min.css')}}" rel="stylesheet" type="text/css">

    <script src="{{ asset('admin/js/config.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <link href="{{asset('admin/js/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('admin/css/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css"
        integrity="sha512-v8QQ0YQ3H4K6Ic3PJkym91KoeNT5S3PnDKvqnwqFD1oiqIl653crGZplPdU5KKtHjO0QKcQ2aUlQZYjHczkmGw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-css/1.4.6/select2-bootstrap.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    

</head>



<body>



    <body>



        @include('admin.layouts.sidebar')

        @include('admin.layouts.header-top')

        @yield('content')

        <script src="{{ asset('admin/js/vendor.min.js')}}"></script>

       

        <script src="{{ asset('admin/libs/jquery-sparkline/jquery.sparkline.min.js')}}"></script>

        <script src="{{ asset('admin/libs/jquery-knob/jquery.knob.min.js')}}"></script>

        <script src="{{ asset('admin/libs/morris.js/morris.min.js')}}"></script>

        <script src="{{ asset('admin/libs/raphael/raphael.min.js')}}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

        <script src="{{asset('admin/js/toastr.min.js')}}"></script>

        <script src="{{asset('admin/js/sweetalert2.all.min.js')}}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
        <script src="{{ asset('admin/libs/dropzone/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('admin/js/pages/form-fileuploads.js') }}"></script>
         <script src="{{ asset('admin/js/app.js')}}"></script>
        @include('admin.layouts.common')


        <script>
           $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        })
        </script>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/8ts5mbr9hypz93cm48toaybsggxg80y362fobzt0q1gcqe4l/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
        tinymce.init({
    selector: '#content',
      menubar: true,
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
</script>

        <script>
        @if(Session::has('success'))

        toastr.options = {

            "closeButton": true,

            "progressBar": true,



        }

        toastr.success("{{ session('success') }}");

        @endif



        @if(Session::has('error'))

        toastr.options = {

            "closeButton": true,

            "progressBar": true

        }

        toastr.error("{{ session('error') }}");

        @endif
        </script>

<script>
const dateObj = new Date();

// Format for flatpickr: "dd-mm-yyyy"
const day = String(dateObj.getDate()).padStart(2, '0');
const month = String(dateObj.getMonth() + 1).padStart(2, '0');
const year = dateObj.getFullYear();
const todayFormatted = `${day}-${month}-${year}`;

// Format for native input: "yyyy-mm-dd"
const todayNative = dateObj.toISOString().split('T')[0];

// 1️⃣ .future_date → allow all dates
document.querySelectorAll('input[type="date"].future_date').forEach(input => {
  input.placeholder = "dd-mm-yyyy";
  flatpickr(input, {
    dateFormat: "d-m-Y"
    // No minDate or maxDate => all dates allowed
  });
});

// 2️⃣ All other inputs → allow past dates and today (no future beyond today)
document.querySelectorAll('input[type="date"]:not(.future_date)').forEach(input => {
  input.placeholder = "dd-mm-yyyy";
  input.setAttribute('max', todayNative); // Native browser support

  flatpickr(input, {
    dateFormat: "d-m-Y",
    maxDate: todayFormatted // Disable dates after today
  });
});

</script>


        @stack('scripts')

    </body>

</html>