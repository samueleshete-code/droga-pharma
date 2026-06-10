<?php
require_once 'includes/config.php';
$pageTitle    = 'Home';
$pageDesc     = 'Droga Pharma PLC – Leading pharmaceutical distribution, medical equipment supply, and healthcare innovation across Africa.';
$pageKeywords = 'Droga Pharma, pharmaceutical Ethiopia, medical equipment Africa, healthcare distribution, laboratory solutions';
require_once 'includes/header.php';

// Fetch latest news
$db = getDB();
$newsResult = $db->query("SELECT id, title, slug, excerpt, image, created_at, category FROM news WHERE is_published=1 ORDER BY created_at DESC LIMIT 3");
$latestNews = $newsResult ? $newsResult->fetch_all(MYSQLI_ASSOC) : [];

// Fetch partners
$partnersResult = $db->query("SELECT id, name, logo, country FROM partners WHERE is_active=1 ORDER BY sort_order ASC LIMIT 18");
$partners = $partnersResult ? $partnersResult->fetch_all(MYSQLI_ASSOC) : [];

// Testimonials table doesn't exist in schema yet – skip gracefully
$testimonials = [];
?>

<!-- ═══════════════════════════════════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════════════════════════════════ -->
<section class="hero-section" id="home" aria-label="Hero">
  <div class="swiper hero-swiper">
    <div class="swiper-wrapper">

      <!-- Slide 1 -->
      <div class="swiper-slide hero-slide" style="background-image: url('assets/images/hero-1.jpg')">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
          <div class="hero-text" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-shield-alt"></i> WHO-GMP Certified</span>
            <h1 class="hero-title">Transforming Healthcare<br><span class="text-gradient">Across Africa</span></h1>
            <p class="hero-subtitle">Leading <span id="typedText" class="typed-text"></span></p>
            <div class="hero-cta">
              <a href="pages/products.php" class="btn btn-primary btn-lg">
                <i class="fas fa-th-large"></i> Explore Products
              </a>
              <a href="pages/contact.php" class="btn btn-outline-white btn-lg">
                <i class="fas fa-envelope"></i> Contact Us
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="swiper-slide hero-slide" style="background-image: url('assets/images/hero-2.jpg')">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
          <div class="hero-text" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-award"></i> 25+ Years of Excellence</span>
            <h1 class="hero-title">Your Trusted<br><span class="text-gradient">Healthcare Partner</span></h1>
            <p class="hero-subtitle">Delivering quality medical solutions to hospitals, clinics, and healthcare facilities nationwide.</p>
            <div class="hero-cta">
              <a href="pages/about.php" class="btn btn-primary btn-lg">
                <i class="fas fa-info-circle"></i> Our Story
              </a>
              <a href="pages/partners.php" class="btn btn-outline-white btn-lg">
                <i class="fas fa-handshake"></i> Our Partners
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3 -->
      <div class="swiper-slide hero-slide" style="background-image: url('assets/images/hero-3.jpg')">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
          <div class="hero-text" data-aos="fade-up">
            <span class="hero-badge"><i class="fas fa-flask"></i> Research & Innovation</span>
            <h1 class="hero-title">Advancing<br><span class="text-gradient">Medical Science</span></h1>
            <p class="hero-subtitle">Investing in research, innovation, and scientific collaboration to improve healthcare outcomes.</p>
            <div class="hero-cta">
              <a href="pages/research.php" class="btn btn-primary btn-lg">
                <i class="fas fa-microscope"></i> Our Research
              </a>
              <a href="pages/careers.php" class="btn btn-outline-white btn-lg">
                <i class="fas fa-users"></i> Join Our Team
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="swiper-pagination"></div>
  </div>

  <!-- Hero Features Bar -->
  <div class="hero-features-bar">
    <div class="container">
      <div class="hero-features-grid">
        <div class="hero-feature">
          <div class="hero-feature-icon"><i class="fas fa-shield-alt"></i></div>
          <div>
            <strong>Trusted Partner</strong>
            <span>WHO-GMP Certified</span>
          </div>
        </div>
        <div class="hero-feature">
          <div class="hero-feature-icon"><i class="fas fa-truck"></i></div>
          <div>
            <strong>Nationwide Distribution</strong>
            <span>All Regions Covered</span>
          </div>
        </div>
        <div class="hero-feature">
          <div class="hero-feature-icon"><i class="fas fa-globe"></i></div>
          <div>
            <strong>International Partners</strong>
            <span>50+ Global Brands</span>
          </div>
        </div>
        <div class="hero-feature">
          <div class="hero-feature-icon"><i class="fas fa-certificate"></i></div>
          <div>
            <strong>Quality Assured</strong>
            <span>ISO 9001:2015</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     ABOUT PREVIEW
