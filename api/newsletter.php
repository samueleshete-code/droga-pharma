<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$db = getDB();
// Check duplicate
$stmt = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'This email is already subscribed.']);
    exit;
}

$stmt = $db->prepare("INSERT INTO newsletter_subscribers (email, subscribed_at) VALUES (?, NOW())");
$stmt->bind_param('s', $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing to our newsletter!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Subscription failed. Please try again.']);
}
