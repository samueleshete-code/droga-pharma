<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Admin Users';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id && $id !== (int)$_SESSION['admin_id']) {
    $db->query("DELETE FROM admin_users WHERE id=$id");
    header('Location: users.php?success=User+deleted'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = sanitize($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = sanitize($_POST['role'] ?? 'editor');
    $active= isset($_POST['is_active']) ? 1 : 0;
    $pwd   = $_POST['password'] ?? '';

    if ($id) {
        if ($pwd) {
            $hash = password_hash($pwd, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE admin_users SET name=?,email=?,role=?,is_active=?,password=? WHERE id=?");
            $stmt->bind_param('sssisi', $name,$email,$role,$active,$hash,$id);
        } else {
            $stmt = $db->prepare("UPDATE admin_users SET name=?,email=?,role=?,is_active=? WHERE id=?");
            $stmt->bind_param('sssii', $name,$email,$role,$active,$id);
        }
        $stmt->execute();
        // Update session if editing self
        if ($id === (int)$_SESSION['admin_id']) $_SESSION['admin_name'] = $name;
        header('Location: users.php?success=User+updated'); exit;
    } else {
        if (!$pwd) { header('Location: users.php?action=add&error=Password+required'); exit; }
        $hash = password_hash($pwd, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO admin_users (name,email,password,role,is_active) VALUES (?,?,?,?,?)");
        $stmt->bind_param('ssssi', $name,$email,$hash,$role,$active);
        $stmt->execute();
        header('Location: users.php?success=User+created'); exit;
    }
}

$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM admin_users WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

$users = $db->query("SELECT * FROM admin_users ORDER BY name")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit User' : 'Add New User' ?></h5>
    <a href="users.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label-admin">Full Name *</label><input type="text" name="name" class="form-control-admin" value="<?= htmlspecialchars($edit['name']??'') ?>" required></div>
        <div class="col-md-6"><label class="form-label-admin">Email *</label><input type="email" name="email" class="form-control-admin" value="<?= htmlspecialchars($edit['email']??'') ?>" required></div>
        <div class="col-md-4"><label class="form-label-admin">Password <?= $edit?'(leave blank to keep)':'' ?> *</label><input type="password" name="password" class="form-control-admin" <?= $edit?'':'required' ?> placeholder="<?= $edit?'Leave blank to keep current':'' ?>"></div>
        <div class="col-md-4"><label class="form-label-admin">Role</label>
          <select name="role" class="form-control-admin">
            <?php foreach(['superadmin'=>'Super Admin','admin'=>'Admin','editor'=>'Editor'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= ($edit['role']??'')===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-4 d-flex align-items-end pb-2">
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_active" <?= ($edit['is_active']??1)?'checked':'' ?>> Active</label>
        </div>
        <div class="col-12"><button type="submit" class="btn btn-primary"><?= $edit?'Update User':'Create User' ?></button></div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-users-cog me-2"></i>Admin Users</h5>
    <a href="users.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add User</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Last Login</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><div class="d-flex align-items-center gap-2"><div class="admin-avatar" style="width:32px;height:32px;font-size:.8rem"><?= strtoupper(substr($u['name'],0,1)) ?></div><strong><?= htmlspecialchars($u['name']) ?></strong><?php if($u['id']==$_SESSION['admin_id']): ?> <span class="badge bg-primary">You</span><?php endif; ?></div></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><span class="badge <?= $u['role']==='superadmin'?'bg-danger':($u['role']==='admin'?'bg-warning text-dark':'bg-secondary') ?>"><?= ucfirst($u['role']) ?></span></td>
          <td><?= $u['last_login'] ? date('M d, Y H:i', strtotime($u['last_login'])) : 'Never' ?></td>
          <td><span class="status-badge <?= $u['is_active']?'status-new':'status-rejected' ?>"><?= $u['is_active']?'Active':'Inactive' ?></span></td>
          <td>
            <a href="users.php?action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <?php if($u['id'] != $_SESSION['admin_id']): ?>
            <button onclick="confirmDelete('users.php?action=delete&id=<?= $u['id'] ?>','Delete this user?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
