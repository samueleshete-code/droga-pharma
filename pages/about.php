<?php
require_once '../includes/config.php';
$pageTitle    = 'About Us';
$pageDesc     = 'Learn about Droga Pharma PLC – our history, leadership, mission, vision, and commitment to transforming healthcare across Africa.';
$pageKeywords = 'about Droga Pharma, pharmaceutical company Ethiopia, healthcare leadership, company history';
require_once '../includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero" style="background-image: url('../assets/images/about-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-building"></i> About Us</span>
      <h1 class="text-white mt-3">Our Story &amp; Mission</h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb-custom">
          <li><a href="<?= SITE_URL ?>">Home</a></li>
          <li class="separator"><i class="fas fa-chevron-right"></i></li>
          <li class="active">About Us</li>
        </ol>
      </nav>
    </div>
  </div>
</section>

<!-- ─── Company Overview ──────────────────────────────────────────────────── -->
<section class="company-overview">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="section-badge">Who We Are</span>
        <h2 class="section-title">Ethiopia's Leading Healthcare Solutions Company</h2>
        <p>Droga Pharma PLC was established in 1999 with a clear vision: to bridge the gap between global pharmaceutical innovation and Ethiopia's healthcare needs. Over 25 years, we have grown from a small distribution company into a comprehensive healthcare solutions provider.</p>
        <p>Today, we serve over 1,200 hospitals, clinics, and healthcare facilities across all 11 regions of Ethiopia, distributing more than 5,000 pharmaceutical and medical products from 50+ global partners.</p>
        <div class="overview-highlights">
          <div class="highlight-item"><i class="fas fa-check-circle"></i> WHO-GMP Certified Operations</div>
          <div class="highlight-item"><i class="fas fa-check-circle"></i> ISO 9001:2015 Quality Management</div>
          <div class="highlight-item"><i class="fas fa-check-circle"></i> EFDA Licensed Pharmaceutical Importer</div>
          <div class="highlight-item"><i class="fas fa-check-circle"></i> Cold Chain Certified Distribution</div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <div class="overview-image-wrap">
          <img src="../assets/images/company-overview.jpg" alt="Droga Pharma headquarters" class="rounded-xl shadow-xl w-100" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── Chairman Message ──────────────────────────────────────────────────── -->
<section class="chairman-message bg-light" id="chairman">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-4 text-center" data-aos="fade-right">
        <div class="chairman-photo-wrap">
          <img src="../assets/images/chairman.jpg" alt="Chairman, Droga Pharma PLC" class="chairman-photo" loading="lazy">
          <div class="chairman-badge">
            <i class="fas fa-quote-left"></i>
          </div>
        </div>
        <h4 class="mt-4 mb-1">Ato Droga Hailemariam</h4>
        <p class="text-muted">Founder &amp; Chairman</p>
      </div>
      <div class="col-lg-8" data-aos="fade-left">
        <span class="section-badge">Chairman's Message</span>
        <h2 class="section-title">A Message from Our Founder</h2>
        <div class="chairman-quote">
          <p>"When I founded Droga Pharma in 1999, my dream was simple: to ensure that every Ethiopian has access to quality, affordable healthcare products. Twenty-five years later, that dream has grown into a mission that touches millions of lives across our nation."</p>
          <p>"We have built not just a company, but a healthcare ecosystem — connecting global innovation with local needs, training healthcare professionals, and investing in the future of Ethiopian medicine. Our journey is far from over."</p>
          <p>"As we look to the future, we remain committed to our founding principles: quality, integrity, and service to humanity. Together, we will continue transforming healthcare across Africa."</p>
        </div>
        <div class="chairman-signature">
          <img src="../assets/images/signature.png" alt="Chairman signature" height="50" loading="lazy">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── Mission, Vision, Values ───────────────────────────────────────────── -->
