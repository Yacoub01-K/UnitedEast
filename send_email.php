<?php
header('Content-Type: application/json');

// Updated CORS headers
header("Access-Control-Allow-Origin: *"); // Allow all origins for testing
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $organization = $data['organization'] ?? '';
    $phone = $data['phone'] ?? '';
    $subject = $data['subject'] ?? '';
    $message = $data['message'] ?? '';
    
    $to = "samer@unitedeast.com";
    $email_subject = "New Contact Form Submission: $subject";
    
    $email_body = "You have received a new message.\n\n".
        "Name: $name\n".
        "Email: $email\n".
        "Organization: $organization\n".
        "Phone: $phone\n".
        "Subject: $subject\n\n".
        "Message:\n$message";
    
    $headers = "From: $email\n";
    $headers .= "Reply-To: $email";
    
    if(mail($to, $email_subject, $email_body, $headers)) {
        echo json_encode(["status" => "success", "message" => "Email sent successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to send email"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}
?>