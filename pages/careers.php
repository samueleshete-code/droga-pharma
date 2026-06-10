<?php
require_once '../includes/config.php';
$pageTitle = 'Careers';
$pageDesc  = 'Join the Droga Pharma team – explore career opportunities in pharmaceutical distribution, medical equipment, healthcare consulting, and more.';
require_once '../includes/header.php';

$db = getDB();
$jobsResult = $db->query("SELECT * FROM jobs WHERE is_active=1 ORDER BY created_at DESC");
$jobs = $jobsResult ? $jobsResult->fetch_all(MYSQLI_ASSOC) : [];
$csrf = generateCSRF();
?>

<section class="page-hero" style="background-image: url('../assets/images/careers-hero.jpg')">
  <div class="page-hero-overlay"></div>
  <div class="container page-hero-content">
    <div data-aos="fade-up">
      <span class="hero-badge"><i class="fas fa-briefcase"></i> Careers</span>
      <h1 class="text-white mt-3">Join Our Team</h1>
      <ol class="breadcrumb-custom">
        <li><a href="<?= SITE_URL ?>">Home</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="active">Careers</li>
      </ol>
    </div>
  </div>
</section>

<!-- Why Work With Us -->
<section class="careers-why bg-light">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Why Join Us</span>
      <h2 class="section-title">Build Your Career in Healthcare</h2>
      <p class="section-subtitle">Be part of a team that's transforming healthcare across Africa</p>
      <div class="section-divider"></div>
    </div>
    <div class="row g-4">
      <?php
      $perks = [
        ['icon'=>'fa-chart-line',    'color'=>'#0066CC','title'=>'Career Growth',       'desc'=>'Clear career paths with mentorship, training, and promotion opportunities.'],
        ['icon'=>'fa-heart',         'color'=>'#DC2626','title'=>'Health Benefits',      'desc'=>'Comprehensive medical insurance for you and your family.'],
        ['icon'=>'fa-graduation-cap','color'=>'#00AEEF','title'=>'Learning & Development','desc'=>'Continuous learning programs, workshops, and international training.'],
        ['icon'=>'fa-users',         'color'=>'#00D084','title'=>'Great Culture',        'desc'=>'Collaborative, inclusive, and supportive work environment.'],
        ['icon'=>'fa-money-bill-wave','color'=>'#D97706','title'=>'Competitive Pay',     'desc'=>'Market-competitive salaries with performance bonuses.'],
        ['icon'=>'fa-globe',         'color'=>'#7C3AED','title'=>'Global Exposure',      'desc'=>'Work with international partners and attend global healthcare conferences.'],
      ];
      foreach ($perks as $i => $p): ?>
      <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up" data-aos-delay="<?= $i * 60 ?>">
        <div class="perk-card text-center">
          <div class="perk-icon" style="background: <?= $p['color'] ?>1a; color: <?= $p['color'] ?>">
            <i class="fas <?= $p['icon'] ?>"></i>
          </div>
          <h6><?= $p['title'] ?></h6>
          <p><?= $p['desc'] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Job Listings -->
