<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRIDA | Login</title>
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="css/login.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <style>
        .bg {
            margin: 0;
            height: 100vh;
            background-size: cover; /* Ensure the image covers the entire page */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent the image from repeating */
        }

        .register-right{
            background-color: rgba(255, 255, 255, 0.7); /* Merah dengan 50% transparansi */
            padding: 20px;
            color: white;
        }
    </style>
</head>

<body class="bg" style="background-image: url(img/login_background.jpeg);">
    <div class="container register">
        <div class="row">
            <div class="col-md-3 text-center text-white">
                <img src="img/logo_pemkot_bekasi.png" width="250">
                <h4>SIRIDA</h4>
                <h5>Sistem Informasi <br />Retribusi Daerah <br />
                    Kota Bekasi</h5>
            </div>
            <div class="col-md-9 register-right">
                <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Masuk Aplikasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tentang Aplikasi</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <h3 class="register-heading">Masuk Aplikasi </h3>
                        <form method='post' action="login_verification.php" id="login-form">
                            <div class="row register-form">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Masukan username " value="" name="username" required />
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Masukan password" value="" name="password" required />
                                    </div>

                                    <input type="submit" class="btnRegister" value="Login" />
                                </div>
                                <?php if (isset($_GET['f']) != null) { ?>
                                    <div class="alert alert-danger" role="alert" style="margin-top: 2%; width: 100%;">
                                        Username atau password anda tidak cocok!
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                <?php } ?>
                            </div>

                        </form>
                    </div>
                    <div class="tab-pane fade show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row" style="padding: 10%; margin-top: 10%;">
                            <div class="col-md-12 text-secondary" style="margin-top: -10%; margin-bottom: -10%;">
                                <p>
                                    SIRIDA Versi 1.0 ini sudah mendukung protokol payment gateway dengan menggunakan ISO 8583
                                    sehingga pihak Bank persepsi dapat dengan segera bekerjasama dengan pihak Bapenda untuk melakukan proses pembayaran Online
                                </p>
                                <h5>Jenis Retribusi</h5>
                                <p>
                                    Jenis Retribusi yang menjadi kewenangan wilayah Badan Pendaptan Kota Bekasi antara lain :
                                <ul>
                                    <li>Retribusi Kesehatan</li>
                                    <li>Retribusi Kebersihan</li>
                                    <li>Retribusi Ijin Mendirikan Bangunan,</li>
                                    <li>dsb.</li>
                                </ul>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    PENYALAHGUNAAN USER MENJADI TANGGUNGJAWAB PEMILIK USER. UNTUK KEAMANAN GANTI PASSWORD SECARA BERKALA.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->



    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>

    <script type="text/javascript">
        $(window).on('load', function() {
            $('#exampleModal').modal('show');
        });
    </script>


    <script type="text/javascript">
        var $form = $('#login-form'),
            $btnLogin = $('#login-btn'),
            $loadImg = "<img src='img/loading.gif'/>";

        $(function() {
            // Validation
            var stat = $form.validate({
                // Rules for form validation
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    }
                },

                // Messages for form validation
                messages: {
                    username: {
                        required: 'Please enter your username'
                    },
                    password: {
                        required: 'Please enter your password'
                    }
                },

                // Do not change code below
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }
            });

            $form.submit(function() {

                if (stat.checkForm()) {
                    $.ajax({
                        type: 'POST',
                        url: $form.attr('action'),
                        data: $form.serialize(),
                        beforeSend: function() {
                            $btnLogin.html($loadImg + "please wait...");
                        },
                        success: function(data) {

                            $btnLogin.html("Log in");

                            if (data == 'success') {
                                title_box = "Login Success";
                                content_box = "I know you and I\'m redirecting you to Dasboard Page ";
                                //	content_box = "I know you and I\'m redirecting you to Dasboard Page "+$loadImg;
                                color_box = "659265";
                                icon_box = "fa-check";
                            } else {
                                title_box = 'Login Failed';
                                content_box = "Sory, I don\'t know you. Please, try again !";
                                color_box = "C46A69";
                                icon_box = "fa-times";

                            }

                            $.smallBox({
                                title: title_box,
                                content: content_box,
                                color: "#" + color_box,
                                iconSmall: "fa " + icon_box + " fa-2x fadeInRight animated",
                                timeout: 5000
                            });

                            if (data == 'success')
                                window.location.assign('index.php');
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>