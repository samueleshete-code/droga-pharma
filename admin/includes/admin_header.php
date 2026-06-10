<?php
if (!defined('SITE_NAME')) require_once '../../includes/config.php';
requireAdmin();
$adminPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' – Admin' : 'Admin Panel' ?> | <?= SITE_NAME ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
  <?php if (isset($extraHead)) echo $extraHead; ?>
</head>
<body class="admin-body">

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <span style="color:#fff;font-family:'Poppins',sans-serif;font-weight:700;font-size:1.1rem;">Droga Admin</span>
    <button class="sidebar-close d-lg-none" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-label">Main</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="sidebar-link <?= $adminPage==='dashboard'?'active':'' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <div class="nav-section-label">Content</div>
    <a href="<?= SITE_URL ?>/admin/products.php" class="sidebar-link <?= $adminPage==='products'?'active':'' ?>"><i class="fas fa-pills"></i> Products</a>
    <a href="<?= SITE_URL ?>/admin/categories.php" class="sidebar-link <?= $adminPage==='categories'?'active':'' ?>"><i class="fas fa-tags"></i> Categories</a>
    <a href="<?= SITE_URL ?>/admin/news.php" class="sidebar-link <?= $adminPage==='news'?'active':'' ?>"><i class="fas fa-newspaper"></i> News</a>
    <a href="<?= SITE_URL ?>/admin/partners.php" class="sidebar-link <?= $adminPage==='partners'?'active':'' ?>"><i class="fas fa-handshake"></i> Partners</a>

    <div class="nav-section-label">Recruitment</div>
    <a href="<?= SITE_URL ?>/admin/jobs.php" class="sidebar-link <?= $adminPage==='jobs'?'active':'' ?>"><i class="fas fa-briefcase"></i> Jobs</a>
    <a href="<?= SITE_URL ?>/admin/applications.php" class="sidebar-link <?= $adminPage==='applications'?'active':'' ?>">
      <i class="fas fa-user-tie"></i> Applications
      <?php
        $db=getDB();
        $nc=$db->query("SELECT COUNT(*) FROM job_applications WHERE status='pending'")->fetch_row()[0]??0;
        if($nc>0) echo "<span class='badge-count'>$nc</span>";
      ?>
    </a>

    <div class="nav-section-label">Communication</div>
    <a href="<?= SITE_URL ?>/admin/messages.php" class="sidebar-link <?= $adminPage==='messages'?'active':'' ?>">
      <i class="fas fa-envelope"></i> Messages
      <?php
        $nm=$db->query("SELECT COUNT(*) FROM contact_messages WHERE is_read=0")->fetch_row()[0]??0;
        if($nm>0) echo "<span class='badge-count'>$nm</span>";
      ?>
    </a>
    <a href="<?= SITE_URL ?>/admin/newsletter.php" class="sidebar-link <?= $adminPage==='newsletter'?'active':'' ?>"><i class="fas fa-mail-bulk"></i> Newsletter</a>

    <div class="nav-section-label">System</div>
    <a href="<?= SITE_URL ?>/admin/users.php" class="sidebar-link <?= $adminPage==='users'?'active':'' ?>"><i class="fas fa-users-cog"></i> Users</a>
    <a href="<?= SITE_URL ?>/admin/settings.php" class="sidebar-link <?= $adminPage==='settings'?'active':'' ?>"><i class="fas fa-cog"></i> Settings</a>
    <a href="<?= SITE_URL ?>" target="_blank" class="sidebar-link"><i class="fas fa-external-link-alt"></i> View Site</a>
    <a href="<?= SITE_URL ?>/admin/logout.php" class="sidebar-link" style="color:#f87171"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </nav>
</aside>

<div class="admin-main">
  <header class="admin-topbar">
    <button class="topbar-toggle d-lg-none" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="topbar-title"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard' ?></div>
    <div class="topbar-actions">
      <div class="admin-user">
        <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin_name']??'A',0,1)) ?></div>
        <span><?= htmlspecialchars($_SESSION['admin_name']??'Admin') ?></span>
      </div>
    </div>
  </header>
  <div class="admin-content">
