<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// CSRF
if (!verifyCSRF($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid. Please refresh and try again.']);
    exit;
}

// Validate
$firstName    = sanitize($_POST['first_name'] ?? '');
$lastName     = sanitize($_POST['last_name'] ?? '');
$email        = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone        = sanitize($_POST['phone'] ?? '');
$organization = sanitize($_POST['organization'] ?? '');
$subject      = sanitize($_POST['subject'] ?? '');
$message      = sanitize($_POST['message'] ?? '');

if (!$firstName || !$lastName || !$email || !$subject || !$message) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$db = getDB();

// Disable strict exceptions temporarily to catch DB errors gracefully
mysqli_report(MYSQLI_REPORT_OFF);

$stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, ip_address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $db->error]);
    exit;
}
$fullName = $firstName . ' ' . $lastName;
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$stmt->bind_param('ssssss', $fullName, $email, $phone, $subject, $message, $ip);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Could not save message: ' . $stmt->error]);
    exit;
}

// Send email notification
$to = SITE_EMAIL;
$emailSubject = "New Contact Form: $subject – $firstName $lastName";
$emailBody = "Name: $firstName $lastName\nEmail: $email\nPhone: $phone\nOrganization: $organization\nSubject: $subject\n\nMessage:\n$message";
$headers = "From: noreply@drogapharma.com\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
@mail($to, $emailSubject, $emailBody, $headers);

echo json_encode(['success' => true, 'message' => 'Thank you! Your message has been sent. We will get back to you within 24 hours.']);