════════════════════════════════════════════════════════════════════════════ -->
<section class="about-preview" id="about">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-lg-6" data-aos="fade-right">
        <div class="about-image-stack">
          <img src="assets/images/about-main.jpg" alt="Droga Pharma facility" class="about-img-main" loading="lazy">
          <img src="assets/images/about-secondary.jpg" alt="Healthcare professionals" class="about-img-secondary" loading="lazy">
          <div class="about-badge-float">
            <span class="badge-number">25+</span>
            <span class="badge-label">Years of Excellence</span>
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left">
        <span class="section-badge">About Droga Pharma</span>
        <h2 class="section-title">Ethiopia's Premier Healthcare Solutions Provider</h2>
        <p class="lead-text">Founded in 1999, Droga Pharma PLC has grown to become one of Ethiopia's most trusted pharmaceutical and medical equipment companies, serving thousands of healthcare facilities across the nation.</p>
        <p>We bridge the gap between global healthcare innovation and local healthcare needs, ensuring that quality medical products reach every corner of Ethiopia and beyond.</p>

        <div class="about-values-grid">
          <div class="about-value-item">
            <div class="value-icon"><i class="fas fa-eye"></i></div>
            <div>
              <strong>Our Vision</strong>
              <p>To be Africa's most trusted healthcare solutions company.</p>
            </div>
          </div>
          <div class="about-value-item">
            <div class="value-icon"><i class="fas fa-bullseye"></i></div>
            <div>
              <strong>Our Mission</strong>
              <p>Delivering quality healthcare products and services that improve lives.</p>
            </div>
          </div>
        </div>

        <a href="pages/about.php" class="btn btn-primary mt-3">
          <i class="fas fa-arrow-right"></i> Learn More About Us
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     STATISTICS SECTION
════════════════════════════════════════════════════════════════════════════ -->
<section class="stats-section bg-gradient">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
          <div class="stat-number"><span data-target="25">0</span>+</div>
          <div class="stat-label">Years of Experience</div>
        </div>
      </div>
      <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-box-open"></i></div>
          <div class="stat-number"><span data-target="5000">0</span>+</div>
          <div class="stat-label">Products Distributed</div>
        </div>
      </div>
      <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-hospital"></i></div>
          <div class="stat-number"><span data-target="1200">0</span>+</div>
          <div class="stat-label">Partner Hospitals</div>
        </div>
      </div>
      <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-card">
          <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
          <div class="stat-number"><span data-target="11">0</span></div>
          <div class="stat-label">Regions Served</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     BUSINESS UNITS
