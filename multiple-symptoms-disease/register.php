<?php 
ob_start(); ?>
<html lang="en">

<head>
    <?php 
    include "../assets/pages/_page-head.php";
    header_path('../'); 
    ?>

    <title>Medimind - Register</title>
</head>

<body class="index-page scrolled" data-aos-easing="ease-in-out" data-aos-duration="600" data-aos-delay="0">
    <main class="main">
        <!-- register Section -->
        <section id="register" class="contact section light-background mt-5">

            <!-- Section Title -->
            <div class="container section-title aos-init" data-aos="fade-up">
                <h2>Register</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container aos-init" data-aos="fade-up" data-aos-delay="100">
                <div class="row g-4 g-lg-5 d-flex justify-content-center">
                    <div class="col-lg-7">
                        <div class="contact-form aos-init" data-aos="fade-up" data-aos-delay="300">
                            <h3>Register</h3>
                            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante
                                ipsum primis.</p>

                            <form id="registerForm" method="post" class="php-email-form aos-init" data-aos="fade-up"
                                data-aos-delay="200" enctype="multipart/form-data">
                                <div class="row gy-4">

                                    <div class="col-md-12">
                                        <label for="name" class="form-label">Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Your Name" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="email" class="form-label">Email<span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Your Email" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="password" class="form-label">Password<span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="Your Password" required>
                                        <small id="passwordWarning" class="text-danger" style="display: none;">
                                            Password must be at least 8 characters, include uppercase, lowercase,
                                            number,
                                            and special character.
                                        </small>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-control d-flex justify-content-around">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="accountType"
                                                    id="radioPatient" value="patient" checked>
                                                <label class="form-check-label" for="radioPatient">Patient</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="accountType"
                                                    id="radioStudent" value="student">
                                                <label class="form-check-label" for="radioStudent">Student</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="accountType"
                                                    id="radioDoctor" value="doctor">
                                                <label class="form-check-label" for="radioDoctor">Doctor</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Proof Upload Field -->
                                    <div class="col-md-12" id="proofUpload" style="display: none;">
                                        <label for="proof" class="form-label">Upload Proof (Student/Doctor ID)</label>
                                        <input type="file" name="proof" id="proof" class="form-control"
                                            accept="image/*">
                                    </div>
                                    <!-- Doctor-specific fields -->
                                    <div class="col-md-12 doctor-field" style="display: none;">
                                        <label for="specialization" class="form-label">Specialization</label>
                                        <input type="text" name="specialization" id="specialization"
                                            class="form-control" placeholder="e.g. Cardiologist">
                                    </div>

                                    <div class="col-md-12 doctor-field" style="display: none;">
                                        <label for="experience" class="form-label">Years of Experience</label>
                                        <input type="number" name="experience" id="experience" class="form-control"
                                            placeholder="e.g. 5">
                                    </div>

                                    <div class="col-md-12 doctor-field" style="display: none;">
                                        <label for="license" class="form-label">Medical Registration Number</label>
                                        <input type="text" name="license" id="license" class="form-control"
                                            placeholder="e.g. MP1234567">
                                    </div>

                                    <!-- Student-specific fields -->
                                    <div class="col-md-12 student-field" style="display: none;">
                                        <label for="institution" class="form-label">Institution Name</label>
                                        <input type="text" name="institution" id="institution" class="form-control"
                                            placeholder="Your Medical College">
                                    </div>

                                    <div class="col-md-12 student-field" style="display: none;">
                                        <label for="year" class="form-label">Year of Study</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">--Select Year--</option>
                                            <option value="3rd">3rd Year</option>
                                            <option value="4th">4th Year</option>
                                            <option value="intern">Intern</option>
                                        </select>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button id="registerBtn" type="submit" class="btn">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /register Section -->
    </main>
    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active">
        <i class="bi bi-arrow-up-short">
        </i>
    </a>
    <?php include "assets/pages/_page-script.php"; ?>
    <script src="assets/js/_register-main.js"></script>
</body>

</html>

<?php
include '../assets/pages/_db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Check token in DB
    $stmt = $conn->prepare("SELECT id, is_verified FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid or expired token.";
        exit;
    }

    $user = $result->fetch_assoc();

    if ($user['is_verified']) {
        echo "Account already verified. Redirecting to login...";
        header("refresh:3;url=login.php");
        exit;
    }

    // Update is_verified
    $update = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
    $update->bind_param("i", $user['id']);
    $update->execute();

    echo "✅ Email verified successfully! Redirecting to login...";
    header("refresh:3;url=login.php");
}
?>