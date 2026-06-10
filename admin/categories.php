<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Product Categories';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM product_categories WHERE id=$id");
    header('Location: categories.php?success=Category+deleted'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = sanitize($_POST['name'] ?? '');
    $slug   = strtolower(preg_replace('/[^a-z0-9]+/i','-',trim($name)));
    $desc   = sanitize($_POST['description'] ?? '');
    $icon   = sanitize($_POST['icon'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;
    $sort   = (int)($_POST['sort_order'] ?? 0);

    if ($id) {
        $stmt = $db->prepare("UPDATE product_categories SET name=?,slug=?,description=?,icon=?,is_active=?,sort_order=? WHERE id=?");
        $stmt->bind_param('sssssii', $name,$slug,$desc,$icon,$active,$sort,$id);
        $stmt->execute();
        header('Location: categories.php?success=Category+updated'); exit;
    } else {
        $stmt = $db->prepare("INSERT INTO product_categories (name,slug,description,icon,is_active,sort_order) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssii', $name,$slug,$desc,$icon,$active,$sort);
        $stmt->execute();
        header('Location: categories.php?success=Category+added'); exit;
    }
}

$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM product_categories WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

$cats = $db->query("SELECT c.*,(SELECT COUNT(*) FROM products WHERE category_id=c.id) as prod_count FROM product_categories c ORDER BY c.sort_order,c.name")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit Category' : 'Add Category' ?></h5>
    <a href="categories.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-4"><label class="form-label-admin">Name *</label><input type="text" name="name" class="form-control-admin" value="<?= htmlspecialchars($edit['name']??'') ?>" required></div>
        <div class="col-md-4"><label class="form-label-admin">Icon (Font Awesome class)</label><input type="text" name="icon" class="form-control-admin" value="<?= htmlspecialchars($edit['icon']??'') ?>" placeholder="fa-pills"></div>
        <div class="col-md-2"><label class="form-label-admin">Sort Order</label><input type="number" name="sort_order" class="form-control-admin" value="<?= $edit['sort_order']??0 ?>"></div>
        <div class="col-md-2 d-flex align-items-end pb-2"><label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_active" <?= ($edit['is_active']??1)?'checked':'' ?>> Active</label></div>
        <div class="col-12"><label class="form-label-admin">Description</label><textarea name="description" class="form-control-admin" rows="2"><?= htmlspecialchars($edit['description']??'') ?></textarea></div>
        <div class="col-12"><button type="submit" class="btn btn-primary"><?= $edit?'Update':'Add Category' ?></button></div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-tags me-2"></i>Product Categories</h5>
    <a href="categories.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Category</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Icon</th><th>Name</th><th>Slug</th><th>Products</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($cats as $c): ?>
        <tr>
          <td><i class="fas <?= htmlspecialchars($c['icon']??'fa-tag') ?> fa-lg" style="color:#0066CC"></i></td>
          <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
          <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
          <td><span class="badge bg-primary"><?= $c['prod_count'] ?></span></td>
          <td><span class="status-badge <?= $c['is_active']?'status-new':'status-rejected' ?>"><?= $c['is_active']?'Active':'Inactive' ?></span></td>
          <td>
            <a href="categories.php?action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <button onclick="confirmDelete('categories.php?action=delete&id=<?= $c['id'] ?>','Delete category? Products will be uncategorized.')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
