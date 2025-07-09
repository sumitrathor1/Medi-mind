<?php
  // Start session (if it hasn't been started already)
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }

  // -----------------------------------------------------------------------
  // 1.  GET USER DATA FROM SESSION (set during login)
  // -----------------------------------------------------------------------
  //     $_SESSION['user_id']       = $user['id'];
  //     $_SESSION['user_name']     = $user['name'];
  //     $_SESSION['user_email']    = $user['email'];
  //     $_SESSION['account_type']  = $user['account_type'];
  //     $_SESSION['user_avatar']   = $user['avatar'] (optional)
  //     $_SESSION['notif_count']   = total unread notifications (optional)
  // -----------------------------------------------------------------------

  $isLoggedIn   = isset($_SESSION['user_id']);
  $userName     = $isLoggedIn ? ($_SESSION['user_name']     ?? 'Henry')                 : null;
  $userEmail    = $isLoggedIn ? ($_SESSION['user_email']    ?? null)                    : null;
  $accountType  = $isLoggedIn ? ($_SESSION['account_type']  ?? null)                    : null;
  $userAvatar   = $isLoggedIn ? ($_SESSION['user_avatar']   ?? 'assets/img/profile.jpg'): null;
  $notifications= $isLoggedIn ? (int)($_SESSION['notif_count'] ?? 0)                    : 0;
?>
<style>
    a{
        text-decoration: none !important;
    }
</style>
<header id="header" class="header d-flex align-items-center fixed-top">
  <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

    <!-- =======  LOGO  ======= -->
    <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
      <!-- <img src="assets/img/logo.png" alt="Logo"> -->
      <!-- <h1 class="sitename">iLanding</h1> -->
    </a>

    <!-- =======  MAIN NAVIGATION  ======= -->
    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="./" class="active">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#features">Features</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#pricing">Pricing</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav><!-- End #navmenu -->

    <!-- =======  RIGHT-SIDE USER AREA  ======= -->
    <?php if ($isLoggedIn): ?>
      <div class="d-flex align-items-center gap-3">
        <!--  🔔 NOTIFICATIONS  -->
        <div class="nav-item dropdown">
          <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell fs-5"></i>
            <?php if ($notifications > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $notifications; ?>
              </span>
            <?php endif; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-0 overflow-hidden">
            <li class="py-2 px-3 fw-semibold small text-uppercase text-muted">Notifications</li>
            <li><hr class="dropdown-divider my-0"></li>
            <!-- TODO: loop through actual notifications -->
            <li><a class="dropdown-item text-center small py-3" href="notifications.php">View All</a></li>
          </ul>
        </div>

        <!--  👤 PROFILE DROPDOWN  -->
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex align-items-center" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Profile picture" class="rounded-circle" width="36" height="36" style="object-fit:cover;">
            <span class="d-none d-md-inline ps-2"><?php echo htmlspecialchars($userName); ?></span>
            <i class="bi bi-chevron-down ps-1 d-none d-md-inline"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-0 overflow-hidden">
            <li class="text-center py-3 bg-light">
              <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="Avatar" class="rounded-circle mb-2" width="60" height="60" style="object-fit:cover;">
              <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($userName); ?></h6>
              <?php if ($accountType): ?><span class="small text-muted d-block"><?php echo htmlspecialchars(ucfirst($accountType)); ?></span><?php endif; ?>
              <?php if ($userEmail): ?><span class="small text-muted d-block"><?php echo htmlspecialchars($userEmail); ?></span><?php endif; ?>
            </li>
            <li><hr class="dropdown-divider my-0"></li>
            <li><a class="dropdown-item py-2" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item py-2" href="settings.php"><i class="bi bi-gear me-2"></i>Settings <span class="badge bg-success ms-1">11</span></a></li>
  
            <li><hr class="dropdown-divider my-0"></li>
            <li><a class="dropdown-item py-2 text-danger" href="./logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div><!-- /.d-flex -->

    <?php else: ?>
      <!-- Guest view: Register / Login buttons -->
      <div class="d-flex">
  <a class="btn-getstarted me-0" href="register.php">Register</a>
  <a class="btn-getstarted" href="login.php">Login</a>
</div>

    <?php endif; ?>

  </div><!-- End .header-container -->
</header>

<!-- ===================================================================
     Bootstrap JS & dropdown initialisation (required for profile menu)
     =================================================================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ujmXBmVwvHidXMa2sUuP6929nDCwa9rjDZ9wla9ieP9XCH8cS5n0r9+3812J948" crossorigin="anonymous"></script>

<!-- Bootstrap CSS (in <head>) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap JS Bundle (before </body>) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
