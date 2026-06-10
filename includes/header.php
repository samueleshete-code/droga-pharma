<?php
if (!defined('SITE_NAME')) require_once __DIR__ . '/config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$csrf = generateCSRF();

// Compute relative path from current script back to the droga-pharma root.
// Strategy: find 'droga-pharma' in the script path, then count how many
// directory levels exist AFTER it (excluding the filename itself).
$_scriptNorm = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$_parts      = explode('/', trim($_scriptNorm, '/'));
$_rootIdx    = array_search('droga-pharma', $_parts);
// Segments after 'droga-pharma' minus 1 for the filename = subdirectory depth
$_subDepth   = ($_rootIdx !== false) ? (count($_parts) - $_rootIdx - 2) : 0;
$_subDepth   = max(0, $_subDepth);
$root        = str_repeat('../', $_subDepth);
// index.php  → 0 levels deep → $root = ""
// pages/about.php → 1 level deep → $root = "../"
// admin/dashboard.php → 1 level deep → $root = "../"
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf" content="<?= $csrf ?>">

  <!-- SEO Meta -->
  <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . SITE_NAME : SITE_NAME . ' – Transforming Healthcare Across Africa' ?></title>
  <meta name="description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Droga Pharma PLC – Leading pharmaceutical distribution, medical equipment supply, and healthcare innovation across Africa.' ?>">
  <meta name="keywords" content="<?= isset($pageKeywords) ? htmlspecialchars($pageKeywords) : 'Droga Pharma, pharmaceutical Ethiopia, medical equipment, healthcare Africa' ?>">
  <meta name="author" content="Droga Pharma PLC">
  <meta name="robots" content="index, follow">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . SITE_NAME : SITE_NAME ?>">
  <meta property="og:description" content="<?= isset($pageDesc) ? htmlspecialchars($pageDesc) : 'Leading pharmaceutical distribution and healthcare innovation across Africa.' ?>">
  <meta property="og:image" content="<?= SITE_URL ?>/assets/images/og-image.jpg">
  <meta property="og:url" content="<?= SITE_URL ?>">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . SITE_NAME : SITE_NAME ?>">
  <meta name="twitter:image" content="<?= SITE_URL ?>/assets/images/og-image.jpg">

  <!-- Schema.org -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Droga Pharma PLC",
    "url": "<?= SITE_URL ?>",
    "logo": "<?= SITE_URL ?>/assets/images/logo.png",
    "contactPoint": { "@type": "ContactPoint", "telephone": "<?= SITE_PHONE ?>", "contactType": "customer service" },
    "address": { "@type": "PostalAddress", "addressLocality": "Addis Ababa", "addressCountry": "ET" }
  }
  </script>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= $root ?>assets/images/favicon.png">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Swiper -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

  <!-- AOS -->
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

  <!-- ✅ CSS loaded via relative paths – works regardless of parent folder name/spaces -->
  <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
  <link rel="stylesheet" href="<?= $root ?>assets/css/pages.css">
</head>
<body>

<!-- Preloader -->
<div id="preloader">
  <div class="preloader-inner">
    <img src="<?= $root ?>assets/images/logo.png" alt="Droga Pharma" width="120">
    <div class="preloader-bar"><div class="preloader-progress"></div></div>
  </div>
</div>

<!-- Back to Top -->
<button id="backToTop" aria-label="Back to top"><i class="fas fa-chevron-up"></i></button>

<!-- WhatsApp Float -->
<a href="https://wa.me/251911234567" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <i class="fab fa-whatsapp"></i>
</a>

<!-- ═══ NAVBAR ═══════════════════════════════════════════════════════════════ -->
<nav id="mainNavbar" class="navbar-main" role="navigation" aria-label="Main navigation">
  <div class="container">
    <div class="navbar-inner">

      <!-- Logo -->
      <a href="<?= SITE_URL ?>/" class="navbar-logo" aria-label="Droga Pharma Home">
        <img src="<?= $root ?>assets/images/logo.png" alt="Droga Pharma PLC" width="140" height="40">
      </a>

      <!-- Nav Menu -->
      <ul class="nav-menu" role="menubar">
        <li role="none"><a href="<?= SITE_URL ?>/" class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>" role="menuitem">Home</a></li>
        <li role="none"><a href="<?= SITE_URL ?>/pages/about.php" class="nav-link <?= $currentPage === 'about' ? 'active' : '' ?>" role="menuitem">About</a></li>

        <!-- Dropdown: Products -->
        <li class="nav-dropdown" role="none">
          <a href="<?= SITE_URL ?>/pages/products.php" class="nav-link <?= $currentPage === 'products' ? 'active' : '' ?>" role="menuitem" aria-haspopup="true">
            Products <i class="fas fa-chevron-down fa-xs"></i>
          </a>
          <div class="dropdown-menu-custom" role="menu">
            <a href="<?= SITE_URL ?>/pages/products.php?cat=pharmaceuticals" role="menuitem"><i class="fas fa-pills"></i> Pharmaceuticals</a>
            <a href="<?= SITE_URL ?>/pages/products.php?cat=medical-devices" role="menuitem"><i class="fas fa-stethoscope"></i> Medical Devices</a>
            <a href="<?= SITE_URL ?>/pages/products.php?cat=diagnostics" role="menuitem"><i class="fas fa-microscope"></i> Diagnostics</a>
            <a href="<?= SITE_URL ?>/pages/products.php?cat=laboratory" role="menuitem"><i class="fas fa-flask"></i> Laboratory Equipment</a>
            <a href="<?= SITE_URL ?>/pages/products.php?cat=surgical" role="menuitem"><i class="fas fa-syringe"></i> Surgical Products</a>
            <a href="<?= SITE_URL ?>/pages/products.php?cat=orthopedic" role="menuitem"><i class="fas fa-bone"></i> Orthopedic Solutions</a>
          </div>
        </li>

        <li role="none"><a href="<?= SITE_URL ?>/pages/services.php" class="nav-link <?= $currentPage === 'services' ? 'active' : '' ?>" role="menuitem">Services</a></li>
        <li role="none"><a href="<?= SITE_URL ?>/pages/partners.php" class="nav-link <?= $currentPage === 'partners' ? 'active' : '' ?>" role="menuitem">Partners</a></li>
        <li role="none"><a href="<?= SITE_URL ?>/pages/research.php" class="nav-link <?= $currentPage === 'research' ? 'active' : '' ?>" role="menuitem">Research</a></li>
        <li role="none"><a href="<?= SITE_URL ?>/pages/careers.php" class="nav-link <?= $currentPage === 'careers' ? 'active' : '' ?>" role="menuitem">Careers</a></li>
        <li role="none"><a href="<?= SITE_URL ?>/pages/news.php" class="nav-link <?= $currentPage === 'news' ? 'active' : '' ?>" role="menuitem">News</a></li>
      </ul>

      <!-- CTA + Toggle -->
      <div class="navbar-actions">
        <a href="<?= SITE_URL ?>/pages/contact.php" class="btn btn-primary btn-sm">
          <i class="fas fa-envelope"></i> Contact Us
        </a>
        <button class="nav-toggler" aria-label="Toggle navigation" aria-expanded="false">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    </div>
  </div>
</nav>
<!-- ═══ END NAVBAR ═══════════════════════════════════════════════════════════ -->
