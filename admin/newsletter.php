<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Newsletter Subscribers';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM newsletter_subscribers WHERE id=$id");
    header('Location: newsletter.php?success=Subscriber+removed'); exit;
}
if ($action === 'toggle' && $id) {
    $db->query("UPDATE newsletter_subscribers SET is_active=1-is_active WHERE id=$id");
    header('Location: newsletter.php?success=Status+updated'); exit;
}

$subs = $db->query("SELECT * FROM newsletter_subscribers ORDER BY subscribed_at DESC")->fetch_all(MYSQLI_ASSOC);
$total  = count($subs);
$active = count(array_filter($subs, fn($s)=>$s['is_active']));

require_once 'includes/admin_header.php';
?>

<div class="row g-4 mb-4">
  <div class="col-md-3"><div class="stat-card-admin"><div class="sca-icon" style="background:#dbeafe;color:#2563eb"><i class="fas fa-users"></i></div><div class="sca-value"><?= $total ?></div><div class="sca-label">Total Subscribers</div></div></div>
  <div class="col-md-3"><div class="stat-card-admin"><div class="sca-icon" style="background:#dcfce7;color:#16a34a"><i class="fas fa-check-circle"></i></div><div class="sca-value"><?= $active ?></div><div class="sca-label">Active</div></div></div>
  <div class="col-md-3"><div class="stat-card-admin"><div class="sca-icon" style="background:#fee2e2;color:#dc2626"><i class="fas fa-times-circle"></i></div><div class="sca-value"><?= $total-$active ?></div><div class="sca-label">Unsubscribed</div></div></div>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-mail-bulk me-2"></i>Subscribers</h5>
    <a href="newsletter.php?export=1" class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i>Export CSV</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Email</th><th>Name</th><th>Subscribed</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($subs)): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">No subscribers yet.</td></tr>
        <?php else: foreach($subs as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['email']) ?></td>
          <td><?= htmlspecialchars($s['name']??'—') ?></td>
          <td><?= date('M d, Y', strtotime($s['subscribed_at'])) ?></td>
          <td><a href="newsletter.php?action=toggle&id=<?= $s['id'] ?>" class="status-badge <?= $s['is_active']?'status-new':'status-rejected' ?>" style="cursor:pointer;text-decoration:none"><?= $s['is_active']?'Active':'Inactive' ?></a></td>
          <td><button onclick="confirmDelete('newsletter.php?action=delete&id=<?= $s['id'] ?>','Remove subscriber?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// CSV Export
if (isset($_GET['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers_'.date('Y-m-d').'.csv"');
    echo "Email,Name,Subscribed At,Status\n";
    foreach($subs as $s) {
        echo '"'.str_replace('"','""',$s['email']).'","'.str_replace('"','""',$s['name']??'').'","'.$s['subscribed_at'].'","'.($s['is_active']?'Active':'Inactive').'"'."\n";
    }
    exit;
}
require_once 'includes/admin_footer.php';
?>
