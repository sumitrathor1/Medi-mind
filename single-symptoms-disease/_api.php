<?php
session_start();
header('Content-Type: application/json');

// ✅ API Key and Model URL
$apiKey = "AIzaSyCDn3c2o0biaYqyG--8JDrdaBmrH5kyXfU";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$apiKey";

// ✅ Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$user_msg = trim($data['message'] ?? '');
$disease_name = trim($data['disease'] ?? '');

if ($user_msg === '') {
    echo json_encode(['reply' => 'Please enter a message.']);
    exit;
}

// ✅ Store disease once
if (!isset($_SESSION['disease']) && $disease_name !== '') {
    $_SESSION['disease'] = $disease_name;
}

// ✅ Setup prompt only once
if (!isset($_SESSION['chat_history'])) {
    $storedDisease = $_SESSION['disease'] ?? 'a health problem';
    $_SESSION['chat_history'] = [
        [
            "role" => "user",
            "parts" => [[
                "text" => "Act like a friendly doctor.
Reply in the same language the user uses. 
If user writes in English, reply in English. 
If user uses Hindi, reply in Hindi.
Keep answers short, friendly, and less than 20 words.
No markdown, no long explanations, no introductions like 'I understand' or 'As an AI'. 
Topic: $storedDisease"
            ]]
        ],
        [
            "role" => "model",
            "parts" => [[
                "text" => "Understood. I will respond in user's language, in short and simple sentences without any intro."
            ]]
        ],
    ];
}

// ✅ Add user message
$_SESSION['chat_history'][] = ["role" => "user", "parts" => [["text" => $user_msg]]];

// ✅ Keep last 20 messages only
$history = array_slice($_SESSION['chat_history'], -20);

// ✅ Prepare API payload
$payload = json_encode(["contents" => $history]);

// ✅ Make API call
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
]);
$response = curl_exec($ch);
curl_close($ch);

// ✅ Decode response
$responseData = json_decode($response, true);

// ✅ Extract model's reply
$ai_msg = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, no response.';

// ✅ Basic cleanup
$ai_msg = str_replace('*', '', $ai_msg);
$ai_msg = preg_replace('/^(I understand.*?|As an AI.*?)[\.\n]+/i', '', $ai_msg);
$ai_msg = preg_replace('/\n{2,}/', "\n", $ai_msg);
$ai_msg = trim($ai_msg);

// ✅ Save model reply to session
$_SESSION['chat_history'][] = ["role" => "model", "parts" => [["text" => $ai_msg]]];

// ✅ Send response
echo json_encode(['reply' => $ai_msg]);