<section class="mvv-section" id="mission">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Our Foundation</span>
      <h2 class="section-title">Mission, Vision &amp; Values</h2>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4">
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
        <div class="mvv-card mvv-mission">
          <div class="mvv-icon"><i class="fas fa-bullseye"></i></div>
          <h3>Our Mission</h3>
          <p>To deliver quality pharmaceutical products, medical equipment, and healthcare services that improve patient outcomes and support healthcare professionals across Africa.</p>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="mvv-card mvv-vision">
          <div class="mvv-icon"><i class="fas fa-eye"></i></div>
          <h3>Our Vision</h3>
          <p>To be Africa's most trusted and innovative healthcare solutions company, recognized for excellence, integrity, and transformative impact on public health.</p>
        </div>
      </div>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="mvv-card mvv-values">
          <div class="mvv-icon"><i class="fas fa-heart"></i></div>
          <h3>Core Values</h3>
          <ul class="values-list">
            <li><i class="fas fa-check"></i> Quality &amp; Excellence</li>
            <li><i class="fas fa-check"></i> Integrity &amp; Transparency</li>
            <li><i class="fas fa-check"></i> Innovation &amp; Progress</li>
            <li><i class="fas fa-check"></i> Patient-Centered Care</li>
            <li><i class="fas fa-check"></i> Community Responsibility</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── Timeline ──────────────────────────────────────────────────────────── -->
<section class="timeline-section bg-light" id="history">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Our Journey</span>
      <h2 class="section-title">Corporate Timeline</h2>
      <p class="section-subtitle">25 years of growth, innovation, and healthcare transformation</p>
      <div class="section-divider"></div>
    </div>
    <div class="timeline">
      <?php
      $milestones = [
        ['year'=>'1999','title'=>'Company Founded',          'desc'=>'Droga Pharma PLC established in Addis Ababa with a focus on pharmaceutical distribution.'],
        ['year'=>'2003','title'=>'First International Partner','desc'=>'Signed first international partnership agreement with a European pharmaceutical manufacturer.'],
        ['year'=>'2007','title'=>'Medical Equipment Division','desc'=>'Expanded into medical equipment supply, partnering with leading global device manufacturers.'],
        ['year'=>'2010','title'=>'ISO Certification',         'desc'=>'Achieved ISO 9001:2008 certification, establishing quality management standards.'],
        ['year'=>'2013','title'=>'Nationwide Expansion',      'desc'=>'Extended distribution network to all regions of Ethiopia, reaching rural healthcare facilities.'],
        ['year'=>'2016','title'=>'Laboratory Solutions',      'desc'=>'Launched dedicated laboratory equipment and consumables division.'],
        ['year'=>'2019','title'=>'20th Anniversary',          'desc'=>'Celebrated 20 years of excellence, serving 800+ healthcare facilities nationwide.'],
        ['year'=>'2021','title'=>'Digital Transformation',    'desc'=>'Launched digital supply chain management platform for real-time inventory tracking.'],
        ['year'=>'2024','title'=>'Research Center',           'desc'=>'Opened dedicated pharmaceutical research and innovation center in Addis Ababa.'],
        ['year'=>'2025','title'=>'Pan-African Expansion',     'desc'=>'Began expansion into neighboring African countries, starting with Kenya and Djibouti.'],
      ];
      foreach ($milestones as $i => $m): ?>
      <div class="timeline-item <?= $i % 2 === 0 ? 'left' : 'right' ?>" data-aos="<?= $i % 2 === 0 ? 'fade-right' : 'fade-left' ?>">
        <div class="timeline-content">
          <div class="timeline-year"><?= $m['year'] ?></div>
          <h4><?= $m['title'] ?></h4>
          <p><?= $m['desc'] ?></p>
        </div>
        <div class="timeline-dot"></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── Leadership Team ───────────────────────────────────────────────────── -->
