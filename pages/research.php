<?php
require_once '../includes/config.php';
$pageTitle = 'Research & Innovation';
$pageDesc  = 'Droga Pharma\'s research and innovation programs – grants, scientific collaborations, publications, and healthcare innovation projects.';
require_once '../includes/header.php';
?>

<section class="page-hero" style="background-image: url('../assets/images/research-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-flask"></i> Research</span>
      <h1 class="text-white mt-3">Research &amp; Innovation</h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Research</li>
      </ol>
    </div>
  </div>
</section>

<!-- Research Overview -->
<section class="research-overview">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <span class="section-badge">Our Commitment</span>
        <h2 class="section-title">Advancing Healthcare Through Science</h2>
        <p>At Droga Pharma, we believe that innovation is the foundation of better healthcare. Our research and innovation programs invest in the future of Ethiopian and African medicine, supporting scientists, funding studies, and fostering collaboration between academia and industry.</p>
        <p>From malaria drug resistance research to digital health innovation, we are committed to generating knowledge that improves patient outcomes across the continent.</p>
        <div class="research-stats">
          <div class="r-stat"><span data-target="15">0</span>+<small>Active Projects</small></div>
          <div class="r-stat"><span data-target="8">0</span><small>University Partners</small></div>
          <div class="r-stat"><span data-target="42">0</span>+<small>Publications</small></div>
          <div class="r-stat"><span data-target="5">0</span>M+<small>ETB Invested</small></div>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <img src="../assets/images/research-lab.jpg" alt="Research laboratory" class="rounded-xl shadow-xl w-100" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- Research Areas -->
<section class="research-areas bg-light">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Focus Areas</span>
      <h2 class="section-title">Research Programs</h2>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4">
      <?php
      $programs = [
        ['icon'=>'fa-dna',          'color'=>'#0066CC','title'=>'Research Grants',         'desc'=>'We fund pharmaceutical and biomedical research at Ethiopian universities and research institutes, with a focus on tropical diseases, antimicrobial resistance, and affordable medicine development.','tags'=>['Malaria','TB','AMR','NTDs']],
        ['icon'=>'fa-lightbulb',    'color'=>'#00AEEF','title'=>'Innovation Programs',     'desc'=>'Our healthcare innovation incubator supports startups and entrepreneurs developing technology solutions for African healthcare challenges, from telemedicine to supply chain optimization.','tags'=>['Digital Health','MedTech','HealthTech','AI']],
        ['icon'=>'fa-university',   'color'=>'#00D084','title'=>'Academic Collaboration',  'desc'=>'Strategic partnerships with Addis Ababa University, Jimma University, and international institutions for joint research, student exchange, and knowledge transfer programs.','tags'=>['AAU','JU','WHO','CDC']],
        ['icon'=>'fa-book-open',    'color'=>'#7C3AED','title'=>'Publications',            'desc'=>'Contributing to peer-reviewed scientific literature in pharmaceutical sciences, public health, and healthcare management. Our researchers publish in leading international journals.','tags'=>['Peer-reviewed','Open Access','Clinical','Pharmacology']],
        ['icon'=>'fa-project-diagram','color'=>'#DC2626','title'=>'Active Projects',       'desc'=>'Current research projects include malaria drug resistance mapping, COVID-19 vaccine cold chain optimization, and AI-powered pharmaceutical demand forecasting.','tags'=>['Ongoing','2024-2026','Multi-site','Funded']],
        ['icon'=>'fa-handshake',    'color'=>'#D97706','title'=>'Industry Partnerships',   'desc'=>'Collaborating with global pharmaceutical companies on clinical trials, post-market surveillance, and pharmacovigilance studies in the Ethiopian context.','tags'=>['Clinical Trials','PV','Phase III','Regulatory']],
      ];
      foreach ($programs as $i => $p): ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
        <div class="research-program-card">
          <div class="rp-icon" style="background: <?= $p['color'] ?>1a; color: <?= $p['color'] ?>">
            <i class="fas <?= $p['icon'] ?>"></i>
          </div>
          <h4><?= $p['title'] ?></h4>
          <p><?= $p['desc'] ?></p>
          <div class="rp-tags">
            <?php foreach ($p['tags'] as $tag): ?>
            <span class="rp-tag" style="background: <?= $p['color'] ?>1a; color: <?= $p['color'] ?>"><?= $tag ?></span>
            <?php endforeach; ?>
          </div>
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
      <div class="cta-icon"><i class="fas fa-microscope"></i></div>
      <h2 class="cta-title text-white">Collaborate With Us</h2>
      <p class="cta-subtitle">Are you a researcher, institution, or company interested in collaborating on healthcare research? We welcome partnerships that advance African healthcare.</p>
      <div class="cta-buttons">
        <a href="contact.php?subject=Research+Collaboration" class="btn btn-outline-white btn-lg"><i class="fas fa-envelope"></i> Get In Touch</a>
      </div>
    </div>
  </div>
</section>

<?php require_once '../includes/footer.php'; ?>
