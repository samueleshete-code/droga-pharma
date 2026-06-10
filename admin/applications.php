<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Job Applications';

$action  = $_GET['action'] ?? '';
$id      = (int)($_GET['id'] ?? 0);
$jobFilter = (int)($_GET['job'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM job_applications WHERE id=$id");
    header('Location: applications.php?success=Application+deleted'); exit;
}
if ($action === 'status' && $id) {
    $status = sanitize($_GET['status'] ?? 'pending');
    $allowed = ['pending','reviewing','shortlisted','rejected','hired'];
    if (in_array($status, $allowed)) {
        $db->query("UPDATE job_applications SET status='$status' WHERE id=$id");
    }
    header('Location: applications.php?success=Status+updated'.($jobFilter?"&job=$jobFilter":'')); exit;
}

$sql = "SELECT a.*, j.title as job_title FROM job_applications a LEFT JOIN jobs j ON a.job_id=j.id WHERE 1";
if ($jobFilter) $sql .= " AND a.job_id=$jobFilter";
$sql .= " ORDER BY a.applied_at DESC";
$apps = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
$jobs = $db->query("SELECT id,title FROM jobs ORDER BY title")->fetch_all(MYSQLI_ASSOC);

$statusColors = ['pending'=>'status-new','reviewing'=>'status-reviewed','shortlisted'=>'bg-info text-white','rejected'=>'status-rejected','hired'=>'bg-success text-white'];

require_once 'includes/admin_header.php';
?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-user-tie me-2"></i>Applications (<?= count($apps) ?>)</h5>
    <form method="GET" class="d-flex gap-2">
      <select name="job" class="form-control-admin" style="width:220px" onchange="this.form.submit()">
        <option value="">All Jobs</option>
        <?php foreach($jobs as $j): ?>
        <option value="<?= $j['id'] ?>" <?= $jobFilter==$j['id']?'selected':'' ?>><?= htmlspecialchars($j['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Applicant</th><th>Job</th><th>Email</th><th>Phone</th><th>CV</th><th>Applied</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($apps)): ?>
        <tr><td colspan="8" class="text-center text-muted py-4">No applications yet.</td></tr>
        <?php else: foreach($apps as $a): ?>
        <tr>
          <td><strong><?= htmlspecialchars($a['full_name']) ?></strong></td>
          <td><?= htmlspecialchars($a['job_title']??'—') ?></td>
          <td><a href="mailto:<?= htmlspecialchars($a['email']) ?>"><?= htmlspecialchars($a['email']) ?></a></td>
          <td><?= htmlspecialchars($a['phone']??'—') ?></td>
          <td>
            <?php if($a['cv_file']): ?>
            <a href="<?= SITE_URL ?>/uploads/cvs/<?= htmlspecialchars($a['cv_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> CV</a>
            <?php else: ?><span class="text-muted">—</span><?php endif; ?>
          </td>
          <td><?= date('M d, Y', strtotime($a['applied_at'])) ?></td>
          <td>
            <div class="dropdown">
              <button class="status-badge <?= $statusColors[$a['status']]??'status-new' ?> dropdown-toggle border-0" data-bs-toggle="dropdown" style="cursor:pointer">
                <?= ucfirst($a['status']) ?>
              </button>
              <ul class="dropdown-menu">
                <?php foreach(['pending','reviewing','shortlisted','hired','rejected'] as $s): ?>
                <li><a class="dropdown-item" href="applications.php?action=status&id=<?= $a['id'] ?>&status=<?= $s ?><?= $jobFilter?"&job=$jobFilter":'' ?>"><?= ucfirst($s) ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </td>
          <td>
            <button class="btn btn-sm btn-outline-secondary me-1" onclick="viewCoverLetter('<?= htmlspecialchars(addslashes($a['full_name'])) ?>','<?= htmlspecialchars(addslashes($a['cover_letter']??'No cover letter provided.')) ?>')"><i class="fas fa-eye"></i></button>
            <button onclick="confirmDelete('applications.php?action=delete&id=<?= $a['id'] ?>','Delete this application?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Cover Letter Modal -->
<div class="modal fade" id="coverModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title" id="coverName">Cover Letter</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><p id="coverText" style="white-space:pre-wrap"></p></div>
  </div></div>
</div>

<?php
$extraScripts = '<script>
function viewCoverLetter(name,text){
  document.getElementById("coverName").textContent=name+" – Cover Letter";
  document.getElementById("coverText").textContent=text;
  new bootstrap.Modal(document.getElementById("coverModal")).show();
}
</script>';
require_once 'includes/admin_footer.php';
?>
