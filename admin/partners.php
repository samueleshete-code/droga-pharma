<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Partners';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM partners WHERE id=$id");
    header('Location: partners.php?success=Partner+deleted'); exit;
}
if ($action === 'toggle' && $id) {
    $db->query("UPDATE partners SET is_active=1-is_active WHERE id=$id");
    header('Location: partners.php?success=Status+updated'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($_POST['name'] ?? '');
    $country = sanitize($_POST['country'] ?? '');
    $website = sanitize($_POST['website'] ?? '');
    $type    = sanitize($_POST['partnership_type'] ?? 'manufacturer');
    $desc    = sanitize($_POST['description'] ?? '');
    $active  = isset($_POST['is_active']) ? 1 : 0;
    $feat    = isset($_POST['is_featured']) ? 1 : 0;
    $sort    = (int)($_POST['sort_order'] ?? 0);

    $logo = sanitize($_POST['existing_logo'] ?? '');
    if (!empty($_FILES['logo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png','webp','svg']) && $_FILES['logo']['size']<2*1024*1024) {
            $dir = __DIR__.'/../uploads/partners/';
            if (!is_dir($dir)) mkdir($dir,0755,true);
            $fname = uniqid('partner_').'.'.$ext;
            move_uploaded_file($_FILES['logo']['tmp_name'], $dir.$fname);
            $logo = $fname;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE partners SET name=?,country=?,website=?,partnership_type=?,description=?,logo=?,is_active=?,is_featured=?,sort_order=? WHERE id=?");
        $stmt->bind_param('ssssssiiii', $name,$country,$website,$type,$desc,$logo,$active,$feat,$sort,$id);
        $stmt->execute();
        header('Location: partners.php?success=Partner+updated'); exit;
    } else {
        $stmt = $db->prepare("INSERT INTO partners (name,country,website,partnership_type,description,logo,is_active,is_featured,sort_order) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssssii', $name,$country,$website,$type,$desc,$logo,$active,$feat,$sort);
        $stmt->execute();
        header('Location: partners.php?success=Partner+added'); exit;
    }
}

$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM partners WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

$partners = $db->query("SELECT * FROM partners ORDER BY sort_order,name")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit Partner' : 'Add New Partner' ?></h5>
    <a href="partners.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label-admin">Partner Name *</label><input type="text" name="name" class="form-control-admin" value="<?= htmlspecialchars($edit['name']??'') ?>" required></div>
        <div class="col-md-3"><label class="form-label-admin">Country</label><input type="text" name="country" class="form-control-admin" value="<?= htmlspecialchars($edit['country']??'') ?>"></div>
        <div class="col-md-3"><label class="form-label-admin">Partnership Type</label>
          <select name="partnership_type" class="form-control-admin">
            <?php foreach(['manufacturer','distributor','hospital','research','government'] as $t): ?>
            <option value="<?= $t ?>" <?= ($edit['partnership_type']??'')===$t?'selected':'' ?>><?= ucfirst($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6"><label class="form-label-admin">Website</label><input type="url" name="website" class="form-control-admin" value="<?= htmlspecialchars($edit['website']??'') ?>"></div>
        <div class="col-md-3"><label class="form-label-admin">Sort Order</label><input type="number" name="sort_order" class="form-control-admin" value="<?= $edit['sort_order']??0 ?>"></div>
        <div class="col-md-3"><label class="form-label-admin">Logo</label>
          <input type="file" name="logo" class="form-control-admin" accept="image/*">
          <?php if(!empty($edit['logo'])): ?><small class="text-muted">Current: <?= htmlspecialchars($edit['logo']) ?></small><input type="hidden" name="existing_logo" value="<?= htmlspecialchars($edit['logo']) ?>"><?php endif; ?>
        </div>
        <div class="col-12"><label class="form-label-admin">Description</label><textarea name="description" class="form-control-admin" rows="3"><?= htmlspecialchars($edit['description']??'') ?></textarea></div>
        <div class="col-12 d-flex gap-4">
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_active" <?= ($edit['is_active']??1)?'checked':'' ?>> Active</label>
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_featured" <?= ($edit['is_featured']??0)?'checked':'' ?>> Featured</label>
        </div>
        <div class="col-12"><button type="submit" class="btn btn-primary"><?= $edit ? 'Update Partner' : 'Add Partner' ?></button></div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-handshake me-2"></i>All Partners (<?= count($partners) ?>)</h5>
    <a href="partners.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Partner</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Logo</th><th>Name</th><th>Country</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($partners)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No partners yet.</td></tr>
        <?php else: foreach($partners as $p): ?>
        <tr>
          <td><?php if($p['logo']): ?><img src="<?= SITE_URL ?>/uploads/partners/<?= htmlspecialchars($p['logo']) ?>" height="36" style="object-fit:contain"><?php else: ?><span class="text-muted">—</span><?php endif; ?></td>
          <td><strong><?= htmlspecialchars($p['name']) ?></strong><?php if($p['is_featured']): ?> <span class="badge bg-warning text-dark ms-1">Featured</span><?php endif; ?></td>
          <td><?= htmlspecialchars($p['country']??'—') ?></td>
          <td><?= ucfirst($p['partnership_type']??'—') ?></td>
          <td><a href="partners.php?action=toggle&id=<?= $p['id'] ?>" class="status-badge <?= $p['is_active']?'status-new':'status-rejected' ?>" style="cursor:pointer;text-decoration:none"><?= $p['is_active']?'Active':'Inactive' ?></a></td>
          <td>
            <a href="partners.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <button onclick="confirmDelete('partners.php?action=delete&id=<?= $p['id'] ?>','Delete this partner?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
