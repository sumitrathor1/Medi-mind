<?php
include 'assets/pages/_db.php';

// ✅ Simulated doctor login
$doctor_email = $_SESSION['user_email'] ?? 'doctor@example.com';

// ✅ Handle Doctor Report Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id']) && isset($_POST['doctor_report'])) {
    $pid = intval($_POST['patient_id']);
    $doctor_report = trim($_POST['doctor_report']);

    $stmt = $conn->prepare("UPDATE student_accepted_patients SET doctor_report = ? WHERE patient_id = ? AND assigned_doctor = ?");
    $stmt->bind_param("sis", $doctor_report, $pid, $doctor_email);
    $stmt->execute();
    exit;
}

// ✅ Fetch all patients assigned to this doctor
$query = "
    SELECT p.user_email, p.user_message, p.ai_response,
           sap.report_text AS student_report, sap.doctor_report, sap.patient_id
    FROM student_accepted_patients sap
    JOIN patient p ON p.id = sap.patient_id
    WHERE sap.assigned_doctor = ?
    ORDER BY sap.accepted_at DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $doctor_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5 mt-4">
    <h2 class="text-center mb-4">👨‍⚕️ Doctor Dashboard</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Patient Email</th>
                        <th>Symptoms</th>
                        <th>AI Response</th>
                        <th>Student Report</th>
                        <th>Your Report</th>
                        <th>Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['user_message'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['ai_response'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['student_report'])) ?></td>
                            <td>
                                <?php if ($row['doctor_report']): ?>
                                    <span class="text-success"><?= nl2br(htmlspecialchars($row['doctor_report'])) ?></span>
                                <?php else: ?>
                                    <form method="POST">
                                        <input type="hidden" name="patient_id" value="<?= $row['patient_id'] ?>">
                                        <textarea name="doctor_report" class="form-control form-control-sm" rows="2" required></textarea>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!$row['doctor_report']): ?>
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </form>
                                <?php else: ?>
                                    ✅ Submitted
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No reports assigned to you yet.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
