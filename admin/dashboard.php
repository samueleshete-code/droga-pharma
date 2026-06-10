<?php
require_once '../includes/config.php';
requireAdmin();
$db = getDB();
$pageTitle = 'Dashboard';

$stats = [
  'products'   => $db->query("SELECT COUNT(*) FROM products WHERE is_active=1")->fetch_row()[0] ?? 0,
  'news'       => $db->query("SELECT COUNT(*) FROM news WHERE is_published=1")->fetch_row()[0] ?? 0,
  'jobs'       => $db->query("SELECT COUNT(*) FROM jobs WHERE is_active=1")->fetch_row()[0] ?? 0,
  'messages'   => $db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetch_row()[0] ?? 0,
  'partners'   => $db->query("SELECT COUNT(*) FROM partners WHERE is_active=1")->fetch_row()[0] ?? 0,
  'applicants' => $db->query("SELECT COUNT(*) FROM job_applications WHERE status='pending'")->fetch_row()[0] ?? 0,
  'subscribers'=> $db->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE is_active=1")->fetch_row()[0] ?? 0,
];

$recentMessages = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 6")?->fetch_all(MYSQLI_ASSOC) ?? [];
$recentApps     = $db->query("SELECT a.*,j.title as job_title FROM job_applications a LEFT JOIN jobs j ON a.job_id=j.id ORDER BY a.applied_at DESC LIMIT 6")?->fetch_all(MYSQLI_ASSOC) ?? [];
$recentNews     = $db->query("SELECT id,title,is_published,created_at FROM news ORDER BY created_at DESC LIMIT 5")?->fetch_all(MYSQLI_ASSOC) ?? [];

require_once 'includes/admin_header.php';
?>

