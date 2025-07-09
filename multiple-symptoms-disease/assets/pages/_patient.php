<?php
include 'assets/pages/_db.php';

$user_email = $_SESSION['user_email'] ?? 'testuser@example.com'; // Replace with real login system
$error = '';
?>

<!-- HTML PART -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Symptoms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5 mt-5">
        <h2 class="text-center mb-4">📝 Submit Your Symptoms</h2>

        <!-- FORM + CHAT RESPONSE -->
        <form id="chatForm" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="message" class="form-label">Describe your symptoms</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" id="submitBtn">Submit Symptoms</button>
        </form>

        <div id="chat-response" class="mt-4" style="display:none;">
            <div class="card p-3">
                <div><strong>You:</strong> <span id="userText"></span></div>
                <div class="mt-2"><strong>AI:</strong> <span id="aiReply" class="text-info"></span></div>
                <div class="alert alert-warning mt-3">
                    <strong>Note:</strong> This is an AI-generated suggestion. A human doctor will review it shortly.
                </div>
            </div>
        </div>

        <!-- Consultation History Table -->
        <div class="mt-5">
            <h4 class="mb-3 text-center">📋 Your Consultation History</h4>

            <?php
       $report_query = "
    SELECT 
        p.user_message, 
        p.ai_response, 
        sap.report_text AS student_report, 
        sap.doctor_report, 
        u.name AS doctor_name
    FROM patient p
    LEFT JOIN student_accepted_patients sap ON sap.patient_id = p.id
    LEFT JOIN users u ON u.email = sap.assigned_doctor AND u.account_type = 'doctor'
    WHERE p.user_email = ?
    ORDER BY p.created_at DESC
";
$stmt = $conn->prepare($report_query);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

            <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Doctor Name</th>
                            <th>Your Symptoms</th>
                            <th>AI Response</th>
                            <th>Student Report</th>
                            <th>Doctor Final Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['doctor_name'] ?? 'Pending') ?></td>
                            <td><?= nl2br(htmlspecialchars($row['user_message'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['ai_response'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['student_report'] ?? 'Pending')) ?></td>
                            <td>
                                <?= $row['doctor_report'] 
                                        ? '<span class="text-success">' . nl2br(htmlspecialchars($row['doctor_report'])) . '</span>' 
                                        : '<span class="text-muted">Awaiting Doctor Response</span>' ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info">You have no consultations yet.</div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const message = document.getElementById('message').value;
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;

        fetch('assets/pages/api/_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `message=${encodeURIComponent(message)}`
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('chat-response').style.display = 'block';
                document.getElementById('userText').innerText = message;
                document.getElementById('aiReply').innerText = data.reply;
                document.getElementById('chatForm').reset();
            })
            .catch(err => {
                alert('Error: ' + err.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
            });
    });
    </script>

</body>

</html>