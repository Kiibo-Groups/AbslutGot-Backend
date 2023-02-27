<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{Asset('assets/img/logo.png') }}"/>
    <link rel="icon" href="{{Asset('assets/img/logo.png')}}" type="image/png" sizes="16x16">

    <!-- NewsStyles -->  
    <link href="{{ Asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{ Asset('assets/vendor/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ Asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

@yield('css')

</head>
<body>

    <header id="header" class="header fixed-top d-flex align-items-center">
        @include('admin.layout.header')
    </header>

    <aside id="sidebar" class="sidebar">
        @include('admin.layout.menu')
    </aside>

    <main id="main" class="main">
        <div class="pagetitle"> 
            <h1>@yield('title')</h1>
             
            <nav>
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('./')}}">Home</a></li>
                <li class="breadcrumb-item active">@yield('breadcrumb')</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            @if(Session::has('error'))
            <div class="col-lg-12">
                <div class="row" style="text-align: left">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>ERROR : </strong> {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            @if(Session::has('message'))
            <div class="col-lg-12">
                <div class="row" style="text-align: left">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>SUCCESS : </strong> {{ Session::get('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color:white">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')
        </section>
    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
        &copy; Copyright <strong><span>KiiboGo</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
        Designed by <a href="https://kiibo.mx" target="_blank">Kiibo</a>
        </div>
    </footer><!-- End Footer -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Vendor JS Files -->
    
    <script src="{{Asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/chart.js/chart.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ Asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ Asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ Asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>

    <script src="{{ Asset('assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ Asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ Asset('assets/js/main.js') }}"></script>
    <script src="{{ Asset('assets/vendor/sweetalert/sweetalert2.all.min.js') }}"></script>

    <script>
        function deleteConfirm(url){
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Deleted!',
                            'This Entry has been deleted.',
                            'success'
                        )

                        window.location = url;
                    }
                })
        }

        function confirmAlert(url){
            Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Do it!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire(
                            'Changed!',
                            'This Entry has been Changed.',
                            'success'
                        )

                        window.location = url;
                    }
            })
        }

        function showMsg(data)
        {
            Swal.fire(data);
        }
    </script>

    @yield('js')

</body>
</html>