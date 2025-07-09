<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Optional: Clear session cookie (optional but good practice)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login or homepage
header("Location: login.php"); // Change to index.php if needed
exit;
?>
