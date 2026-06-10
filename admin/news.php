<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'News Management';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

if ($action === 'delete' && $id) {
    $db->query("DELETE FROM news WHERE id=$id");
    header('Location: news.php?success=Article+deleted'); exit;
}
if ($action === 'toggle' && $id) {
    $db->query("UPDATE news SET is_published = 1-is_published WHERE id=$id");
    header('Location: news.php?success=Status+updated'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = sanitize($_POST['title'] ?? '');
    $excerpt  = sanitize($_POST['excerpt'] ?? '');
    $content  = $_POST['content'] ?? '';
    $category = sanitize($_POST['category'] ?? '');
    $tags     = sanitize($_POST['tags'] ?? '');
    $pub      = isset($_POST['is_published']) ? 1 : 0;
    $feat     = isset($_POST['is_featured']) ? 1 : 0;
    $slug     = strtolower(preg_replace('/[^a-z0-9]+/i','-', trim($title))).'-'.time();
    $authorId = $_SESSION['admin_id'];

    $image = sanitize($_POST['existing_image'] ?? '');
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png','webp']) && $_FILES['image']['size']<3*1024*1024) {
            $dir = __DIR__.'/../uploads/news/';
            if (!is_dir($dir)) mkdir($dir,0755,true);
            $fname = uniqid('news_').'.'.$ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $dir.$fname);
            $image = $fname;
        }
    }

    if ($id) {
        $stmt = $db->prepare("UPDATE news SET title=?,excerpt=?,content=?,category=?,tags=?,image=?,is_published=?,is_featured=?,published_at=IF(?=1,NOW(),NULL),updated_at=NOW() WHERE id=?");
        $stmt->bind_param('ssssssiiii', $title,$excerpt,$content,$category,$tags,$image,$pub,$feat,$pub,$id);
        $stmt->execute();
        header('Location: news.php?success=Article+updated'); exit;
    } else {
        $stmt = $db->prepare("INSERT INTO news (title,slug,excerpt,content,category,tags,image,is_published,is_featured,author_id,published_at) VALUES (?,?,?,?,?,?,?,?,?,?,IF(?=1,NOW(),NULL))");
        $stmt->bind_param('sssssssiiii', $title,$slug,$excerpt,$content,$category,$tags,$image,$pub,$feat,$authorId,$pub);
        $stmt->execute();
        header('Location: news.php?success=Article+added'); exit;
    }
}

$edit = null;
if ($action === 'edit' && $id) {
    $r = $db->query("SELECT * FROM news WHERE id=$id");
    $edit = $r ? $r->fetch_assoc() : null;
}

$articles = $db->query("SELECT n.*,u.name as author FROM news n LEFT JOIN admin_users u ON n.author_id=u.id ORDER BY n.created_at DESC")->fetch_all(MYSQLI_ASSOC);
require_once 'includes/admin_header.php';
?>

<?php if ($edit || $action==='add'): ?>
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <h5><?= $edit ? 'Edit Article' : 'Add New Article' ?></h5>
    <a href="news.php" class="btn btn-sm btn-outline-secondary">Cancel</a>
  </div>
  <div class="admin-card-body p-4">
    <form method="POST" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label-admin">Title *</label>
          <input type="text" name="title" class="form-control-admin" value="<?= htmlspecialchars($edit['title']??'') ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Category</label>
          <input type="text" name="category" class="form-control-admin" value="<?= htmlspecialchars($edit['category']??'') ?>" placeholder="e.g. Company News">
        </div>
        <div class="col-12">
          <label class="form-label-admin">Excerpt</label>
          <textarea name="excerpt" class="form-control-admin" rows="2"><?= htmlspecialchars($edit['excerpt']??'') ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label-admin">Content *</label>
          <textarea name="content" id="articleContent" class="form-control-admin" rows="12"><?= htmlspecialchars($edit['content']??'') ?></textarea>
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Tags (comma separated)</label>
          <input type="text" name="tags" class="form-control-admin" value="<?= htmlspecialchars($edit['tags']??'') ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label-admin">Featured Image</label>
          <input type="file" name="image" class="form-control-admin" accept="image/*">
          <?php if(!empty($edit['image'])): ?>
          <small class="text-muted">Current: <?= htmlspecialchars($edit['image']) ?></small>
          <input type="hidden" name="existing_image" value="<?= htmlspecialchars($edit['image']) ?>">
          <?php endif; ?>
        </div>
        <div class="col-md-4 d-flex gap-4 align-items-end pb-2">
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_published" <?= ($edit['is_published']??0)?'checked':'' ?>> Published</label>
          <label class="d-flex align-items-center gap-2"><input type="checkbox" name="is_featured" <?= ($edit['is_featured']??0)?'checked':'' ?>> Featured</label>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary"><?= $edit ? 'Update Article' : 'Publish Article' ?></button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5><i class="fas fa-newspaper me-2"></i>All Articles (<?= count($articles) ?>)</h5>
    <a href="news.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Article</a>
  </div>
  <div class="table-responsive">
    <table class="admin-table">
      <thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Author</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if(empty($articles)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No articles yet.</td></tr>
        <?php else: foreach($articles as $a): ?>
        <tr>
          <td><?php if($a['image']): ?><img src="<?= SITE_URL ?>/uploads/news/<?= htmlspecialchars($a['image']) ?>" width="60" height="40" style="object-fit:cover;border-radius:6px"><?php else: ?><div style="width:60px;height:40px;background:#f1f5f9;border-radius:6px"></div><?php endif; ?></td>
          <td><strong><?= htmlspecialchars($a['title']) ?></strong><?php if($a['is_featured']): ?> <span class="badge bg-warning text-dark ms-1">Featured</span><?php endif; ?></td>
          <td><?= htmlspecialchars($a['category']??'—') ?></td>
          <td><?= htmlspecialchars($a['author']??'—') ?></td>
          <td>
            <a href="news.php?action=toggle&id=<?= $a['id'] ?>" class="status-badge <?= $a['is_published']?'status-new':'status-rejected' ?>" style="cursor:pointer;text-decoration:none">
              <?= $a['is_published']?'Published':'Draft' ?>
            </a>
          </td>
          <td><?= date('M d, Y', strtotime($a['created_at'])) ?></td>
          <td>
            <a href="news.php?action=edit&id=<?= $a['id'] ?>" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></a>
            <a href="<?= SITE_URL ?>/pages/news.php?slug=<?= htmlspecialchars($a['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary me-1"><i class="fas fa-eye"></i></a>
            <button onclick="confirmDelete('news.php?action=delete&id=<?= $a['id'] ?>','Delete this article?')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
