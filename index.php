<?php
// Di index.php
session_start();
if (isset($_SESSION['result'])) {
    $result = $_SESSION['result'];

    // Hapus data sesi
    unset($_SESSION['result']);
} else {
    $result = false;
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Mowi | Monitoring Kesehatan Sawimu</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Logo Browser -->
    <link rel="shortcut icon" href="public/assets/logo.png" type="image/x-icon">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- Font from Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&family=Tinos:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- Local -->
    <link rel="stylesheet" href="public/style/main.css">
</head>

<body>
    <nav id="navbarTabs" class="fixed-top navbar navbar-expand-md bg-blur bg-body-tertiary navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="public/assets/logo.png" alt="Mowi"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary" href="#main">Mulai Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <div class="container-fluid h-100">
            <div class="h-100 row justify-content-start align-items-center">
                <div class="col-lg-4 col-md-7">
                    <h1>
                        Temukan <b>Solusi <span id="run"></span></b> Untuk Menjaga Kesehatan Tanamanmu
                    </h1>
                </div>
            </div>
        </div>
    </header>


    <main data-bs-spy="scroll" data-bs-target="#navbarTabs" data-bs-root-margin="0px 0px -40%"
        data-bs-smooth-scroll="true" class="scrollspy-example bg-body-tertiary" tabindex="0">

        <section id="main" class="main text-white p-5">
            <div class="container">
                <div class="row justify-content-center align-items-stretch">
                    <h2 class="text-center">Coba Cek Kesehatan Tanaman Sawimu</h2>
                    <div class="col-lg-7 col-md-8 p-1">
                        <form action="form_request.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <h3 class="form-label">Masukkan Gambar</h3>
                                <?php if (isset($_GET)){
                                if ($_GET['input'] == 'fail') { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Bukan Gambar!</strong> Pastikan file yang dikirim adalah gambar
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                <?php }} ?>
                                <label for="inputFile" class="form-label">Pilih gambar tanaman sawimu untuk dicek
                                    kondisinya</label>
                                <input type="file" class="form-control" id="inputFile" name="image" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-secondary w-100">Kirim</button>
                            </div>
                        </form>
                    </div>

                    <?php if ($result) { ?>
                    <div class="col-lg-5 col-md-4 p-1">
                        <div class="card rounded-4 w-100 h-100">
                            <div class="card-header">
                                <h4 class="text-center">Kondisi Tanaman Sawi:</h4>
                            </div>
                            <div class="card-body">
                                <h1 class="text-center">
                                    <?php if ($result['class']=='well') { ?>
                                    <b class="text-info">Sehat</b>
                                    <?php } if ($result['class']=='unwell') { ?>
                                    <b class="text-danger">Tidak Sehat</b>
                                    <?php } if ($result['class']=='others') { ?>
                                    <b class="text-warning">Bukan Tanaman Sawi</b>
                                    <?php } ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="about" class="about p-5">
            <div class="container">
                <div class="row justify-content-between align-items-center flex-row-reverse mb-3">
                    <div class="col-lg-7 col-md-6 p-md-4 p-1">
                        <img src="public/assets/apa-itu-mowi.jpg" alt="Apa itu Mowi?" class="rounded-5 img-thumb">
                    </div>
                    <div class="col-lg-5 col-md-6 p-1">
                        <h2>Apa itu <b>Mowi</b>?</h2>
                        <p><b>Mowi</b> adalah sebuah inovasi pada budidaya sawi dengan bantuan sistem AI yang
                            dikembangkan untuk menghasilkan sawi yang berkualitas. Mulai dari hanya mengecek kondisi
                            sawi berdasarkan gambar sampai proses monitoring yang meberikan laporan kesehatan sawi yang
                            dibudidayakan.
                            <br>
                            Dengan
                            teknologi pemindaian menggunakan AI yang dikembangkan, diharapkan alat ini mampu memantau
                            berbagai karakteristik sawi untuk mendeteksi kondisi stres, kekurangan
                            nutrisi, penyakit, atau gangguan lainnya pada tanaman sawi. Serta memberikan data digital,
                            rekomendasi perawatan,
                            dan memungkinkan penghematan sumber daya dalam pertanian.
                        </p>
                    </div>
                </div>

                <div class="row justify-content-around align-items-stretch mb-3">
                    <h2>Nantikan Perkembangan Teknologi Kami</h2>
                    <div class="col-lg-3 col-md-5 p-1 my-2">
                        <div class="card rounded-4 w-100 h-100">
                            <img src="public/assets/sawiscan.jpg" class="card-img-top rounded-4" alt="SawiScan">
                            <div class="card-body">
                                <h4 class="card-title text-center"><b>SawiScan</b></h5>
                                    <p class="card-text">Cek kondisi kesehatan sawimu secara langsung menggunakan
                                        <b>SawiScan</b>
                                        sebelum dilakukan distribusi
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-5 p-1 my-2">
                        <div class="card rounded-4 w-100 h-100">
                            <img src="public/assets/sawidiag.jpg" class="card-img-top rounded-4" alt="SawiDiag">
                            <div class="card-body">
                                <h4 class="card-title text-center"><b>SawiDiag</b></h5>
                                    <p class="card-text">Lakukan analisis terhadap kesehatan sawimu dengan
                                        <b>SawiDiag</b> untuk mendapatkan laporan analisis kesehatan sawimu
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-5 p-1 my-2">
                        <div class="card rounded-4 w-100 h-100">
                            <img src="public/assets/sawimon.jpg" class="card-img-top rounded-4" alt="SawiMon">
                            <div class="card-body">
                                <h4 class="card-title text-center"><b>SawiMon</b></h5>
                                    <p class="card-text">Lakukan pemantauan secara otomatis untuk tanamanan sawimu
                                        melalui <b>SawiMon</b> dan tunggu laporan analisis kesehatan sawimu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="d-flex p-2">
        <p class="m-auto text-white">@2023 Monitoring Kesehatan Tanaman Sawimu dengan Mudah | <b>Mowi</b></p>
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>

    <script src="public/script/run_text.js"></script>

    <script src="public/script/nav_tabs.js"></script>
</body>

</html>