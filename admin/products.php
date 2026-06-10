<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Products';

// Handle actions
$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM products WHERE id=$id");
    header('Location: products.php?success=Product+deleted'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = sanitize($_POST['name'] ?? '');
    $catId  = (int)($_POST['category_id'] ?? 0);
    $short  = sanitize($_POST['short_description'] ?? '');
    $desc   = $_POST['description'] ?? '';
    $mfr    = sanitize($_POST['manufacturer'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;
    $feat   = isset($_POST['is_featured']) ? 1 : 0;
    $sort   = (int)($_POST['sort_order'] ?? 0);
    $slug   = strtolower(preg_replace('/[^a-z0-9]+/i','-', trim($name)));

    // Image upload
    $image = sanitize($_POST['existing_image'] ?? '');
    if (!empty($_FILES['image']['name'])) {
        $ext  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];
        if (in_array($ext, $allowed) && $_FILES['image']['size'] < 2*1024*1024) {
            $dir = __DIR__ . '/../uploads/products/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $fname = uniqid('prod_').'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $dir.$fname);
            $image = $fname;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE products SET name=?,slug=?,category_id=?,short_description=?,description=?,manufacturer=?,image=?,is_active=?,is_featured=?,sort_order=?,updated_at=NOW() WHERE id=?");
        $stmt->bind_param('ssissssiii i', $name,$slug,$catId,$short,$desc,$mfr,$image,$active,$feat,$sort,$id);
        $stmt->execute();
        header('Location: products.php?success=Product+updated'); exit;
    } else {
        $stmt = $db->prepare("INSERT INTO products (name,slug,category_id,short_description,description,manufacturer,image,is_active,is_featured,sort_order) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssissssiii', $name,$slug,$catId,$short,$desc,$mfr,$image,$active,$feat,$sort);
        $stmt->execute();
        header('Location: products.php?success=Product+added'); exit;
    }
}

// Edit mode
$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM products WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

// Fetch all
$search = sanitize($_GET['search'] ?? '');
$catFilter = (int)($_GET['cat'] ?? 0);
$sql = "SELECT p.*,c.name as cat_name FROM products p LEFT JOIN product_categories c ON p.category_id=c.id WHERE 1";
if ($search) $sql .= " AND p.name LIKE '%".$db->real_escape_string($search)."%'";
if ($catFilter) $sql .= " AND p.category_id=$catFilter";
$sql .= " ORDER BY p.sort_order,p.name";
$products = $db->query($sql)->fetch_all(MYSQLI_ASSOC);
$cats = $db->query("SELECT * FROM product_categories ORDER BY sort_order")->fetch_all(MYSQLI_ASSOC);

require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<!-- ── Form ── -->
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit Product' : 'Add New Product' ?></h5>
    <a href="products.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label-admin">Product Name *</label>
          <input type="text" name="name" class="form-control-admin" value="<?= htmlspecialchars($edit['name']??'') ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Category</label>
          <select name="category_id" class="form-control-admin">
            <option value="">-- Select --</option>
            <?php foreach($cats as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($edit['category_id']??'')==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label-admin">Short Description</label>
          <input type="text" name="short_description" class="form-control-admin" value="<?= htmlspecialchars($edit['short_description']??'') ?>">
        </div>
        <div class="col-12">
          <label class="form-label-admin">Full Description</label>
          <textarea name="description" class="form-control-admin" rows="5"><?= htmlspecialchars($edit['description']??'') ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Manufacturer</label>
          <input type="text" name="manufacturer" class="form-control-admin" value="<?= htmlspecialchars($edit['manufacturer']??'') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Sort Order</label>
          <input type="number" name="sort_order" class="form-control-admin" value="<?= $edit['sort_order']??0 ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Product Image</label>
          <input type="file" name="image" class="form-control-admin" accept="image/*">
          <?php if (!empty($edit['image'])): ?>
          <small class="text-muted">Current: <?= htmlspecialchars($edit['image']) ?></small>
          <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit['image']) ?>">
          <?php endif; ?>
        </div>
        <div class="col-md-6 d-flex gap-4 align-items-center">
          <label class="d-flex align-items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" <?= ($edit['is_active']??1)?'checked':'' ?>> Active
          </label>
          <label class="d-flex align-items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_featured" <?= ($edit['is_featured']??0)?'checked':'' ?>> Featured
          </label>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><?= $edit ? 'Update Product' : 'Add Product' ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- ── List ── -->
<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-pills me-2"></i>All Products (<?= count($products) ?>)</h5>
    <a href="products.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Product</a>
  </div>
  <div class="p-3 border-bottom d-flex gap-2 flex-wrap">
    <form method="GET" class="d-flex gap-2 flex-wrap">
      <input type="text" name="search" class="form-control-admin" style="width:220px" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
      <select name="cat" class="form-control-admin" style="width:180px">
        <option value="">All Categories</option>
        <?php foreach($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $catFilter==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
      <a href="products.php" class="btn btn-sm btn-outline-secondary">Reset</a>
    </form>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Manufacturer</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($products)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No products found.</td></tr>
        <?php else: foreach($products as $p): ?>
        <tr>
          <td>
            <?php if($p['image']): ?>
            <img src="<?= SITE_URL ?>/uploads/products/<?= htmlspecialchars($p['image']) ?>" width="50" height="40" style="object-fit:cover;border-radius:6px">
            <?php else: ?><div style="width:50px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center"><i class="fas fa-image text-muted"></i></div><?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($p['name']) ?></strong><?php if($p['is_featured']): ?> <span class="badge bg-warning text-dark ms-1">Featured</span><?php endif; ?></td>
          <td><?= htmlspecialchars($p['cat_name']??'—') ?></td>
          <td><?= htmlspecialchars($p['manufacturer']??'—') ?></td>
          <td><span class="status-badge <?= $p['is_active']?'status-new':'status-rejected' ?>"><?= $p['is_active']?'Active':'Inactive' ?></span></td>
          <td>
            <a href="products.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <button onclick="confirmDelete('products.php?action=delete&id=<?= $p['id'] ?>','Delete this product?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