════════════════════════════════════════════════════════════════════════════ -->
<section class="business-units bg-light" id="products">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">What We Offer</span>
      <h2 class="section-title">Our Business Units</h2>
      <p class="section-subtitle">Comprehensive healthcare solutions across six specialized divisions</p>
      <div class="section-divider"></div>
    </div>

    <div class="row g-4">
      <?php
      $units = [
        ['icon'=>'fa-pills',       'color'=>'#0066CC', 'title'=>'Pharmaceuticals',      'desc'=>'Wide range of branded and generic medicines from leading global manufacturers.',          'link'=>'products.php?cat=pharmaceuticals', 'count'=>'2,000+ Products'],
        ['icon'=>'fa-stethoscope', 'color'=>'#00AEEF', 'title'=>'Medical Equipment',    'desc'=>'State-of-the-art diagnostic and therapeutic medical devices for modern healthcare.',     'link'=>'products.php?cat=medical-devices',  'count'=>'500+ Devices'],
        ['icon'=>'fa-microscope',  'color'=>'#00D084', 'title'=>'Diagnostics',           'desc'=>'Advanced diagnostic solutions for accurate and timely disease detection.',               'link'=>'products.php?cat=diagnostics',      'count'=>'300+ Solutions'],
        ['icon'=>'fa-flask',       'color'=>'#7C3AED', 'title'=>'Laboratory Solutions', 'desc'=>'Complete laboratory equipment and consumables for research and clinical labs.',          'link'=>'products.php?cat=laboratory',       'count'=>'800+ Items'],
        ['icon'=>'fa-syringe',     'color'=>'#DC2626', 'title'=>'Surgical Supplies',    'desc'=>'High-quality surgical instruments and disposables for operating theatres.',              'link'=>'products.php?cat=surgical',         'count'=>'600+ Products'],
        ['icon'=>'fa-user-md',     'color'=>'#D97706', 'title'=>'Healthcare Consulting','desc'=>'Expert advisory services for healthcare facility setup and management.',                 'link'=>'services.php',                      'count'=>'Expert Team'],
      ];
      foreach ($units as $i => $unit): ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
        <div class="business-unit-card tilt-card">
          <div class="bu-icon" style="background: <?= $unit['color'] ?>1a; color: <?= $unit['color'] ?>">
            <i class="fas <?= $unit['icon'] ?>"></i>
          </div>
          <div class="bu-count" style="color: <?= $unit['color'] ?>"><?= $unit['count'] ?></div>
          <h4 class="bu-title"><?= $unit['title'] ?></h4>
          <p class="bu-desc"><?= $unit['desc'] ?></p>
          <a href="pages/<?= $unit['link'] ?>" class="bu-link" style="color: <?= $unit['color'] ?>">
            Explore <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     PARTNERS SHOWCASE
