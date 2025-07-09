<?php
session_start();
include 'assets/pages/_db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = "Please enter both email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, account_type, is_verified FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error = "User not found.";
        } else {
            $user = $result->fetch_assoc();

            if (!password_verify($password, $user['password'])) {
                $error = "Incorrect password.";
            } elseif (!$user['is_verified']) {
                $error = "Please verify your email before logging in.";
            } else {
                // Success — create session
                $_SESSION['user_id']       = $user['id'];
                $_SESSION['user_name']     = $user['name'];
                $_SESSION['user_email']    = $user['email'];
                $_SESSION['account_type']  = $user['account_type'];

                header("Location: dashboard.php"); // 🔁 Redirect after login
                
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
    include "../assets/pages/_page-head.php";
    header_path('../'); 
    ?>
    <title>Medimind - Login</title>
</head>

<body class="index-page scrolled">
    <main class="main">
        <section id="login" class="contact section light-background mt-5">
            <div class="container section-title" data-aos="fade-up">
                <h2>Login</h2>
                <p>Access your Medimind account securely</p>
            </div>

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <h3>Login</h3>
                            <p>Enter your credentials to continue</p>

                            <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <form method="post" class="php-email-form">
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <label for="email" class="form-label">Email<span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="password" class="form-label">Password<span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn">Login</button>
                                    </div>

                                    <div class="col-12 text-center">
                                        <small>Don't have an account? <a href="register.php">Register here</a></small>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include "assets/pages/_page-script.php"; ?>
</body>

</html>