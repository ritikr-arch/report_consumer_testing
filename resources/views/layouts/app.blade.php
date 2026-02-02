<html lang="en" data-bs-theme="light" data-menu-color="light" data-topbar-color="dark">
   <head>
      <meta charset="utf-8" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta content="" name="description" />
      <meta content="Myra Studio" name="author" />
      <!-- App favicon -->
      <link rel="shortcut icon" href="{{ asset('admin/img/favicon.png')}}">
      <link href="{{ asset('admin/libs/morris.js/morris.css')}}" rel="stylesheet" type="text/css" />
      <!-- App css -->
      <link href="{{ asset('admin/css/style.min.css')}}" rel="stylesheet" type="text/css">
      <link href="{{ asset('admin/css/style.css')}}" rel="stylesheet" type="text/css">
      <link href="{{ asset('admin/css/icons.min.css')}}" rel="stylesheet" type="text/css">
      <script src="{{ asset('admin/js/config.js')}}"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/fontawesome.min.css" integrity="sha512-v8QQ0YQ3H4K6Ic3PJkym91KoeNT5S3PnDKvqnwqFD1oiqIl653crGZplPdU5KKtHjO0QKcQ2aUlQZYjHczkmGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');
       
        </style>
    </head>
    <body>
        @yield('content')
        
    <script src="{{ asset('admin/js/vendor.min.js')}}"></script>
    <script src="{{ asset('admin/js/app.js')}}"></script>
    </body>
</html>