════════════════════════════════════════════════════════════════════════════ -->
<section class="partners-section" id="partners">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Global Network</span>
      <h2 class="section-title">Our Trusted Partners</h2>
      <p class="section-subtitle">Collaborating with world-leading healthcare brands to bring the best to Africa</p>
      <div class="section-divider"></div>
    </div>

    <div class="swiper partners-swiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <?php if (!empty($partners)): ?>
          <?php foreach ($partners as $partner): ?>
          <div class="swiper-slide partner-slide">
            <div class="partner-logo-card">
              <img src="<?= SITE_URL ?>/uploads/partners/<?= htmlspecialchars($partner['logo']) ?>"
                   alt="<?= htmlspecialchars($partner['name']) ?>" loading="lazy">
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?php $demoPartners = ['Pfizer','Novartis','Roche','Abbott','Siemens','Philips','GE Healthcare','Becton Dickinson','3M Health','Medtronic','Johnson & Johnson','Bayer'];
          foreach ($demoPartners as $p): ?>
          <div class="swiper-slide partner-slide">
            <div class="partner-logo-card">
              <span class="partner-name-placeholder"><?= $p ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="pages/partners.php" class="btn btn-outline">
        <i class="fas fa-handshake"></i> View All Partners
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     RESEARCH & INNOVATION
════════════════════════════════════════════════════════════════════════════ -->
<section class="research-preview bg-light" id="research">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Innovation</span>
      <h2 class="section-title">Research & Innovation</h2>
      <p class="section-subtitle">Driving healthcare advancement through science, technology, and collaboration</p>
      <div class="section-divider"></div>
    </div>

    <div class="row g-4">
      <?php
      $research = [
        ['icon'=>'fa-dna',         'color'=>'#0066CC', 'title'=>'Research Grants',        'desc'=>'Supporting local and international research initiatives to advance pharmaceutical sciences in Ethiopia.'],
        ['icon'=>'fa-lightbulb',   'color'=>'#00AEEF', 'title'=>'Innovation Programs',    'desc'=>'Incubating healthcare startups and innovative solutions that address Africa\'s unique health challenges.'],
        ['icon'=>'fa-users',       'color'=>'#00D084', 'title'=>'Scientific Collaboration','desc'=>'Partnering with universities, research institutes, and global health organizations worldwide.'],
        ['icon'=>'fa-book-medical','color'=>'#7C3AED', 'title'=>'Publications',           'desc'=>'Contributing to peer-reviewed journals and scientific publications in pharmaceutical research.'],
      ];
      foreach ($research as $i => $item): ?>
      <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
        <div class="research-card">
          <div class="research-icon" style="background: <?= $item['color'] ?>1a; color: <?= $item['color'] ?>">
            <i class="fas <?= $item['icon'] ?>"></i>
          </div>
          <h4><?= $item['title'] ?></h4>
          <p><?= $item['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="pages/research.php" class="btn btn-primary">
        <i class="fas fa-flask"></i> Explore Our Research
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     TESTIMONIALS
════════════════════════════════════════════════════════════════════════════ -->
<section class="testimonials-section bg-gradient-dark" id="testimonials">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge" style="background: rgba(255,255,255,0.15); color: #fff;">What They Say</span>
      <h2 class="section-title text-white">Trusted by Healthcare Leaders</h2>
      <p class="section-subtitle" style="color: rgba(255,255,255,0.7)">Hear from hospitals, clinics, and partners across Africa</p>
      <div class="section-divider"></div>
    </div>

    <div class="swiper testimonials-swiper" data-aos="fade-up">
      <div class="swiper-wrapper">
        <?php if (!empty($testimonials)): ?>
          <?php foreach ($testimonials as $t): ?>
          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-stars">
                <?php for ($s = 0; $s < ($t['rating'] ?? 5); $s++): ?>
                  <i class="fas fa-star"></i>
                <?php endfor; ?>
              </div>
              <p class="testimonial-text">"<?= htmlspecialchars($t['content']) ?>"</p>
              <div class="testimonial-author">
                <?php if (!empty($t['photo'])): ?>
                  <img src="<?= SITE_URL ?>/uploads/testimonials/<?= htmlspecialchars($t['photo']) ?>" alt="<?= htmlspecialchars($t['name']) ?>">
                <?php else: ?>
                  <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                  <strong><?= htmlspecialchars($t['name']) ?></strong>
                  <span><?= htmlspecialchars($t['position']) ?>, <?= htmlspecialchars($t['organization']) ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <?php $demoTestimonials = [
            ['name'=>'Dr. Abebe Girma',    'pos'=>'Chief Medical Officer',  'org'=>'Black Lion Hospital',       'text'=>'Droga Pharma has been our most reliable pharmaceutical partner for over a decade. Their product quality and delivery consistency are unmatched.'],
            ['name'=>'Dr. Tigist Haile',   'pos'=>'Director',               'org'=>'Tikur Anbessa Hospital',    'text'=>'The medical equipment supplied by Droga Pharma has significantly improved our diagnostic capabilities. Excellent after-sales support.'],
            ['name'=>'Mr. Solomon Bekele', 'pos'=>'Procurement Manager',    'org'=>'Ethio-Djibouti Railways',   'text'=>'Their supply chain reliability and competitive pricing make them our preferred healthcare supplier for all our medical needs.'],
            ['name'=>'Dr. Meron Tadesse',  'pos'=>'Head of Laboratory',     'org'=>'St. Paul\'s Hospital',      'text'=>'The laboratory solutions from Droga Pharma have transformed our testing capabilities. Professional team, quality products.'],
            ['name'=>'Dr. Yonas Alemu',    'pos'=>'Surgical Department Head','org'=>'Yekatit 12 Hospital',      'text'=>'Consistent quality, timely delivery, and excellent technical support. Droga Pharma is a true healthcare partner.'],
          ];
          foreach ($demoTestimonials as $t): ?>
          <div class="swiper-slide">
            <div class="testimonial-card">
              <div class="testimonial-stars">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
              </div>
              <p class="testimonial-text">"<?= $t['text'] ?>"</p>
              <div class="testimonial-author">
                <div class="testimonial-avatar"><?= strtoupper(substr($t['name'], 0, 1)) ?></div>
                <div>
                  <strong><?= $t['name'] ?></strong>
                  <span><?= $t['pos'] ?>, <?= $t['org'] ?></span>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      <div class="testimonials-pagination swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     LATEST NEWS
════════════════════════════════════════════════════════════════════════════ -->
<section class="news-section" id="news">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Stay Informed</span>
      <h2 class="section-title">Latest News & Updates</h2>
      <p class="section-subtitle">Stay up to date with our latest developments, partnerships, and healthcare insights</p>
      <div class="section-divider"></div>
    </div>

    <div class="row g-4">
      <?php if (!empty($latestNews)): ?>
        <?php foreach ($latestNews as $i => $article): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <article class="news-card">
            <div class="news-image">
              <img src="<?= SITE_URL ?>/uploads/news/<?= htmlspecialchars($article['image']) ?>"
                   alt="<?= htmlspecialchars($article['title']) ?>" loading="lazy">
              <span class="news-category"><?= htmlspecialchars($article['category']) ?></span>
            </div>
            <div class="news-body">
              <div class="news-meta">
                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($article['created_at'])) ?></span>
              </div>
              <h4 class="news-title">
                <a href="pages/news.php?slug=<?= htmlspecialchars($article['slug']) ?>">
                  <?= htmlspecialchars($article['title']) ?>
                </a>
              </h4>
              <p class="news-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
              <a href="pages/news.php?slug=<?= htmlspecialchars($article['slug']) ?>" class="news-read-more">
                Read More <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php $demoNews = [
          ['cat'=>'Company News',  'date'=>'May 20, 2025', 'title'=>'Droga Pharma Expands Distribution Network to Southern Ethiopia', 'excerpt'=>'We are proud to announce the expansion of our distribution network, now reaching all zones of Southern Ethiopia with faster delivery times.'],
          ['cat'=>'Partnership',   'date'=>'May 10, 2025', 'title'=>'New Partnership with Siemens Healthineers for Advanced Diagnostics', 'excerpt'=>'Droga Pharma signs a landmark agreement with Siemens Healthineers to bring cutting-edge diagnostic equipment to Ethiopian hospitals.'],
          ['cat'=>'Innovation',    'date'=>'Apr 28, 2025', 'title'=>'Droga Pharma Launches Digital Health Platform for Supply Chain', 'excerpt'=>'Our new digital platform enables real-time tracking of pharmaceutical supplies, improving efficiency and reducing stockouts across facilities.'],
        ];
        foreach ($demoNews as $i => $n): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
          <article class="news-card">
            <div class="news-image news-image-placeholder">
              <div class="news-placeholder-bg"></div>
              <span class="news-category"><?= $n['cat'] ?></span>
            </div>
            <div class="news-body">
              <div class="news-meta">
                <span><i class="fas fa-calendar"></i> <?= $n['date'] ?></span>
              </div>
              <h4 class="news-title"><a href="pages/news.php"><?= $n['title'] ?></a></h4>
              <p class="news-excerpt"><?= $n['excerpt'] ?></p>
              <a href="pages/news.php" class="news-read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </article>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="pages/news.php" class="btn btn-outline">
        <i class="fas fa-newspaper"></i> View All News
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════════════════════
     CALL TO ACTION
════════════════════════════════════════════════════════════════════════════ -->
<section class="cta-section bg-gradient">
  <div class="container">
    <div class="cta-inner" data-aos="zoom-in">
      <div class="cta-icon"><i class="fas fa-heartbeat"></i></div>
      <h2 class="cta-title text-white">Let's Improve Healthcare Together</h2>
      <p class="cta-subtitle">Partner with Droga Pharma to bring quality healthcare solutions to your facility. Our team is ready to support your needs.</p>
      <div class="cta-buttons">
        <a href="pages/contact.php" class="btn btn-outline-white btn-lg">
          <i class="fas fa-envelope"></i> Get In Touch
        </a>
        <a href="pages/products.php" class="btn btn-accent btn-lg">
          <i class="fas fa-th-large"></i> Browse Products
        </a>
      </div>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
