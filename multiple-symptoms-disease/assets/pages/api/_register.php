<?php
// header('Content-Type: application/json');

// // Only allow POST
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     echo json_encode(['success' => false, 'message' => 'Invalid request.']);
//     exit;
// }

// $name        = $_POST['name'] ?? '';
// $email       = $_POST['email'] ?? '';
// $password    = $_POST['password'] ?? '';
// $accountType = $_POST['accountType'] ?? '';

// include '../_db.php';

// // Basic validation
// if (!$name || !$email || !$password || !$accountType) {
//     echo json_encode(['success' => false, 'message' => 'All fields are required.']);
//     exit;
// }

// // Check if user already exists
// $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
// $stmt->bind_param("s", $email);
// $stmt->execute();
// $result = $stmt->get_result();
// if ($result->num_rows > 0) {
//     echo json_encode(['success' => false, 'message' => 'User already exists.']);
//     exit;
// }

// $targetPath = null;
// if (in_array($accountType, ['student', 'doctor'])) {
//     if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== 0) {
//         echo json_encode(['success' => false, 'message' => 'Proof required for student/doctor.']);
//         exit;
//     }

//     $uploadDir = '../proofs/';
//     if (!is_dir($uploadDir)) {
//         mkdir($uploadDir, 0777, true);
//     }

//     $filename = basename($_FILES['proof']['name']);
//     $targetPath = $uploadDir . uniqid() . '_' . $filename;

//     if (!move_uploaded_file($_FILES['proof']['tmp_name'], $targetPath)) {
//         echo json_encode(['success' => false, 'message' => 'File upload failed.']);
//         exit;
//     }
// }

// // Start transaction
// $conn->begin_transaction();

// try {
//     $token = bin2hex(random_bytes(32));
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//     $stmt = $conn->prepare("INSERT INTO users (name, email, password, account_type, proof_path, verification_token) VALUES (?, ?, ?, ?, ?, ?)");
//     $stmt->bind_param("ssssss", $name, $email, $hashed_password, $accountType, $targetPath, $token);

//     if (!$stmt->execute()) {
//         throw new Exception('Database insert failed: ' . $stmt->error);
//     }

//     include '_send-mail.php';
//     if (!send_mail($email, $name, $token)) {
//         throw new Exception('Failed to send verification email.');
//     }

//     // Everything succeeded
//     $conn->commit();
//     echo json_encode(['success' => true, 'message' => 'Registered. Please verify your email.']);

// } catch (Exception $e) {
//     // Rollback DB insert
//     $conn->rollback();

//     // Delete uploaded file if exists
//     if ($targetPath && file_exists($targetPath)) {
//         unlink($targetPath);
//     }

//     echo json_encode(['success' => false, 'message' => $e->getMessage()]);
// }

// $stmt->close();
// $conn->close(); 

ini_set('display_errors', 0); // Don't show errors in browser
ini_set('log_errors', 1);     // Log errors instead
error_reporting(E_ALL); 

header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$name        = $_POST['name'] ?? '';
$email       = $_POST['email'] ?? '';
$password    = $_POST['password'] ?? '';
$accountType = $_POST['accountType'] ?? '';

include '../_db.php';

// Basic validation
if (!$name || !$email || !$password || !$accountType) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'User already exists.']);
    exit;
}

// File upload (optional, only for student/doctor)
$targetPath = null;
if (in_array($accountType, ['student', 'doctor'])) {
    if (!isset($_FILES['proof']) || $_FILES['proof']['error'] !== 0) {
        echo json_encode(['success' => false, 'message' => 'Proof required for student/doctor.']);
        exit;
    }

    $uploadDir = '../proofs/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES['proof']['name']);
    $targetPath = $uploadDir . uniqid() . '_' . $filename;

    if (!move_uploaded_file($_FILES['proof']['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'File upload failed.']);
        exit;
    }
}

// Extra fields for doctor and student
$specialization   = $_POST['specialization'] ?? null;
$experience       = $_POST['experience'] ?? null;
$license_number   = $_POST['license'] ?? null;

$institution      = $_POST['institution'] ?? null;
$study_year       = $_POST['year'] ?? null;

// Start transaction
$conn->begin_transaction();

try {
    $token = bin2hex(random_bytes(32));
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    
    // Insert user
    $stmt = $conn->prepare("
    INSERT INTO users (
        name, email, password, account_type, proof_path, verification_token,
        specialization, experience, license_number, institution, study_year
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssssss",
        $name,
        $email,
        $hashed_password,
        $accountType,
        $targetPath,
        $token,
        $specialization,
        $experience,
        $license_number,
        $institution,
        $study_year
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Database insert failed: ' . $stmt->error);
    }
    
    // Send mail
    include '_send-mail.php';
    if (!send_mail($email, $name, $token)) {
        throw new Exception('Failed to send verification email.');
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Registered. Please verify your email.']);

} catch (Exception $e) {
    $conn->rollback();

    if ($targetPath && file_exists($targetPath)) {
        unlink($targetPath);
    }

    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$stmt->close();
$conn->close();

?>