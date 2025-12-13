<?php
// ============================================
// EMAIL CONFIGURATION - ADD YOUR DETAILS HERE
// ============================================
$to_email = "your-email@example.com";  // Replace with your email address
$from_email = "noreply@northaesthetic.com";  // Replace with your domain email
$subject_prefix = "New Feedback - North Aesthetic";

// ============================================

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $category = strip_tags(trim($_POST["category"]));
    $rating = strip_tags(trim($_POST["rating"]));
    $message = strip_tags(trim($_POST["message"]));

    // Validate data
    if (empty($name) || empty($email) || empty($category) || empty($rating) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }

    // Email subject
    $subject = $subject_prefix . " - " . $category;

    // Email body
    $email_body = "You have received new feedback from your website.\n\n";
    $email_body .= "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Category: $category\n";
    $email_body .= "Rating: $rating stars\n\n";
    $email_body .= "Message:\n$message\n";

    // Email headers
    $headers = "From: $from_email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Send email
    if (mail($to_email, $subject, $email_body, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Feedback sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send email. Please try again.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}