<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Contact Messages';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM contact_messages WHERE id=$id");
    header('Location: messages.php?success=Message+deleted'); exit;
}
if ($action === 'read' && $id) {
    $db->query("UPDATE contact_messages SET is_read=1 WHERE id=$id");
}
if ($action === 'readall') {
    $db->query("UPDATE contact_messages SET is_read=1");
    header('Location: messages.php?success=All+marked+as+read'); exit;
}

// View single
$view = null;
if ($action === 'view' && $id) {
    $db->query("UPDATE contact_messages SET is_read=1 WHERE id=$id");
    $r = $db->query("SELECT * FROM contact_messages WHERE id=$id");
    $view = $r ? $r->fetch_assoc() : null;
}

$messages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
$unread = array_filter($messages, fn($m) => !$m['is_read']);

require_once 'includes/admin_header.php';
?>

<?php if ($view): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5>Message from <?= htmlspecialchars($view['name']) ?></h5>
    <div class="d-flex gap-2">
      <a href="mailto:<?= htmlspecialchars($view['email']) ?>?subject=Re: <?= urlencode($view['subject']??'') ?>" class="btn btn-sm btn-primary"><i class="fas fa-reply me-1"></i>Reply</a>
      <a href="messages.php" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
  </div>
  <div class="admin-card-body p-4">
    <div class="row g-3 mb-4">
      <div class="col-md-3"><strong>From:</strong><br><?= htmlspecialchars($view['name']) ?></div>
      <div class="col-md-3"><strong>Email:</strong><br><a href="mailto:<?= htmlspecialchars($view['email']) ?>"><?= htmlspecialchars($view['email']) ?></a></div>
      <div class="col-md-3"><strong>Phone:</strong><br><?= htmlspecialchars($view['phone']??'—') ?></div>
      <div class="col-md-3"><strong>Date:</strong><br><?= date('M d, Y H:i', strtotime($view['created_at'])) ?></div>
      <div class="col-md-6"><strong>Subject:</strong><br><?= htmlspecialchars($view['subject']??'—') ?></div>
    </div>
    <div class="p-3 bg-light rounded">
      <strong>Message:</strong>
      <p class="mt-2 mb-0" style="white-space:pre-wrap"><?= htmlspecialchars($view['message']) ?></p>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-envelope me-2"></i>Messages (<?= count($unread) ?> unread)</h5>
    <a href="messages.php?action=readall" class="btn btn-sm btn-outline-secondary">Mark All Read</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th></th><th>From</th><th>Subject</th><th>Email</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($messages)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No messages yet.</td></tr>
        <?php else: foreach($messages as $m): ?>
        <tr style="<?= !$m['is_read']?'font-weight:600;background:#f0f7ff':'' ?>">
          <td><?php if(!$m['is_read']): ?><span class="unread-dot"></span><?php endif; ?></td>
          <td><?= htmlspecialchars($m['name']) ?></td>
          <td><?= htmlspecialchars($m['subject']??'—') ?></td>
          <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
          <td><?= date('M d, Y', strtotime($m['created_at'])) ?></td>
          <td>
            <a href="messages.php?action=view&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-eye"></i></a>
            <a href="mailto:<?= htmlspecialchars($m['email']) ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-reply"></i></a>
            <button onclick="confirmDelete('messages.php?action=delete&id=<?= $m['id'] ?>','Delete this message?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