<section class="leadership-section" id="leadership">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Our People</span>
      <h2 class="section-title">Leadership Team</h2>
      <p class="section-subtitle">Experienced professionals driving healthcare innovation</p>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4">
      <?php
      $leaders = [
        ['name'=>'Ato Droga Hailemariam',  'role'=>'Founder & Chairman',          'img'=>'leader-1.jpg'],
        ['name'=>'Dr. Selamawit Bekele',   'role'=>'Chief Executive Officer',      'img'=>'leader-2.jpg'],
        ['name'=>'Ato Yohannes Tadesse',   'role'=>'Chief Operations Officer',     'img'=>'leader-3.jpg'],
        ['name'=>'Dr. Mekdes Alemu',       'role'=>'Chief Medical Officer',        'img'=>'leader-4.jpg'],
        ['name'=>'Ato Biruk Haile',        'role'=>'Chief Financial Officer',      'img'=>'leader-5.jpg'],
        ['name'=>'Dr. Tigist Worku',       'role'=>'Head of Research & Innovation','img'=>'leader-6.jpg'],
        ['name'=>'Ato Solomon Girma',      'role'=>'Head of Distribution',         'img'=>'leader-7.jpg'],
        ['name'=>'Ms. Hiwot Tesfaye',      'role'=>'Head of Human Resources',      'img'=>'leader-8.jpg'],
      ];
      foreach ($leaders as $i => $l): ?>
      <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 80 ?>">
        <div class="leader-card">
          <div class="leader-photo">
            <img src="../assets/images/team/<?= $l['img'] ?>" alt="<?= htmlspecialchars($l['name']) ?>" loading="lazy">
            <div class="leader-overlay">
              <div class="leader-social">
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="Email"><i class="fas fa-envelope"></i></a>
              </div>
            </div>
          </div>
          <div class="leader-info">
            <h5><?= htmlspecialchars($l['name']) ?></h5>
            <span><?= htmlspecialchars($l['role']) ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── CSR ───────────────────────────────────────────────────────────────── -->
<section class="csr-section bg-light" id="csr">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Giving Back</span>
      <h2 class="section-title">Corporate Social Responsibility</h2>
      <p class="section-subtitle">Investing in communities and the future of Ethiopian healthcare</p>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4">
      <?php
      $csr = [
        ['icon'=>'fa-graduation-cap','color'=>'#0066CC','title'=>'Healthcare Education',   'desc'=>'Sponsoring medical students and healthcare professional training programs across Ethiopia.'],
        ['icon'=>'fa-clinic-medical','color'=>'#00AEEF','title'=>'Rural Health Access',    'desc'=>'Donating medicines and equipment to rural health centers in underserved communities.'],
        ['icon'=>'fa-leaf',          'color'=>'#00D084','title'=>'Environmental Stewardship','desc'=>'Implementing green supply chain practices and reducing pharmaceutical waste.'],
        ['icon'=>'fa-hands-helping', 'color'=>'#7C3AED','title'=>'Community Programs',    'desc'=>'Supporting health awareness campaigns, free medical camps, and disease prevention initiatives.'],
      ];
      foreach ($csr as $i => $item): ?>
      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="csr-card">
          <div class="csr-icon" style="background: <?= $item['color'] ?>1a; color: <?= $item['color'] ?>">
            <i class="fas <?= $item['icon'] ?>"></i>
          </div>
          <h4><?= $item['title'] ?></h4>
          <p><?= $item['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section bg-gradient">
  <div class="container">
    <div class="cta-inner" data-aos="zoom-in">
      <div class="cta-icon"><i class="fas fa-handshake"></i></div>
      <h2 class="cta-title text-white">Partner With Us</h2>
      <p class="cta-subtitle">Join our growing network of healthcare partners and help us transform healthcare across Africa.</p>
      <div class="cta-buttons">
        <a href="contact.php" class="btn btn-outline-white btn-lg"><i class="fas fa-envelope"></i> Get In Touch</a>
        <a href="partners.php" class="btn btn-accent btn-lg"><i class="fas fa-handshake"></i> Our Partners</a>
      </div>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
