<?php
require_once '../includes/config.php';
$pageTitle = 'Services';
$pageDesc  = 'Droga Pharma PLC services – pharmaceutical distribution, supply chain management, healthcare consulting, equipment installation, and regulatory assistance.';
require_once '../includes/header.php';
?>

<section class="page-hero" style="background-image: url('../assets/images/services-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-cogs"></i> Services</span>
      <h1 class="text-white mt-3">Our Services</h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Services</li>
      </ol>
    </div>
  </div>
</section>

<section class="services-section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">What We Do</span>
      <h2 class="section-title">Comprehensive Healthcare Services</h2>
      <p class="section-subtitle">End-to-end solutions for healthcare facilities, distributors, and medical professionals</p>
      <div class="section-divider"></div>
    </div>

    <div class="row g-4">
      <?php
      $services = [
        ['icon'=>'fa-truck',          'color'=>'#0066CC','title'=>'Pharmaceutical Distribution',  'desc'=>'Nationwide distribution of pharmaceutical products with cold chain management, ensuring product integrity from manufacturer to patient.','features'=>['Temperature-controlled logistics','Real-time tracking','Last-mile delivery','Emergency supply']],
        ['icon'=>'fa-link',           'color'=>'#00AEEF','title'=>'Supply Chain Management',      'desc'=>'End-to-end supply chain solutions including procurement, warehousing, inventory management, and demand forecasting.','features'=>['Inventory optimization','Demand forecasting','Vendor management','ERP integration']],
        ['icon'=>'fa-user-md',        'color'=>'#00D084','title'=>'Healthcare Consulting',        'desc'=>'Expert advisory services for healthcare facility setup, pharmaceutical procurement strategy, and regulatory compliance.','features'=>['Facility planning','Procurement strategy','Regulatory guidance','Staff training']],
        ['icon'=>'fa-tools',          'color'=>'#7C3AED','title'=>'Equipment Installation',       'desc'=>'Professional installation, commissioning, and validation of medical equipment by certified biomedical engineers.','features'=>['Site preparation','Equipment commissioning','User training','Validation documentation']],
        ['icon'=>'fa-wrench',         'color'=>'#DC2626','title'=>'Maintenance & Support',        'desc'=>'Preventive and corrective maintenance services for all medical equipment, with rapid response times and genuine spare parts.','features'=>['Preventive maintenance','24/7 emergency support','Genuine spare parts','Service contracts']],
        ['icon'=>'fa-file-medical',   'color'=>'#D97706','title'=>'Regulatory Assistance',        'desc'=>'Support for product registration, import permits, and compliance with Ethiopian Food and Drug Authority (EFDA) requirements.','features'=>['Product registration','Import permits','EFDA compliance','Documentation support']],
      ];
      foreach ($services as $i => $s): ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
        <div class="service-card">
          <div class="service-icon" style="background: <?= $s['color'] ?>1a; color: <?= $s['color'] ?>">
            <i class="fas <?= $s['icon'] ?>"></i>
          </div>
          <h4 class="service-title"><?= $s['title'] ?></h4>
          <p class="service-desc"><?= $s['desc'] ?></p>
          <ul class="service-features">
            <?php foreach ($s['features'] as $f): ?>
            <li><i class="fas fa-check-circle" style="color: <?= $s['color'] ?>"></i> <?= $f ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="contact.php?service=<?= urlencode($s['title']) ?>" class="service-cta" style="color: <?= $s['color'] ?>">
            Learn More <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="why-us bg-light">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Why Droga Pharma</span>
      <h2 class="section-title">Why Choose Us</h2>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4 align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <img src="../assets/images/why-us.jpg" alt="Why choose Droga Pharma" class="rounded-xl shadow-xl w-100" loading="lazy">
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <?php
        $reasons = [
          ['icon'=>'fa-award',       'title'=>'25+ Years Experience',    'desc'=>'Decades of expertise in pharmaceutical distribution and healthcare services.'],
          ['icon'=>'fa-shield-alt',  'title'=>'Quality Guaranteed',      'desc'=>'All products are sourced from WHO-GMP certified manufacturers.'],
          ['icon'=>'fa-clock',       'title'=>'Reliable Delivery',       'desc'=>'On-time delivery with real-time tracking across all regions.'],
          ['icon'=>'fa-headset',     'title'=>'24/7 Support',            'desc'=>'Round-the-clock customer support and emergency supply services.'],
          ['icon'=>'fa-globe',       'title'=>'Global Network',          'desc'=>'Access to 50+ international pharmaceutical and medical device brands.'],
          ['icon'=>'fa-certificate', 'title'=>'Certified & Compliant',   'desc'=>'ISO 9001:2015 certified with full EFDA regulatory compliance.'],
        ];
        foreach ($reasons as $r): ?>
        <div class="why-item">
          <div class="why-icon"><i class="fas <?= $r['icon'] ?>"></i></div>
          <div>
            <strong><?= $r['title'] ?></strong>
            <p><?= $r['desc'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="cta-section bg-gradient">
  <div class="container">
    <div class="cta-inner" data-aos="zoom-in">
      <div class="cta-icon"><i class="fas fa-cogs"></i></div>
      <h2 class="cta-title text-white">Ready to Get Started?</h2>
      <p class="cta-subtitle">Contact our team to discuss your healthcare service needs and get a customized solution.</p>
      <div class="cta-buttons">
        <a href="contact.php" class="btn btn-outline-white btn-lg"><i class="fas fa-envelope"></i> Contact Us</a>
        <a href="products.php" class="btn btn-accent btn-lg"><i class="fas fa-th-large"></i> View Products</a>
      </div>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
