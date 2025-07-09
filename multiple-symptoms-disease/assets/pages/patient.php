<?php $error = ''; ?>
<!-- Symptom Form Section -->
<section class="hero section w-100 h-100">
    <div class="container">
        <div class="container py-5">
            <h2 class="text-center mb-4">Submit Your Symptoms</h2>

            <form id="chatForm" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label for="message" class="form-label">Describe your symptoms</label>
                    <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="submitBtn">Get AI Suggestion</button>
            </form>

            <div id="chat-response" class="mt-4"></div>
        </div>
    </div>
</section>

<!-- Chat UI Section -->
<div class="container mt-5" id="chat-ui-section" style="display: none;">
    <div class="card shadow-sm rounded p-4">
        <h4 class="mb-3">Chat With Medi-Mind</h4>
        <div id="chat-box"
            style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
            <!-- AI and User Message appears here -->
        </div>
        <div class="mt-3 d-flex">
            <input type="text" id="chat-input" class="form-control me-2" placeholder="Chat disabled after first AI reply..." disabled>
            <button class="btn btn-primary" onclick="sendMessage()" disabled>Send</button>
        </div>
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
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message=${encodeURIComponent(message)}`
    })
    .then(res => res.json())
    .then(data => {
        // Show AI response
        document.getElementById('chat-response').innerHTML = `
            <div class="alert alert-secondary"><strong>You:</strong> ${message}</div>
            <div class="alert alert-info"><strong>AI:</strong> ${data.reply}</div>
            <div class="alert alert-warning mt-3">
                <strong>Note:</strong> This is an AI-generated response and might not be accurate.
                Please wait 1 hour for a human healthcare provider to review your case.
            </div>
        `;

        // Load into chat box
        document.getElementById('chat-box').innerHTML = `
            <div><strong>You:</strong> ${message}</div>
            <div><strong>Medi-Mind:</strong> ${data.reply}</div>
        `;

        // Disable chat
        document.getElementById('chat-ui-section').style.display = 'block';
        document.getElementById('chat-input').disabled = true;

        // Reset form
        document.getElementById('chatForm').reset();
    })
    .catch(err => {
        document.getElementById('chat-response').innerHTML =
            `<div class="alert alert-danger">Error: ${err.message}</div>`;
        submitBtn.disabled = false;
    });
});
</script>