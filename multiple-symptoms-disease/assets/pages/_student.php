<?php
include 'assets/pages/_db.php';

$doctor_result = $conn->query("SELECT name, email FROM users WHERE account_type = 'doctor'");
$doctors = [];
while ($doc = $doctor_result->fetch_assoc()) {
    $doctors[] = $doc;
}

// Simulated logged-in student
$student_email = $_SESSION['user_email'] ?? 'student@example.com';

// Handle Accept Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Count current accepted patients with no report
    $countRes = $conn->prepare("SELECT COUNT(*) FROM student_accepted_patients WHERE student_email = ? AND report_sent = 0");
    $countRes->bind_param("s", $student_email);
    $countRes->execute();
    $countRes->bind_result($active_count);
    $countRes->fetch();
    $countRes->close();

    // Accept patient if limit not reached
    if (isset($_POST['accept_id']) && $active_count < 2) {
        $id = intval($_POST['accept_id']);

        // Update patient as unavailable
        $stmt1 = $conn->prepare("UPDATE patient SET is_available = 0 WHERE id = ?");
        $stmt1->bind_param("i", $id);
        $stmt1->execute();

        // Insert into student_accepted_patients
        $stmt2 = $conn->prepare("INSERT INTO student_accepted_patients (student_email, patient_id) VALUES (?, ?)");
        $stmt2->bind_param("si", $student_email, $id);
        $stmt2->execute();
        exit;
    }

    // Handle report submission
    if (isset($_POST['send_report_id']) && isset($_POST['report_text']) && isset($_POST['doctor_email'])) {
        $pid = intval($_POST['send_report_id']);
        $report = trim($_POST['report_text']);
        $doctor_email = $_POST['doctor_email'];

        $stmt = $conn->prepare("UPDATE student_accepted_patients SET report_text = ?, assigned_doctor = ?, report_sent = 1 WHERE patient_id = ? AND student_email = ?");
        $stmt->bind_param("ssis", $report, $doctor_email, $pid, $student_email);
        $stmt->execute();
        exit;
    }

}

// ✅ Count active
$countRes = $conn->prepare("SELECT COUNT(*) FROM student_accepted_patients WHERE student_email = ? AND report_sent = 0");
$countRes->bind_param("s", $student_email);
$countRes->execute();
$countRes->bind_result($accepted_count);
$countRes->fetch();
$countRes->close();

// ✅ Fetch available patients
$available_patients = $conn->query("SELECT * FROM patient WHERE is_available = 1 ORDER BY created_at DESC");

// ✅ Fetch current accepted patients
$accepted_query = "
    SELECT p.*, sap.report_sent 
    FROM student_accepted_patients sap
    JOIN patient p ON p.id = sap.patient_id
    WHERE sap.student_email = ?
    ORDER BY sap.accepted_at DESC
";
$stmt = $conn->prepare($accepted_query);
$stmt->bind_param("s", $student_email);
$stmt->execute();
$accepted_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5 mt-5">
        <h2 class="mb-4 text-center">👨‍🎓 Student Dashboard</h2>

        <!-- Accepted Patients (With Report Buttons) -->
        <div class="card mb-5 p-4 shadow-sm">
            <h4>Accepted Patients (Max 2)</h4>
            <?php if ($accepted_result->num_rows > 0): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Patient ID</th>
                        <th>Email</th>
                        <th>Symptoms</th>
                        <th>AI Response</th>
                        <th>Status</th>
                        <th>Send Report</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $accepted_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['user_email']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['user_message'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['ai_response'])) ?></td>
                        <td>
                            <?= $row['report_sent'] ? '<span class="badge bg-success">Report Sent</span>' : '<span class="badge bg-warning">Pending</span>' ?>
                        </td>
                        <td>
                            <?php if (!$row['report_sent']): ?>
                            <form method="post">
                                <input type="hidden" name="send_report_id" value="<?= $row['id'] ?>">

                                <div class="mb-2">
                                    <textarea name="report_text" class="form-control form-control-sm" rows="2" required
                                        placeholder="Write report here..."></textarea>
                                </div>

                                <div class="mb-2">
                                    <select name="doctor_email" class="form-select form-select-sm" required>
                                        <option value="">Select Doctor</option>
                                        <?php foreach ($doctors as $doc): ?>
                                        <option value="<?= htmlspecialchars($doc['email']) ?>">
                                            <?= htmlspecialchars($doc['name']) ?>
                                            (<?= htmlspecialchars($doc['email']) ?>)
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary">Send Report</button>
                            </form>
                            <?php else: ?>
                            ✅ Sent
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="text-muted">No accepted patients yet.</p>
            <?php endif; ?>
        </div>

        <!-- Available Patients -->
        <div class="card p-4 shadow-sm">
            <h4>Available Patients to Accept</h4>
            <?php if ($available_patients->num_rows > 0): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Symptoms</th>
                        <th>AI Response</th>
                        <th>Accept</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $available_patients->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['user_email']) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['user_message'])) ?></td>
                        <td><?= nl2br(htmlspecialchars($row['ai_response'])) ?></td>
                        <td>
                            <?php if ($accepted_count < 2): ?>
                            <form method="post">
                                <input type="hidden" name="accept_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-success btn-sm">Accept</button>
                            </form>
                            <?php else: ?>
                            <span class="text-danger">Limit reached (2)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="alert alert-info mt-3">No available patients right now.</div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>