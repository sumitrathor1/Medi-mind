<html lang="en">

<head>
    <?php 
    include "../assets/pages/_page-head.php";
        header_path('../'); 
    ?>
    <title>Medimind - Dashboard</title>
</head>

<body class="index-page scrolled" data-aos-easing="ease-in-out" data-aos-duration="600" data-aos-delay="0">
    <?php include "assets/pages/_header.php"; ?>
    <?php  include "assets/pages/_main.php"; ?>
    <?php include "assets/pages/_footer.php"; ?>
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active">
        <i class="bi bi-arrow-up-short">
        </i>
    </a>
    <?php include "assets/pages/_page-script.php"; ?>

    <!-- Bootstrap CSS (in <head>) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap JS Bundle (before </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>