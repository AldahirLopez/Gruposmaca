<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Grupo SMACA</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #c8e6c9);
            font-family: 'Open Sans', sans-serif;
        }

        .card {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            margin: 1rem auto;
            background-color: #fff;
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: none;
        }

        .back-to-top {
            background-color: #28a745;
        }

        .logo-container {
            text-align: center;
            margin: 1rem 0;
        }

        .logo-container img {
            max-width: 150px;
        }

        .login-header {
            background-color: #28a745;
            color: #fff;
            padding: 1rem;
            border-radius: 1rem 1rem 0 0;
            text-align: center;
        }

        .login-header h5 {
            margin: 0;
            font-size: 1.5rem;
        }

        .login-header p {
            margin: 0;
            font-size: 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        .text-center a {
            color: #28a745;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .social-login {
            text-align: center;
            margin: 1rem 0;
        }

        .social-login button {
            margin: 0.5rem;
            width: 100%;
            max-width: 240px;
            display: inline-block;
        }
    </style>

</head>

<body>

    <main>
        <div class="container">

            <div class="logo-container">
                <img src="assets/img/logoarmonia.png" alt="Logo">
            </div>

            <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="card">

                        <div class="login-header">
                            <h5>Iniciar Sesión</h5>
                            <p>Ingresa tu correo y contraseña</p>
                        </div>

                        <div class="card-body">

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <input type="email" name="email" class="form-control" id="email" required>
                                        <div class="invalid-feedback">Por favor, ingresa tu correo.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                    <div class="invalid-feedback">Por favor, ingresa tu contraseña.</div>
                                </div>
                                <div class="mb-3 d-grid">
                                    <button class="btn btn-primary" type="submit">Iniciar Sesión</button>
                                </div>

                                @if ($errors->has('error'))
                                    <div class="alert alert-danger mt-3" role="alert">
                                        {{ $errors->first('error') }}
                                    </div>
                                @endif
                            </form>

                            <div class="social-login">


                                <div class="text-center mt-3">
                                    <p class="small">¿No tienes una cuenta? <a
                                            href="{{ route('register') }}">Regístrate</a></p>
                                </div>
                                @if (isset($errorMessage))
                                    <div class="alert alert-danger mt-3" role="alert">
                                        {{ $errorMessage }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/chart.js/chart.umd.js"></script>
    <script src="assets/vendor/echarts/echarts.min.js"></script>
    <script src="assets/vendor/quill/quill.js"></script>
    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>