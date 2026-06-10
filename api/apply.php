<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

if (!verifyCSRF($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid.']);
    exit;
}

$firstName   = sanitize($_POST['first_name'] ?? '');
$lastName    = sanitize($_POST['last_name'] ?? '');
$email       = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone       = sanitize($_POST['phone'] ?? '');
$coverLetter = sanitize($_POST['cover_letter'] ?? '');
$jobId       = (int)($_POST['job_id'] ?? 0);
$jobTitle    = sanitize($_POST['job_title'] ?? '');

if (!$firstName || !$lastName || !$email || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// If job_id is 0 (demo job), set to NULL to bypass FK constraint
$jobIdValue = $jobId > 0 ? $jobId : null;

// Handle CV upload
$cvPath = '';
if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['pdf', 'doc', 'docx'];
    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only PDF, DOC, DOCX allowed.']);
        exit;
    }
    if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File size exceeds 5MB limit.']);
        exit;
    }
    $uploadDir = __DIR__ . '/../uploads/cvs/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $filename = uniqid('cv_') . '_' . time() . '.' . $ext;
    if (move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $filename)) {
        $cvPath = $filename;
    }
}

$db = getDB();
mysqli_report(MYSQLI_REPORT_OFF);

$stmt = $db->prepare("INSERT INTO job_applications (job_id, full_name, email, phone, cover_letter, cv_file) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB prepare error: ' . $db->error]);
    exit;
}
$fullName = $firstName . ' ' . $lastName;
$stmt->bind_param('isssss', $jobIdValue, $fullName, $email, $phone, $coverLetter, $cvPath);

if ($stmt->execute()) {
    // Notify HR
    $to = 'hr@drogapharma.com';
    $subject = "New Job Application: $jobTitle – $firstName $lastName";
    $body = "New application received:\n\nJob: $jobTitle\nName: $firstName $lastName\nEmail: $email\nPhone: $phone\n\nCover Letter:\n$coverLetter";
    $headers = "From: noreply@drogapharma.com\r\nReply-To: $email";
    @mail($to, $subject, $body, $headers);

    echo json_encode(['success' => true, 'message' => 'Your application has been submitted successfully! We will review it and contact you soon.']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . $stmt->error]);
}