<!-- Welcome -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h4 class="mb-1">Welcome back, <?= htmlspecialchars(explode(' ',$_SESSION['admin_name']??'Admin')[0]) ?> 👋</h4>
    <p class="text-muted mb-0">Here's what's happening with your website today.</p>
  </div>
  <div class="d-flex gap-2">
    <a href="<?= SITE_URL ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-external-link-alt me-1"></i>View Site</a>
    <a href="news.php?action=add" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Article</a>
  </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
  <?php
  $cards = [
    ['label'=>'Active Products',   'value'=>$stats['products'],   'icon'=>'fa-pills',       'color'=>'#0066CC','link'=>'products.php'],
    ['label'=>'Published Articles','value'=>$stats['news'],       'icon'=>'fa-newspaper',   'color'=>'#00AEEF','link'=>'news.php'],
    ['label'=>'Open Jobs',         'value'=>$stats['jobs'],       'icon'=>'fa-briefcase',   'color'=>'#00D084','link'=>'jobs.php'],
    ['label'=>'Unread Messages',   'value'=>$stats['messages'],   'icon'=>'fa-envelope',    'color'=>'#DC2626','link'=>'messages.php'],
    ['label'=>'Active Partners',   'value'=>$stats['partners'],   'icon'=>'fa-handshake',   'color'=>'#7C3AED','link'=>'partners.php'],
    ['label'=>'New Applications',  'value'=>$stats['applicants'], 'icon'=>'fa-user-tie',    'color'=>'#D97706','link'=>'applications.php'],
    ['label'=>'Subscribers',       'value'=>$stats['subscribers'],'icon'=>'fa-mail-bulk',   'color'=>'#0891B2','link'=>'newsletter.php'],
  ];
  foreach ($cards as $c): ?>
  <div class="col-xl-3 col-lg-4 col-md-4 col-6">
    <a href="<?= $c['link'] ?>" class="stat-card-admin">
      <div class="sca-icon" style="background:<?= $c['color'] ?>1a;color:<?= $c['color'] ?>"><i class="fas <?= $c['icon'] ?>"></i></div>
      <div class="sca-value"><?= number_format($c['value']) ?></div>
      <div class="sca-label"><?= $c['label'] ?></div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <!-- Recent Messages -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5><i class="fas fa-envelope me-2"></i>Recent Messages</h5>
        <a href="messages.php" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="admin-card-body">
        <?php if(empty($recentMessages)): ?>
        <p class="text-muted text-center py-3 mb-0">No messages yet.</p>
        <?php else: foreach($recentMessages as $m): ?>
        <div class="activity-item">
          <div class="activity-avatar"><?= strtoupper(substr($m['name'],0,1)) ?></div>
          <div class="activity-info">
            <strong><?= htmlspecialchars($m['name']) ?></strong>
            <span><?= htmlspecialchars($m['subject']??'No subject') ?></span>
            <small><?= date('M d, H:i', strtotime($m['created_at'])) ?></small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <?php if(!$m['is_read']): ?><span class="unread-dot"></span><?php endif; ?>
            <a href="messages.php?action=view&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Recent Applications -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5><i class="fas fa-user-tie me-2"></i>Recent Applications</h5>
        <a href="applications.php" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="admin-card-body">
        <?php if(empty($recentApps)): ?>
        <p class="text-muted text-center py-3 mb-0">No applications yet.</p>
        <?php else: foreach($recentApps as $a): ?>
        <div class="activity-item">
          <div class="activity-avatar"><?= strtoupper(substr($a['full_name'],0,1)) ?></div>
          <div class="activity-info">
            <strong><?= htmlspecialchars($a['full_name']) ?></strong>
            <span><?= htmlspecialchars($a['job_title']??'Unknown Job') ?></span>
            <small><?= date('M d, H:i', strtotime($a['applied_at'])) ?></small>
          </div>
          <span class="status-badge status-new">New</span>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Recent Articles -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5><i class="fas fa-newspaper me-2"></i>Recent Articles</h5>
        <a href="news.php" class="btn btn-sm btn-outline-primary">View All</a>
      </div>
      <div class="admin-card-body">
        <?php if(empty($recentNews)): ?>
        <p class="text-muted text-center py-3 mb-0">No articles yet.</p>
        <?php else: foreach($recentNews as $n): ?>
        <div class="activity-item">
          <div class="activity-avatar" style="background:#00AEEF"><i class="fas fa-newspaper" style="font-size:.8rem"></i></div>
          <div class="activity-info">
            <strong><?= htmlspecialchars($n['title']) ?></strong>
            <small><?= date('M d, Y', strtotime($n['created_at'])) ?></small>
          </div>
          <div class="d-flex gap-2 align-items-center">
            <span class="status-badge <?= $n['is_published']?'status-new':'status-rejected' ?>"><?= $n['is_published']?'Live':'Draft' ?></span>
            <a href="news.php?action=edit&id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="col-lg-6">
    <div class="admin-card">
      <div class="admin-card-header"><h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5></div>
      <div class="admin-card-body p-4">
        <div class="row g-3">
          <div class="col-6"><a href="products.php?action=add" class="btn btn-outline-primary w-100"><i class="fas fa-plus me-2"></i>Add Product</a></div>
          <div class="col-6"><a href="news.php?action=add" class="btn btn-outline-primary w-100"><i class="fas fa-plus me-2"></i>Write Article</a></div>
          <div class="col-6"><a href="jobs.php?action=add" class="btn btn-outline-primary w-100"><i class="fas fa-plus me-2"></i>Post Job</a></div>
          <div class="col-6"><a href="partners.php?action=add" class="btn btn-outline-primary w-100"><i class="fas fa-plus me-2"></i>Add Partner</a></div>
          <div class="col-6"><a href="messages.php" class="btn btn-outline-danger w-100"><i class="fas fa-envelope me-2"></i>Messages <?php if($stats['messages']>0): ?><span class="badge bg-danger"><?= $stats['messages'] ?></span><?php endif; ?></a></div>
          <div class="col-6"><a href="settings.php" class="btn btn-outline-secondary w-100"><i class="fas fa-cog me-2"></i>Settings</a></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
