<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>Panel de Administración</title>
    <link rel="icon" type="image/x-icon" href="{{ Asset('assets/img/logo.png') }}"/>
    <link rel="icon" href="{{ Asset('assets/img/logo.png') }}" type="image/png" sizes="16x16">

    <!-- NewsStyles -->  
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

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="{{url('./')}}" class="logo d-flex align-items-center w-auto">
                  <span class="d-none d-lg-block" style="text-align: center;">Bienvenido(a) a<br /> Absolut<strong>GO</strong></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                    <div class="pt-4 pb-2">
                        <h5 class="card-title text-center pb-0 fs-4">Panel Administrativo</h5>
                        <p class="text-center small">Ingresa tus datos de acceso como Administrador.</p>
                    </div>

                        
                    <form class="row g-3 needs-validation" novalidate action="{{ $form_url }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-12">
                            <label for="yourUsername" class="form-label">Username</label>
                            <div class="input-group has-validation">
                                <input class="form-control" type="text" name="username" id="yourUsername" placeholder="Usuario" required="required">
                                <div class="invalid-feedback">Please enter your username.</div>
                            </div>
                        </div>

                        <div class="col-12" style="padding-top:10px;">
                            <label for="yourPassword" class="form-label">Password</label>
                            <input class="form-control" type="password" name="password" id="yourPassword" placeholder="Contraseña" required="required">
                            <div class="invalid-feedback">Please enter your password!</div>
                        </div>

                        <div class="col-12" style="padding-top:10px;"> 
                            <button id="submit" type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </div>
                        
                        <div class="col-12" style="padding-top:10px;"> 
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong> ERROR : </strong> {{ Session::get('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
        
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    SUCCESS : </strong> {{ Session::get('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>

                    </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="https://kiibo.mx" target="_blank">Kiibo Groups</a>
              </div>

            </div>
          </div>
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