<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>Pages / Not Found 404 | Kiibo Go</title>
    <link rel="icon" type="image/x-icon" href="{{ Asset('assets/img/logo.png') }}"/>
    <link rel="icon" href="{{ Asset('assets/img/logo.png') }}" type="image/png" sizes="16x16">

    <!-- NewsStyles --> 
        <link href="{{ Asset('assets/css/bundle.min.css') }}" rel="stylesheet"> 
        <link href="{{ Asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
        <link href="{{ Asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
      
        <!-- Template Main CSS File -->
        <link href="{{ Asset('assets/css/style.css') }}" rel="stylesheet">

</head>
<body>
  
    <main>
        <div class="container">

            <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1>404</h1>
            <h2>La página que buscas no existe.</h2>
            <a class="btn" href="{{url('./')}}">Volver al inicio</a>
            <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
                Designed by <a href="https://kiibo.mx" target="_blank">Kiibo Go</a>
            </div>
            </section>

        </div>
    </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
 <!-- Vendor JS Files -->
 <script src="{{ Asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/chart.js/chart.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/echarts/echarts.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/quill/quill.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
 <script src="{{ Asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
 <script src="{{ Asset('assets/vendor/php-email-form/validate.js') }}"></script>

 <!-- Template Main JS File -->
 <script src="{{ Asset('assets/js/main.js') }}"></script>
</body>
</html>