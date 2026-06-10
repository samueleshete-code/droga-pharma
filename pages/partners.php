<?php
require_once '../includes/config.php';
$pageTitle = 'Partners';
$pageDesc  = 'Droga Pharma\'s global network of pharmaceutical and medical equipment partners – 50+ international brands trusted across Africa.';
require_once '../includes/header.php';

$db = getDB();
$countryFilter = sanitize($_GET['country'] ?? '');
$sql = "SELECT * FROM partners WHERE is_active=1";
if ($countryFilter) $sql .= " AND country = '" . $db->real_escape_string($countryFilter) . "'";
$sql .= " ORDER BY sort_order ASC, name ASC";
$partnersResult = $db->query($sql);
$partners = $partnersResult ? $partnersResult->fetch_all(MYSQLI_ASSOC) : [];
$countriesResult = $db->query("SELECT DISTINCT country FROM partners WHERE is_active=1 ORDER BY country");
$countries = $countriesResult ? array_column($countriesResult->fetch_all(MYSQLI_ASSOC), 'country') : [];
?>

<section class="page-hero" style="background-image: url('../assets/images/partners-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-handshake"></i> Partners</span>
      <h1 class="text-white mt-3">Our Global Partners</h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Partners</li>
      </ol>
    </div>
  </div>
</section>

<section class="partners-listing-section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Global Network</span>
      <h2 class="section-title">Trusted International Partners</h2>
      <p class="section-subtitle">We collaborate with world-leading pharmaceutical and medical device companies to bring the best healthcare solutions to Africa</p>
      <div class="section-divider"></div>
    </div>

    <!-- Country Filter -->
    <div class="partners-filter" data-aos="fade-up">
      <a href="partners.php" class="filter-btn <?= !$countryFilter ? 'active' : '' ?>">All Countries</a>
      <?php foreach ($countries as $c): ?>
      <a href="partners.php?country=<?= urlencode($c) ?>" class="filter-btn <?= $countryFilter === $c ? 'active' : '' ?>"><?= htmlspecialchars($c) ?></a>
      <?php endforeach; ?>
    </div>

    <!-- Partners Grid -->
    <div class="row g-4">
      <?php if (!empty($partners)): ?>
        <?php foreach ($partners as $i => $p): ?>
        <div class="col-lg-2 col-md-3 col-4" data-aos="fade-up" data-aos-delay="<?= ($i % 6) * 50 ?>">
          <div class="partner-card">
            <img src="<?= SITE_URL ?>/uploads/partners/<?= htmlspecialchars($p['logo']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
            <div class="partner-card-info">
              <strong><?= htmlspecialchars($p['name']) ?></strong>
              <span><?= htmlspecialchars($p['country']) ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php
        $demoPartners = [
          ['name'=>'Pfizer','country'=>'USA'],['name'=>'Novartis','country'=>'Switzerland'],
          ['name'=>'Roche','country'=>'Switzerland'],['name'=>'Abbott','country'=>'USA'],
          ['name'=>'Siemens Healthineers','country'=>'Germany'],['name'=>'Philips Healthcare','country'=>'Netherlands'],
          ['name'=>'GE Healthcare','country'=>'USA'],['name'=>'Becton Dickinson','country'=>'USA'],
          ['name'=>'3M Health Care','country'=>'USA'],['name'=>'Medtronic','country'=>'Ireland'],
          ['name'=>'Johnson & Johnson','country'=>'USA'],['name'=>'Bayer','country'=>'Germany'],
          ['name'=>'AstraZeneca','country'=>'UK'],['name'=>'Sanofi','country'=>'France'],
          ['name'=>'GSK','country'=>'UK'],['name'=>'Merck','country'=>'Germany'],
          ['name'=>'Boehringer Ingelheim','country'=>'Germany'],['name'=>'Fresenius','country'=>'Germany'],
        ];
        foreach ($demoPartners as $i => $p): ?>
        <div class="col-lg-2 col-md-3 col-4" data-aos="fade-up" data-aos-delay="<?= ($i % 6) * 50 ?>">
          <div class="partner-card">
            <div class="partner-logo-placeholder"><span><?= $p['name'] ?></span></div>
            <div class="partner-card-info">
              <strong><?= $p['name'] ?></strong>
              <span><?= $p['country'] ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Partnership CTA -->
    <div class="partnership-cta" data-aos="fade-up">
      <div class="row align-items-center g-4">
        <div class="col-lg-8">
          <h3>Become a Partner</h3>
          <p>Are you a pharmaceutical or medical device manufacturer looking to expand into the Ethiopian and East African market? We'd love to partner with you.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
          <a href="contact.php?subject=Partnership" class="btn btn-primary btn-lg">
            <i class="fas fa-handshake"></i> Partner With Us
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