<section class="jobs-section">
  <div class="container">
    <div class="section-header" data-aos="fade-up">
      <span class="section-badge">Open Positions</span>
      <h2 class="section-title">Current Openings</h2>
      <div class="section-divider"></div>
    </div>

    <!-- Search -->
    <div class="jobs-search-bar" data-aos="fade-up">
      <i class="fas fa-search"></i>
      <input type="text" id="jobSearch" placeholder="Search jobs by title, department..." aria-label="Search jobs">
    </div>

    <div class="jobs-list" id="jobsList">
      <?php if (!empty($jobs)): ?>
        <?php foreach ($jobs as $job): ?>
        <div class="job-card" data-aos="fade-up">
          <div class="job-info">
            <div class="job-dept-badge"><?= htmlspecialchars($job['department']) ?></div>
            <h4 class="job-title"><?= htmlspecialchars($job['title']) ?></h4>
            <div class="job-meta">
              <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($job['location']) ?></span>
              <span><i class="fas fa-briefcase"></i> <?= htmlspecialchars($job['type']) ?></span>
              <span><i class="fas fa-calendar"></i> Deadline: <?= date('M d, Y', strtotime($job['deadline'])) ?></span>
            </div>
          </div>
          <div class="job-actions">
            <button class="btn btn-outline btn-sm" onclick="toggleJobDetails(this)">View Details</button>
            <button class="btn btn-primary btn-sm" onclick="openApplicationModal(<?= $job['id'] ?>, '<?= htmlspecialchars($job['title']) ?>')">Apply Now</button>
          </div>
          <div class="job-details" style="display:none">
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <?php
        $demoJobs = [
          ['dept'=>'Sales','title'=>'Medical Sales Representative','loc'=>'Addis Ababa','type'=>'Full-time','deadline'=>'Jun 30, 2025','desc'=>'We are looking for an experienced Medical Sales Representative to promote our pharmaceutical products to healthcare professionals across Addis Ababa and surrounding areas.'],
          ['dept'=>'Logistics','title'=>'Supply Chain Coordinator','loc'=>'Addis Ababa','type'=>'Full-time','deadline'=>'Jun 25, 2025','desc'=>'Coordinate pharmaceutical supply chain operations including procurement, inventory management, and distribution logistics.'],
          ['dept'=>'Technical','title'=>'Biomedical Engineer','loc'=>'Addis Ababa','type'=>'Full-time','deadline'=>'Jul 15, 2025','desc'=>'Install, maintain, and repair medical equipment at client facilities. Provide technical training and support to healthcare staff.'],
          ['dept'=>'Finance','title'=>'Senior Accountant','loc'=>'Addis Ababa','type'=>'Full-time','deadline'=>'Jun 20, 2025','desc'=>'Manage financial reporting, budgeting, and compliance for our growing pharmaceutical distribution operations.'],
          ['dept'=>'IT','title'=>'Software Developer (PHP/MySQL)','loc'=>'Addis Ababa','type'=>'Full-time','deadline'=>'Jul 1, 2025','desc'=>'Develop and maintain our internal ERP and supply chain management systems using PHP, MySQL, and modern web technologies.'],
        ];
        foreach ($demoJobs as $i => $j): ?>
        <div class="job-card" data-aos="fade-up" data-aos-delay="<?= $i * 60 ?>">
          <div class="job-info">
            <div class="job-dept-badge"><?= $j['dept'] ?></div>
            <h4 class="job-title"><?= $j['title'] ?></h4>
            <div class="job-meta">
              <span><i class="fas fa-map-marker-alt"></i> <?= $j['loc'] ?></span>
              <span><i class="fas fa-briefcase"></i> <?= $j['type'] ?></span>
              <span><i class="fas fa-calendar"></i> Deadline: <?= $j['deadline'] ?></span>
            </div>
          </div>
          <div class="job-actions">
            <button class="btn btn-outline btn-sm" onclick="toggleJobDetails(this)">View Details</button>
            <button class="btn btn-primary btn-sm" onclick="openApplicationModal(0, '<?= $j['title'] ?>')">Apply Now</button>
          </div>
          <div class="job-details" style="display:none">
            <p><?= $j['desc'] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Application Modal -->
<div id="applicationModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="modalTitle" style="display:none">
  <div class="modal-box">
    <div class="modal-header">
      <h4 id="modalTitle">Apply for Position</h4>
      <button onclick="closeApplicationModal()" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <form id="applicationForm" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
        <input type="hidden" name="job_id" id="modalJobId">
        <input type="hidden" name="job_title" id="modalJobTitle">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="form-group">
              <label>First Name *</label>
              <input type="text" name="first_name" class="form-control-custom" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Last Name *</label>
              <input type="text" name="last_name" class="form-control-custom" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Email *</label>
              <input type="email" name="email" class="form-control-custom" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Phone *</label>
              <input type="tel" name="phone" class="form-control-custom" required>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Cover Letter</label>
              <textarea name="cover_letter" class="form-control-custom" rows="4" placeholder="Tell us why you're a great fit..."></textarea>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <label>Upload CV (PDF, DOC, DOCX – max 5MB) *</label>
              <input type="file" name="cv" class="form-control-custom" accept=".pdf,.doc,.docx" required>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">
              <i class="fas fa-paper-plane"></i> Submit Application
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleJobDetails(btn) {
  const details = btn.closest('.job-card').querySelector('.job-details');
  const isOpen = details.style.display !== 'none';
  details.style.display = isOpen ? 'none' : 'block';
  btn.textContent = isOpen ? 'View Details' : 'Hide Details';
}
function openApplicationModal(id, title) {
  document.getElementById('modalJobId').value = id;
  document.getElementById('modalJobTitle').value = title;
  document.getElementById('modalTitle').textContent = 'Apply for: ' + title;
  document.getElementById('applicationModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function closeApplicationModal() {
  document.getElementById('applicationModal').style.display = 'none';
  document.body.style.overflow = '';
}
document.getElementById('applicationModal')?.addEventListener('click', function(e) {
  if (e.target === this) closeApplicationModal();
});
document.getElementById('applicationForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('button[type="submit"]');
  btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
  try {
    const res = await fetch('../api/apply.php', { method: 'POST', body: new FormData(this) });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const data = await res.json();
    showToast(data.success ? 'success' : 'error', data.message);
    if (data.success) { this.reset(); closeApplicationModal(); }
  } catch(err) { showToast('error', 'Submission failed: ' + err.message); }
  finally { btn.disabled = false; btn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application'; }
});
</script>

<?php require_once '../includes/footer.php'; ?>
