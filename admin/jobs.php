<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Jobs';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM jobs WHERE id=$id");
    header('Location: jobs.php?success=Job+deleted'); exit;
}
if ($action === 'toggle' && $id) {
    $db->query("UPDATE jobs SET is_active=1-is_active WHERE id=$id");
    header('Location: jobs.php?success=Status+updated'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = sanitize($_POST['title'] ?? '');
    $dept    = sanitize($_POST['department'] ?? '');
    $loc     = sanitize($_POST['location'] ?? '');
    $type    = sanitize($_POST['type'] ?? 'full-time');
    $exp     = sanitize($_POST['experience'] ?? '');
    $edu     = sanitize($_POST['education'] ?? '');
    $desc    = $_POST['description'] ?? '';
    $req     = $_POST['requirements'] ?? '';
    $salary  = sanitize($_POST['salary_range'] ?? '');
    $deadline= sanitize($_POST['deadline'] ?? '');
    $active  = isset($_POST['is_active']) ? 1 : 0;
    $slug    = strtolower(preg_replace('/[^a-z0-9]+/i','-',trim($title))).'-'.time();

    if ($id) {
        $stmt = $db->prepare("UPDATE jobs SET title=?,department=?,location=?,type=?,experience=?,education=?,description=?,requirements=?,salary_range=?,deadline=?,is_active=?,updated_at=NOW() WHERE id=?");
        $stmt->bind_param('ssssssssssii', $title,$dept,$loc,$type,$exp,$edu,$desc,$req,$salary,$deadline,$active,$id);
        $stmt->execute();
        header('Location: jobs.php?success=Job+updated'); exit;
    } else {
        $stmt = $db->prepare("INSERT INTO jobs (title,slug,department,location,type,experience,education,description,requirements,salary_range,deadline,is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssssssssi', $title,$slug,$dept,$loc,$type,$exp,$edu,$desc,$req,$salary,$deadline,$active);
        $stmt->execute();
        header('Location: jobs.php?success=Job+added'); exit;
    }
}

$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM jobs WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

$jobs = $db->query("SELECT j.*,(SELECT COUNT(*) FROM job_applications WHERE job_id=j.id) as app_count FROM jobs j ORDER BY j.created_at DESC")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit Job' : 'Post New Job' ?></h5>
    <a href="jobs.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label-admin">Job Title *</label><input type="text" name="title" class="form-control-admin" value="<?= htmlspecialchars($edit['title']??'') ?>" required></div>
        <div class="col-md-4"><label class="form-label-admin">Department</label><input type="text" name="department" class="form-control-admin" value="<?= htmlspecialchars($edit['department']??'') ?>"></div>
        <div class="col-md-4"><label class="form-label-admin">Location</label><input type="text" name="location" class="form-control-admin" value="<?= htmlspecialchars($edit['location']??'Addis Ababa') ?>"></div>
        <div class="col-md-4"><label class="form-label-admin">Job Type</label>
          <select name="type" class="form-control-admin">
            <?php foreach(['full-time'=>'Full-time','part-time'=>'Part-time','contract'=>'Contract','internship'=>'Internship'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= ($edit['type']??'')===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4"><label class="form-label-admin">Application Deadline</label><input type="date" name="deadline" class="form-control-admin" value="<?= $edit['deadline']??'' ?>"></div>
        <div class="col-md-4"><label class="form-label-admin">Experience Required</label><input type="text" name="experience" class="form-control-admin" value="<?= htmlspecialchars($edit['experience']??'') ?>" placeholder="e.g. 3+ years"></div>
        <div class="col-md-4"><label class="form-label-admin">Education</label><input type="text" name="education" class="form-control-admin" value="<?= htmlspecialchars($edit['education']??'') ?>" placeholder="e.g. BSc in Pharmacy"></div>
        <div class="col-md-4"><label class="form-label-admin">Salary Range</label><input type="text" name="salary_range" class="form-control-admin" value="<?= htmlspecialchars($edit['salary_range']??'') ?>" placeholder="e.g. Competitive"></div>
        <div class="col-12"><label class="form-label-admin">Job Description *</label><textarea name="description" class="form-control-admin" rows="6"><?= htmlspecialchars($edit['description']??'') ?></textarea></div>
        <div class="col-12"><label class="form-label-admin">Requirements</label><textarea name="requirements" class="form-control-admin" rows="4"><?= htmlspecialchars($edit['requirements']??'') ?></textarea></div>
        <div class="col-12 d-flex gap-4">
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_active" <?= ($edit['is_active']??1)?'checked':'' ?>> Active / Accepting Applications</label>
        </div>
        <div class="col-12"><button type="submit" class="btn btn-primary"><?= $edit ? 'Update Job' : 'Post Job' ?></button></div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-briefcase me-2"></i>All Jobs (<?= count($jobs) ?>)</h5>
    <a href="jobs.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Post Job</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Title</th><th>Department</th><th>Type</th><th>Deadline</th><th>Applications</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($jobs)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No jobs posted yet.</td></tr>
        <?php else: foreach($jobs as $j): ?>
        <tr>
          <td><strong><?= htmlspecialchars($j['title']) ?></strong></td>
          <td><?= htmlspecialchars($j['department']??'—') ?></td>
          <td><?= ucfirst($j['type']) ?></td>
          <td><?= $j['deadline'] ? date('M d, Y', strtotime($j['deadline'])) : '—' ?></td>
          <td><a href="applications.php?job=<?= $j['id'] ?>" class="badge bg-primary text-white"><?= $j['app_count'] ?> apps</a></td>
          <td><a href="jobs.php?action=toggle&id=<?= $j['id'] ?>" class="status-badge <?= $j['is_active']?'status-new':'status-rejected' ?>" style="cursor:pointer;text-decoration:none"><?= $j['is_active']?'Active':'Closed' ?></a></td>
          <td>
            <a href="jobs.php?action=edit&id=<?= $j['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <button onclick="confirmDelete('jobs.php?action=delete&id=<?= $j['id'] ?>','Delete this job?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
